@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="mb-4">
        <h4 class="mb-2">Riwayat Peminjaman</h4>
        <p class="text-muted mb-0">Daftar transaksi buku yang sedang atau pernah dipinjam anggota.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Dipinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($loans as $idx => $loan)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td>{{ optional($loan->borrowed_at)->format('d/m/Y') ?: '-' }}</td>
                            <td>{{ optional($loan->due_at)->format('d/m/Y') ?: '-' }}</td>
                            <td><span class="badge bg-primary">Dipinjam</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Tidak ada riwayat peminjaman.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
