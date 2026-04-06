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
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td>{{ $loan->borrowed_at }}</td>
                    <td>{{ $loan->due_at }}</td>
                    <td>{{ $loan->returned_at ?: '-' }}</td>
                    <td>Rp {{ number_format($loan->fine, 0, ',', '.') }}</td>
                    <td>
                        @if($loan->status === 'approved')
                            <form method="POST" action="{{ route('anggota.return', $loan->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-success">Kembalikan</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Belum ada riwayat.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection