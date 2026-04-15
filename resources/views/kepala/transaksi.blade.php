@extends('layouts.app')

@section('title', 'Transaksi - Kepala Perpustakaan')

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h4 class="mb-2">Riwayat Transaksi Perpustakaan</h4>
            <p class="text-muted mb-0">Seluruh transaksi peminjaman dan pengembalian anggota ditampilkan di halaman ini.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td>{{ $loan->id }}</td>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ $loan->book->title }}</td>
                                <td>{{ optional($loan->borrowed_at)->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ optional($loan->due_at)->format('d/m/Y') ?: '-' }}</td>
                                <td>{{ optional($loan->returned_at)->format('d/m/Y') ?: '-' }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'pending' => ['label' => 'Menunggu', 'color' => 'warning'],
                                            'approved' => ['label' => 'Dipinjam', 'color' => 'primary'],
                                            'return_requested' => ['label' => 'Menunggu Pengembalian', 'color' => 'info'],
                                            'returned' => ['label' => 'Dikembalikan', 'color' => 'success'],
                                            'rejected' => ['label' => 'Ditolak', 'color' => 'danger'],
                                        ];
                                        $status = $statusMap[$loan->status] ?? ['label' => ucfirst(str_replace('_', ' ', $loan->status)), 'color' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $status['color'] }}">{{ $status['label'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
