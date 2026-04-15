@extends('layouts.app')

@section('title', 'Pengajuan Peminjaman')

@section('content')
    <div class="mb-4">
        <h4 class="mb-2">Pengajuan Peminjaman</h4>
        <p class="text-muted mb-0">Saat menolak pengajuan, petugas wajib menuliskan alasan agar anggota bisa melihat keterangannya di riwayat peminjaman.</p>
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
                            <th>Status</th>
                            <th>Tgl Request</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $idx => $loan)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>{{ $loan->created_at->format('d-m-Y') }}</td>
                            <td>
                                <form action="{{ route('petugas.pengajuan.confirm', $loan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $loan->id }}">Tolak</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">Tidak ada pengajuan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($requests as $loan)
        <div class="modal fade" id="rejectModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('petugas.pengajuan.confirm', $loan->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <div class="modal-header">
                            <h5 class="modal-title">Tolak Pengajuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Anggota</label>
                                <input type="text" class="form-control" value="{{ $loan->user->name }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Buku</label>
                                <input type="text" class="form-control" value="{{ $loan->book->title }}" readonly>
                            </div>
                            <div class="mb-0">
                                <label for="rejection_reason_{{ $loan->id }}" class="form-label">Alasan ditolak</label>
                                <textarea
                                    id="rejection_reason_{{ $loan->id }}"
                                    name="rejection_reason"
                                    class="form-control @error('rejection_reason') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Tulis alasan penolakan, misalnya stok sedang tidak tersedia atau akun anggota belum memenuhi syarat."
                                    required
                                >{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
