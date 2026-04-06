@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <h4>Riwayat Peminjaman</h4>
    <table class="table table-bordered">
        <thead>
            <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Dipinjam</th><th>Jatuh Tempo</th><th>Status</th></tr>
        </thead>
        <tbody>
        @forelse($loans as $idx => $loan)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->borrowed_at }}</td>
                <td>{{ $loan->due_at }}</td>
                <td>Dipinjaman</td>
            </tr>
        @empty
            <tr><td colspan="6">Tidak ada riwayat peminjaman.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection