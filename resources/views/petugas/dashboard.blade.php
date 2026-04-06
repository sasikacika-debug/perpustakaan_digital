@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h4 class="mb-2">Dashboard Petugas</h4>
            <p class="text-muted mb-0">Ringkasan operasional perpustakaan hari ini. Kelola peminjaman, pengembalian, buku, dan anggota dari satu halaman cepat.</p>
        </div>
        <div class="dashboard-actions d-flex flex-wrap gap-2">
            <a href="{{ route('petugas.pengajuan') }}" class="btn btn-outline-primary">Lihat Peminjaman</a>
            <a href="{{ route('petugas.pengembalian') }}" class="btn btn-outline-success">Proses Pengembalian</a>
            <a href="{{ route('petugas.books') }}" class="btn btn-outline-secondary">Daftar Buku</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-primary text-white">
                    <div class="card-title">Total Buku</div>
                    <div class="display-5">{{ $totalBooks }}</div>
                    <div class="text-white-75">Semua koleksi buku tersedia</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-success text-white">
                    <div class="card-title">Anggota Terdaftar</div>
                    <div class="display-5">{{ $totalMembers }}</div>
                    <div class="text-white-75">Jumlah anggota yang dapat meminjam buku</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-info text-white">
                    <div class="card-title">Kategori</div>
                    <div class="display-5">{{ $totalCategories }}</div>
                    <div class="text-white-75">Jenis buku yang tersedia</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Pengajuan Baru</div>
                    <div class="display-5 text-warning">{{ $pendingLoans }}</div>
                    <div class="text-muted">Menunggu konfirmasi</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Peminjaman Aktif</div>
                    <div class="display-5 text-info">{{ $activeLoans }}</div>
                    <div class="text-muted">Sedang dipinjam</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Pengembalian</div>
                    <div class="display-5 text-success">{{ $returnRequests }}</div>
                    <div class="text-muted">Permintaan pengembalian</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Overdue</div>
                    <div class="display-5 text-danger">{{ $overdueLoans }}</div>
                    <div class="text-muted">Telat dikembalikan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Permintaan Terbaru</h5>
                    @if($latestRequests->isEmpty())
                        <p class="text-muted">Belum ada permintaan peminjaman atau pengembalian terbaru.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-small align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Buku</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($latestRequests as $loan)
                                        <tr>
                                            <td>{{ $loan->user->name }}</td>
                                            <td>{{ $loan->book->title }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $loan->status)) }}</td>
                                            <td>{{ optional($loan->created_at)->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Buku Stok Rendah</h5>
                    @if($lowStockBooks->isEmpty())
                        <p class="text-muted">Semua buku memiliki stok cukup.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($lowStockBooks as $book)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <div>
                                        <strong>{{ $book->title }}</strong>
                                        <div class="text-muted small">{{ $book->author }}</div>
                                    </div>
                                    <span class="badge bg-danger rounded-pill">{{ $book->available_stock }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection