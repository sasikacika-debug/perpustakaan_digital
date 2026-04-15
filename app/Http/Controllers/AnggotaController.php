<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnggotaController extends Controller
{
    protected function authorizeRole(): ?\Illuminate\Http\RedirectResponse
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role !== 'anggota') {
            return redirect('/dashboard');
        }

        return null;
    }

    public function dashboard()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $totalBooks = Book::count();
        $borrowedBooks = Loan::where('user_id', Auth::id())->whereIn('status', ['pending', 'approved'])->count();
        $denda = Loan::where('user_id', Auth::id())
            ->where('status', 'returned')
            ->sum('fine');

        $latest = Book::latest()->limit(5)->get();

        return view('anggota.dashboard', compact('totalBooks', 'borrowedBooks', 'denda', 'latest'));
    }

    public function catalog()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $query = Book::with('category');
        $categories = \App\Models\Category::all();
        
        // Search by title or author
        if ($search = request('search')) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
        }
        
        // Filter by category
        if ($categoryId = request('category')) {
            $query->where('category_id', $categoryId);
        }
        
        $books = $query->paginate(15);
        
        return view('anggota.catalog', compact('books', 'categories'));
    }

    public function bookDetail(Book $book)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $book->load('category');

        return view('anggota.book_detail', compact('book'));
    }

    public function profile()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        return view('anggota.profile', ['user' => Auth::user()]);
    }

    public function history()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $history = Loan::with('book')->where('user_id', Auth::id())->latest()->get();
        return view('anggota.history', compact('history'));
    }

    public function borrow(Request $request, $bookId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $book = Book::findOrFail($bookId);

        if ($book->available_stock < 1) {
            return back()->with('error', 'Buku tidak tersedia.');
        }

        DB::transaction(function () use ($book) {
            $updated = Book::where('id', $book->id)
                ->where('available_stock', '>', 0)
                ->decrement('available_stock');

            if (!$updated) {
                throw new \Exception('Stok buku tidak tersedia.');
            }

            Loan::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'status' => 'pending',
                'borrowed_at' => now(),
                'due_at' => now()->addDays(3),
            ]);
        });

        return redirect()->route('anggota.catalog')->with('success', 'Permintaan peminjaman dikirim, tunggu persetujuan petugas.');
    }

    public function return($loanId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $loan = Loan::where('id', $loanId)->where('user_id', Auth::id())->firstOrFail();

        if ($loan->status !== 'approved') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        $loan->status = 'return_requested';
        $loan->save();

        return redirect()->route('anggota.history')->with('success', 'Permintaan pengembalian dikirim, tunggu konfirmasi petugas.');
    }

    public function submitReturn(Request $request, $loanId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $loan = Loan::where('id', $loanId)->where('user_id', Auth::id())->firstOrFail();

        if ($loan->status !== 'approved') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        $request->validate([
            'requested_return_date' => 'required|date|after_or_equal:' . $loan->borrowed_at,
            'condition' => 'required|in:baik,rusak,hilang',
        ]);

        $loan->requested_return_date = $request->requested_return_date;
        $loan->condition = $request->condition;
        $loan->fine = $loan->calculateFine();
        $loan->status = 'return_requested';
        $loan->save();

        return redirect()->route('anggota.history')->with('success', 'Form pengembalian dikirim, tunggu konfirmasi petugas.');
    }

    public function payFine($loanId)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $loan = Loan::where('id', $loanId)->where('user_id', Auth::id())->firstOrFail();

        if ($loan->status !== 'returned' || $loan->fine <= 0 || $loan->fine_status !== 'unpaid') {
            return back()->with('error', 'Tidak dapat memproses pembayaran denda.');
        }

        $loan->fine_status = 'paid';
        $loan->save();

        return redirect()->route('anggota.history')->with('success', 'Permintaan pembayaran denda dikirim, tunggu konfirmasi petugas.');
    }
}
