<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'E-Perpus')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: linear-gradient(180deg, #1e293b, #0f172a); color: white; }
        .sidebar h4 { padding: 24px 20px 12px; margin: 0; font-size: 1.4rem; letter-spacing: 0.02em; }
        .sidebar p { padding: 0 20px 15px; margin: 0; color: #94a3b8; font-size: 0.9rem; }
        .sidebar a { display: block; padding: 12px 22px; color: #cbd5f5; text-decoration: none; font-size: 0.95rem; }
        .sidebar a:hover, .sidebar .active { background-color: #334155; color: white; }
        .sidebar .footer-link { margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.08); }
        .main { margin-left: 260px; }
        .topbar { min-height: 70px; background: white; display: flex; align-items: center; justify-content: space-between; padding: 0 24px; border-bottom: 1px solid #e2e8f0; }
        .dashboard-card { border-radius: 1rem; box-shadow: 0 15px 30px rgba(15, 23, 42, 0.08); }
        .dashboard-card .card-body { min-height: 130px; }
        .dashboard-card .card-title { font-size: 0.95rem; letter-spacing: 0.03em; text-transform: uppercase; opacity: 0.8; }
        .dashboard-card .display-5 { font-size: 2.3rem; font-weight: 700; }
        .dashboard-actions .btn { min-width: 140px; }
        .table-small th, .table-small td { padding: 0.75rem 0.8rem; }
    </style>
</head>
<body>
<div class="sidebar">
    <h4>E-Perpus</h4>

    @php
        $role = auth()->check() ? auth()->user()->role : 'guest';
        $menu = [];

        if ($role === 'anggota') {
            $menu = [
                ['label' => 'Dashboard', 'url' => route('anggota.dashboard')],
                ['label' => 'Riwayat Peminjaman', 'url' => route('anggota.history')],
                ['label' => 'Daftar Buku', 'url' => route('anggota.catalog')],
                ['label' => 'Profile', 'url' => route('anggota.profile')],
            ];
        } elseif ($role === 'petugas') {
            $menu = [
                ['label' => 'Dashboard', 'url' => route('petugas.dashboard')],
                ['label' => 'Peminjaman', 'url' => route('petugas.pengajuan')],
                ['label' => 'Pengembalian', 'url' => route('petugas.pengembalian')],
                ['label' => 'Riwayat Peminjaman', 'url' => route('petugas.riwayat_peminjaman')],
                ['label' => 'Riwayat Pengembalian', 'url' => route('petugas.riwayat_pengembalian')],
                ['label' => 'Daftar Buku', 'url' => route('petugas.books')],
                ['label' => 'Kategori', 'url' => route('petugas.categories')],
                ['label' => 'Anggota', 'url' => route('petugas.anggota')],
                ['label' => 'Profile', 'url' => route('petugas.profile')],
            ];
        } elseif ($role === 'kepala') {
            $menu = [
                ['label' => 'Dashboard', 'url' => route('kepala.dashboard')],
                ['label' => 'Transaksi', 'url' => route('kepala.transaksi')],
                ['label' => 'Daftar Buku', 'url' => route('kepala.books')],
                ['label' => 'Daftar Pengguna', 'url' => route('kepala.users')],
                ['label' => 'Laporan', 'url' => route('kepala.laporan')],
                ['label' => 'Profile', 'url' => route('kepala.profile')],
            ];
        }
    @endphp

    @foreach($menu as $item)
        <a href="{{ $item['url'] }}" class="{{ request()->url() === $item['url'] ? 'active' : '' }}">{{ $item['label'] }}</a>
    @endforeach
</div>

<div class="main">
    <div class="topbar">
        <div>@yield('title', 'Dashboard')</div>
        <div>
            @if(auth()->check())
                <span>Halo, {{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary ms-2">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a> |
                <a href="{{ route('register') }}">Daftar</a>
            @endif
        </div>
    </div>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>