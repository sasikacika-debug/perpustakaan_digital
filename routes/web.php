<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\KepalaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    if (!auth()->check()) return redirect('/login');

    $role = auth()->user()->role;

    return match ($role) {
        'petugas' => redirect()->route('petugas.dashboard'),
        'kepala' => redirect()->route('kepala.dashboard'),
        default => redirect()->route('anggota.dashboard'),
    };
});

// Anggota
Route::get('/anggota/dashboard', [AnggotaController::class, 'dashboard'])->name('anggota.dashboard');
Route::get('/anggota/riwayat', [AnggotaController::class, 'history'])->name('anggota.history');
Route::get('/anggota/buku', [AnggotaController::class, 'catalog'])->name('anggota.catalog');
Route::get('/anggota/profile', [AnggotaController::class, 'profile'])->name('anggota.profile');
Route::post('/anggota/borrow/{book}', [AnggotaController::class, 'borrow'])->name('anggota.borrow');
Route::post('/anggota/return/{loan}', [AnggotaController::class, 'return'])->name('anggota.return');

// Petugas
Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
Route::get('/petugas/pengajuan', [PetugasController::class, 'pendingRequests'])->name('petugas.pengajuan');
Route::post('/petugas/pengajuan/{loan}/confirm', [PetugasController::class, 'confirmLoan'])->name('petugas.pengajuan.confirm');
Route::get('/petugas/riwayat-peminjaman', [PetugasController::class, 'loanHistory'])->name('petugas.riwayat_peminjaman');
Route::get('/petugas/pengembalian', [PetugasController::class, 'returns'])->name('petugas.pengembalian');
Route::post('/petugas/pengembalian/{loan}/confirm', [PetugasController::class, 'confirmReturn'])->name('petugas.pengembalian.confirm');
Route::get('/petugas/riwayat-pengembalian', [PetugasController::class, 'returnHistory'])->name('petugas.riwayat_pengembalian');
Route::get('/petugas/books', [PetugasController::class, 'books'])->name('petugas.books');
Route::post('/petugas/books', [PetugasController::class, 'storeBook'])->name('petugas.books.store');
Route::put('/petugas/books/{book}', [PetugasController::class, 'updateBook'])->name('petugas.books.update');
Route::delete('/petugas/books/{book}', [PetugasController::class, 'deleteBook'])->name('petugas.books.delete');
Route::get('/petugas/categories', [PetugasController::class, 'categories'])->name('petugas.categories');
Route::post('/petugas/categories', [PetugasController::class, 'storeCategory'])->name('petugas.categories.store');
Route::put('/petugas/categories/{category}', [PetugasController::class, 'updateCategory'])->name('petugas.categories.update');
Route::delete('/petugas/categories/{category}', [PetugasController::class, 'deleteCategory'])->name('petugas.categories.delete');
Route::get('/petugas/anggota', [PetugasController::class, 'members'])->name('petugas.anggota');
Route::get('/petugas/profile', function () {
    return view('petugas.profile');
})->name('petugas.profile');

// Kepala
Route::get('/kepala/dashboard', [KepalaController::class, 'dashboard'])->name('kepala.dashboard');
Route::get('/kepala/transaksi', [KepalaController::class, 'transaksi'])->name('kepala.transaksi');
Route::get('/kepala/books', [KepalaController::class, 'books'])->name('kepala.books');
Route::get('/kepala/users', [KepalaController::class, 'users'])->name('kepala.users');
Route::post('/kepala/users', [KepalaController::class, 'storeUser'])->name('kepala.users.store');
Route::get('/kepala/laporan', [KepalaController::class, 'laporan'])->name('kepala.laporan');
Route::get('/kepala/profile', function () {
    return view('kepala.profile');
})->name('kepala.profile');

Route::get('/test', function () {
    return 'OK';
});
