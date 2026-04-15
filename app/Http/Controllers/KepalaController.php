<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KepalaController extends Controller
{
    protected function authorizeRole(): ?\Illuminate\Http\RedirectResponse
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->role !== 'kepala') {
            return redirect('/dashboard');
        }

        return null;
    }

    public function dashboard()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $totalBooks = Book::count();
        $totalCategories = \App\Models\Category::count();
        $totalMembers = User::where('role', 'anggota')->count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $pendingLoans = Loan::where('status', 'pending')->count();
        $activeLoans = Loan::where('status', 'approved')->count();
        $returnRequests = Loan::where('status', 'return_requested')->count();
        $totalTransactions = Loan::count();
        
        // Get recent transactions
        $recentTransactions = Loan::with(['user', 'book'])
            ->latest()
            ->take(8)
            ->get();
        
        // Get low stock books
        $lowStockBooks = Book::orderBy('available_stock')
            ->take(5)
            ->get();

        return view('kepala.dashboard', compact(
            'totalBooks',
            'totalCategories',
            'totalMembers',
            'totalPetugas',
            'pendingLoans',
            'activeLoans',
            'returnRequests',
            'totalTransactions',
            'recentTransactions',
            'lowStockBooks'
        ));
    }

    public function transaksi()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        // Menampilkan riwayat peminjaman anggota (seluruh status)
        $loans = Loan::with(['user', 'book'])
            ->orderBy('borrowed_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepala.transaksi', compact('loans'));
    }

    public function books()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $books = Book::with('category')->get();

        return view('kepala.books', compact('books'));
    }

    public function users()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $users = User::all();

        return view('kepala.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return redirect()->route('kepala.users')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function laporan()
    {
        $deny = $this->authorizeRole();
        if ($deny) return $deny;

        $totalBooks = Book::count();
        $totalUsers = User::where('role', 'anggota')->count();
        $totalLoans = Loan::count();
        $activeLoans = Loan::whereIn('status', ['pending', 'approved', 'return_requested'])->count();
        $returnedLoans = Loan::where('status', 'returned')->count();
        $rejectedLoans = Loan::where('status', 'rejected')->count();
        
        // Calculate total fines
        $totalFines = Loan::where('status', 'returned')
            ->sum('fine');
        
        // Get overdue loans (approved loans that passed due_at)
        $overdueLoans = Loan::where('status', 'approved')
            ->whereDate('due_at', '<', now())
            ->count();
        
        // Get detailed loans with fine information
        $loans = Loan::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($loan) {
                if ($loan->status === 'returned' && !$loan->fine) {
                    $loan->fine = $loan->calculateFine();
                }
                return $loan;
            });
        
        // Statistics by status
        $loansByStatus = Loan::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Top users by loans
        $topUsers = User::where('role', 'anggota')
            ->withCount('loans')
            ->orderByDesc('loans_count')
            ->take(5)
            ->get();

        return view('kepala.laporan', compact(
            'totalBooks',
            'totalUsers',
            'totalLoans',
            'activeLoans',
            'returnedLoans',
            'rejectedLoans',
            'totalFines',
            'overdueLoans',
            'loans',
            'loansByStatus',
            'topUsers'
        ));
    }
}
