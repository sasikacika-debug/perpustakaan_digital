<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    protected function authorizeRole(): ?\Illuminate\Http\RedirectResponse
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (!in_array(Auth::user()->role, ['petugas', 'kepala'])) {
            return redirect('/dashboard');
        }

        return null;
    }

    public function dashboard()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalMembers = User::where('role', 'anggota')->count();
        $pendingLoans = Loan::where('status', 'pending')->count();
        $activeLoans = Loan::where('status', 'approved')->count();
        $returnRequests = Loan::where('status', 'return_requested')->count();
        $overdueLoans = Loan::where('status', 'approved')->whereDate('due_at', '<', now())->count();

        $latestRequests = Loan::with(['user', 'book'])
            ->whereIn('status', ['pending', 'return_requested'])
            ->latest()
            ->take(5)
            ->get();

        $lowStockBooks = Book::orderBy('available_stock')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'totalBooks',
            'totalCategories',
            'totalMembers',
            'pendingLoans',
            'activeLoans',
            'returnRequests',
            'overdueLoans',
            'latestRequests',
            'lowStockBooks'
        ));
    }

    public function pendingRequests()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $requests = Loan::with(['user', 'book'])->where('status', 'pending')->get();
        return view('petugas.pengajuan', compact('requests'));
    }

    public function loanHistory()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $loans = Loan::with(['user', 'book'])->where('status', 'approved')->get();
        return view('petugas.riwayat_peminjaman', compact('loans'));
    }

    public function returnHistory()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $returns = Loan::with(['user', 'book'])->where('status', 'returned')->get();
        return view('petugas.riwayat_pengembalian', compact('returns'));
    }

    public function confirmLoan(Request $request, $loanId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $loan = Loan::findOrFail($loanId);
        $action = $request->input('action');

        if ($action === 'approve') {
            if ($loan->status !== 'pending') {
                return back()->with('error', 'Status peminjaman tidak valid.');
            }

            $loan->status = 'approved';
            $loan->borrowed_at = now();
            $loan->due_at = now()->addDays(7);
            $loan->save();

            return back()->with('success', 'Peminjaman disetujui.');
        }

        if ($loan->status === 'pending') {
            $loan->status = 'rejected';
            $loan->save();
            $loan->book->increment('available_stock');
            return back()->with('success', 'Peminjaman ditolak dan stok buku dikembalikan.');
        }

        return back()->with('error', 'Tidak dapat memproses status peminjaman ini.');
    }

    public function returns()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $returns = Loan::with(['user', 'book'])->where('status', 'return_requested')->get();
        return view('petugas.pengembalian', compact('returns'));
    }

    public function confirmReturn(Request $request, $loanId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $loan = Loan::findOrFail($loanId);

        if ($loan->status !== 'return_requested') {
            return back()->with('error', 'Status peminjaman tidak valid.');
        }

        $loan->status = 'returned';
        $loan->returned_at = now();
        $loan->fine = $loan->calculateFine();
        $loan->save();

        $loan->book->increment('available_stock');

        return back()->with('success', 'Pengembalian dikonfirmasi.');
    }

    public function books()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $books = Book::with('category')->get();
        return view('petugas.books', compact('books'));
    }

    public function categories()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $categories = Category::all();
        return view('petugas.categories', compact('categories'));
    }

    public function members()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $members = User::where('role', 'anggota')->get();
        return view('petugas.anggota', compact('members'));
    }

    public function storeCategory(Request $request)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $data = $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($data);
        return back()->with('success', 'Kategori berhasil ditambah.');
    }

    public function updateCategory(Request $request, $categoryId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $category = Category::findOrFail($categoryId);

        $data = $request->validate([
            'name' => 'required|unique:categories,name,' . $categoryId,
            'description' => 'nullable|string',
        ]);

        $category->update($data);
        return back()->with('success', 'Kategori berhasil diupdate.');
    }

    public function deleteCategory($categoryId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $category = Category::findOrFail($categoryId);

        if ($category->books()->count() > 0) {
            return back()->with('error', 'Kategori memiliki buku, tidak dapat dihapus.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function storeBook(Request $request)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|digits:4',
            'category_id' => 'required|exists:categories,id',
            'total_stock' => 'required|integer|min:0',
        ]);

        $data['available_stock'] = $data['total_stock'];
        Book::create($data);

        return back()->with('success', 'Buku berhasil ditambah.');
    }

    public function updateBook(Request $request, $bookId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $book = Book::findOrFail($bookId);

        $data = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'year' => 'nullable|digits:4',
            'category_id' => 'required|exists:categories,id',
            'total_stock' => 'required|integer|min:0',
        ]);

        $oldStock = $book->total_stock;
        $book->update($data);
        $book->available_stock += ($data['total_stock'] - $oldStock);
        $book->save();

        return back()->with('success', 'Buku berhasil diupdate.');
    }

    public function deleteBook($bookId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $book = Book::findOrFail($bookId);

        if ($book->available_stock < $book->total_stock) {
            return back()->with('error', 'Buku sedang dipinjam, tidak dapat dihapus.');
        }

        $book->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }
}

