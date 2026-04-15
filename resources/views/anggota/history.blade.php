@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <h4>Riwayat Peminjaman</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Pinjam</th>
                <th>Tenggat</th>
                <th>Kembali</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($history as $index => $loan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $loan->book->title }}</td>
                    <td>
                        @php
                            $statusMap = [
                                'pending' => ['label' => 'Menunggu', 'color' => 'warning'],
                                'approved' => ['label' => 'Disetujui', 'color' => 'success'],
                                'rejected' => ['label' => 'Ditolak', 'color' => 'danger'],
                                'return_requested' => ['label' => 'Menunggu Pengembalian', 'color' => 'info'],
                                'returned' => ['label' => 'Dikembalikan', 'color' => 'secondary'],
                            ];
                            $status = $statusMap[$loan->status] ?? ['label' => ucfirst(str_replace('_', ' ', $loan->status)), 'color' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $status['color'] }}">{{ $status['label'] }}</span>
                    </td>
                    <td>
                        @if($loan->status === 'rejected' && $loan->rejection_reason)
                            <div class="small text-danger fw-semibold mb-1">Alasan penolakan</div>
                            <div>{{ $loan->rejection_reason }}</div>
                        @elseif($loan->status === 'pending')
                            <span class="text-muted">Menunggu persetujuan petugas</span>
                        @elseif($loan->status === 'approved')
                            <span class="text-muted">Pengajuan disetujui, silakan pinjam sesuai jadwal.</span>
                        @elseif($loan->status === 'return_requested')
                            <span class="text-muted">Pengembalian sedang menunggu konfirmasi petugas.</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $loan->borrowed_at }}</td>
                    <td>{{ $loan->due_at }}</td>
                    <td>{{ $loan->returned_at ?: '-' }}</td>
                    <td>
                        @if($loan->fine > 0)
                            Rp {{ number_format($loan->fine, 0, ',', '.') }}
                            @if($loan->fine_status === 'unpaid')
                                <span class="badge bg-danger">Belum Bayar</span>
                            @elseif($loan->fine_status === 'paid')
                                <span class="badge bg-warning">Menunggu Konfirmasi</span>
                            @elseif($loan->fine_status === 'confirmed')
                                <span class="badge bg-success">Sudah Bayar</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($loan->status === 'approved')
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#returnModal{{ $loan->id }}">Kembalikan</button>
                        @elseif($loan->status === 'return_requested')
                            <span class="text-muted">Menunggu konfirmasi petugas</span>
                        @elseif($loan->status === 'returned' && $loan->fine > 0 && $loan->fine_status === 'unpaid')
                            <span class="text-muted">Bayar di petugas</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">Belum ada riwayat.</td></tr>
            @endforelse
        </tbody>
    </table>

    @foreach($history as $loan)
        @if($loan->status === 'approved')
            <!-- Modal -->
            <div class="modal fade" id="returnModal{{ $loan->id }}" tabindex="-1" aria-labelledby="returnModalLabel{{ $loan->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="returnModalLabel{{ $loan->id }}">Form Pengembalian Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('anggota.submit_return', $loan->id) }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="book_title{{ $loan->id }}" class="form-label">Judul Buku</label>
                                    <input type="text" class="form-control" id="book_title{{ $loan->id }}" value="{{ $loan->book->title }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="borrowed_at{{ $loan->id }}" class="form-label">Tanggal Pinjam</label>
                                    <input type="date" class="form-control" id="borrowed_at{{ $loan->id }}" value="{{ $loan->borrowed_at->format('Y-m-d') }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="requested_return_date{{ $loan->id }}" class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" id="requested_return_date{{ $loan->id }}" name="requested_return_date" required>
                                </div>
                                <div class="mb-3">
                                    <label for="condition{{ $loan->id }}" class="form-label">Kondisi Buku</label>
                                    <select class="form-select" id="condition{{ $loan->id }}" name="condition" required>
                                        <option value="">Pilih Kondisi</option>
                                        <option value="baik">Baik</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Denda (Otomatis)</label>
                                    <input type="text" class="form-control" value="Akan dihitung otomatis" readonly>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Ajukan Pengembalian</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
