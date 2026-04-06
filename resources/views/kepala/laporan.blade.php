@extends('layouts.app')

@section('title', 'Laporan - Kepala Perpustakaan')

@section('content')
    <div class="mb-4">
        <h4 class="mb-2">Laporan Perpustakaan</h4>
        <p class="text-muted">Ringkasan lengkap statistik, transaksi, dan denda perpustakaan untuk periode berjalan.</p>
    </div>

    <!-- Primary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-primary text-white">
                    <div class="card-title">Total Koleksi Buku</div>
                    <div class="display-5">{{ $totalBooks }}</div>
                    <div class="text-white-75">Buku dalam perpustakaan</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-success text-white">
                    <div class="card-title">Total Anggota</div>
                    <div class="display-5">{{ $totalUsers }}</div>
                    <div class="text-white-75">Pengguna terdaftar</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card dashboard-card border-0 overflow-hidden">
                <div class="card-body bg-danger text-white">
                    <div class="card-title">Total Denda</div>
                    <div class="display-5">Rp{{ number_format($totalFines, 0, ',', '.') }}</div>
                    <div class="text-white-75">Denda yang tersimpan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Total Transaksi</div>
                    <div class="display-5 text-info">{{ $totalLoans }}</div>
                    <div class="text-muted">Peminjaman keseluruhan</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Aktif</div>
                    <div class="display-5 text-warning">{{ $activeLoans }}</div>
                    <div class="text-muted">Pending, approved, return</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Dikembalikan</div>
                    <div class="display-5 text-success">{{ $returnedLoans }}</div>
                    <div class="text-muted">Selesai</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="card-title">Overdue</div>
                    <div class="display-5 text-danger">{{ $overdueLoans }}</div>
                    <div class="text-muted">Melewati batas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Loan Table -->
    <div class="card dashboard-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Detail Transaksi Peminjaman</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Status</th>
                            <th>Dipinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Dikembalikan</th>
                            <th>Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td>{{ $loan->user->name }}</td>
                                <td>{{ substr($loan->book->title, 0, 30) }}{{ strlen($loan->book->title) > 30 ? '...' : '' }}</td>
                                <td>
                                    @php
                                        $color = match($loan->status) {
                                            'pending' => 'warning',
                                            'approved' => 'success',
                                            'returned' => 'info',
                                            'return_requested' => 'secondary',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst(str_replace('_', ' ', $loan->status)) }}</span>
                                </td>
                                <td>{{ optional($loan->borrowed_at)->format('d M Y') ?: '-' }}</td>
                                <td>{{ optional($loan->due_at)->format('d M Y') ?: '-' }}</td>
                                <td>{{ optional($loan->returned_at)->format('d M Y') ?: '-' }}</td>
                                <td>
                                    @if($loan->fine > 0)
                                        <span class="badge bg-danger">Rp{{ number_format($loan->fine, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada transaksi peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Users -->
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Anggota Paling Aktif</h5>
                    @if($topUsers->isEmpty())
                        <p class="text-muted">Belum ada riwayat peminjaman.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($topUsers as $idx => $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <div>
                                        <span class="badge bg-secondary rounded-circle me-2">{{ $idx + 1 }}</span>
                                        {{ $user->name }}
                                    </div>
                                    <span class="badge bg-primary">{{ $user->loans_count }} peminjaman</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card dashboard-card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ringkasan Status Transaksi</h5>
                    <div class="d-grid gap-2">
                        @php
                            $statuses = [
                                'pending' => ['label' => 'Menunggu', 'color' => 'warning'],
                                'approved' => ['label' => 'Disetujui', 'color' => 'success'],
                                'return_requested' => ['label' => 'Pengembalian Diminta', 'color' => 'info'],
                                'returned' => ['label' => 'Dikembalikan', 'color' => 'secondary'],
                                'rejected' => ['label' => 'Ditolak', 'color' => 'danger'],
                            ];
                        @endphp
                        @foreach($statuses as $status => $detail)
                            @if(isset($loansByStatus[$status]))
                                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                    <span>{{ $detail['label'] }}</span>
                                    <span class="badge bg-{{ $detail['color'] }}">{{ $loansByStatus[$status] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
