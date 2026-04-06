@extends('layouts.app')

@section('title', 'Dashboard Kepala Perpustakaan')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h4 class="mb-2">Dashboard Kepala Perpustakaan</h4>
            <p class="text-muted mb-0">Ringkasan lengkap operasional dan statistik perpustakaan. Monitor semua aspek dari koleksi buku, anggota, hingga transaksi peminjaman.</p>
        </div>
        <div class="dashboard-actions d-flex flex-wrap gap-2">
            <a href="{{ route('kepala.transaksi') }}" class="btn btn-outline-primary">Lihat Transaksi</a>
            <a href="{{ route('kepala.users') }}" class="btn btn-outline-info">Kelola Pengguna</a>
        </div>
    </div>

    <!-- Primary Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-primary text-white">
                    <div class="card-title">Total Buku</div>
                    <div class="display-5">{{ $totalBooks }}</div>
                    <div class="text-white-75">Koleksi dalam perpustakaan</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-success text-white">
                    <div class="card-title">Anggota Terdaftar</div>
                    <div class="display-5">{{ $totalMembers }}</div>
                    <div class="text-white-75">Pengguna aktif</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-info text-white">
                    <div class="card-title">Staff Petugas</div>
                    <div class="display-5">{{ $totalPetugas }}</div>
                    <div class="text-white-75">Pengelola sistem</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Kategori Buku</div>
                    <div class="display-5 text-info">{{ $totalCategories }}</div>
                    <div class="text-muted">Jenis koleksi</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Peminjaman Aktif</div>
                    <div class="display-5 text-success">{{ $activeLoans }}</div>
                    <div class="text-muted">Sedang dipinjam</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Permintaan Baru</div>
                    <div class="display-5 text-warning">{{ $pendingLoans }}</div>
                    <div class="text-muted">Menunggu review</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Total Transaksi</div>
                    <div class="display-5">{{ $totalTransactions }}</div>
                    <div class="text-muted">Sepanjang masa</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Rows -->
    <div class="row g-3">
        <div class="col-xl-7">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Transaksi Terbaru</h5>
                    @if($recentTransactions->isEmpty())
                        <p class="text-muted">Belum ada transaksi.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-small align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Anggota</th>
                                        <th>Buku</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $trans)
                                        <tr>
                                            <td>{{ $trans->user->name }}</td>
                                            <td>{{ substr($trans->book->title, 0, 30) }}{{ strlen($trans->book->title) > 30 ? '...' : '' }}</td>
                                            <td>
                                                @php
                                                    $statusColor = match($trans->status) {
                                                        'pending' => 'warning',
                                                        'approved' => 'success',
                                                        'returned' => 'info',
                                                        'return_requested' => 'info',
                                                        'rejected' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $trans->status)) }}</span>
                                            </td>
                                            <td>{{ optional($trans->created_at)->format('d M Y') }}</td>
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
                                        <strong>{{ substr($book->title, 0, 25) }}{{ strlen($book->title) > 25 ? '...' : '' }}</strong>
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
