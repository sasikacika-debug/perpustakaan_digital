@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')
    <h4>Riwayat Pengembalian</h4>
    <table class="table table-bordered">
        <thead>
            <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Dipinjam</th><th>Dikembalikan</th><th>Denda</th></tr>
        </thead>
        <tbody>
        @forelse($returns as $idx => $loan)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->borrowed_at }}</td>
                <td>{{ $loan->returned_at }}</td>
                <td>Rp {{ number_format($loan->fine, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="6">Tidak ada riwayat pengembalian.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection