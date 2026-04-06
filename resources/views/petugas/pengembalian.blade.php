@extends('layouts.app')

@section('title', 'Pengembalian Buku')

@section('content')
    <h4>Pengembalian Buku</h4>
    <table class="table table-bordered">
        <thead>
            <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Dipinjam</th><th>Jatuh Tempo</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($returns as $idx => $loan)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->borrowed_at }}</td>
                <td>{{ $loan->due_at }}</td>
                <td>
                    <form method="POST" action="{{ route('petugas.pengembalian.confirm', $loan->id) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Konfirmasi</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Tidak ada permintaan pengembalian.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection