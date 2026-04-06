@extends('layouts.app')

@section('title', 'Transaksi - Kepala Perpustakaan')

@section('content')
    <h4>Riwayat Peminjaman Anggota (Transaksi)</h4>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                        <tr>
                            <td>{{ $loan->id }}</td>
                            <td>{{ $loan->user->name }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td>{{ $loan->borrowed_at ? \Illuminate\Support\Carbon::parse($loan->borrowed_at)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $loan->returned_at ? \Illuminate\Support\Carbon::parse($loan->returned_at)->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($loan->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($loan->status == 'borrowed')
                                    <span class="badge bg-primary">Dipinjam</span>
                                @elseif($loan->status == 'returned')
                                    <span class="badge bg-success">Dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection