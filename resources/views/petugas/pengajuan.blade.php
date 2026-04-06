@extends('layouts.app')

@section('title', 'Pengajuan Peminjaman')

@section('content')
    <h4>Pengajuan Peminjaman</h4>
    <table class="table table-bordered">
        <thead>
            <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Status</th><th>Tgl Request</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($requests as $idx => $loan)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ ucfirst($loan->status) }}</td>
                <td>{{ $loan->created_at->format('d-m-Y') }}</td>
                <td>
                    <form action="{{ route('petugas.pengajuan.confirm', $loan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button class="btn btn-sm btn-success">Setujui</button>
                    </form>
                    <form action="{{ route('petugas.pengajuan.confirm', $loan->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button class="btn btn-sm btn-danger">Tolak</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Tidak ada pengajuan.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection