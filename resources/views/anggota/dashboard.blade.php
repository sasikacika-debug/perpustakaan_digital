@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@section('content')
    <div class="alert alert-success">Selamat datang di sistem perpustakaan digital</div>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 bg-primary text-white">
                <h5>Total Buku</h5>
                <h3>{{ $totalBooks }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-success text-white">
                <h5>Buku Dipinjam</h5>
                <h3>{{ $borrowedBooks }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-warning text-white">
                <h5>Denda</h5>
                <h3>Rp {{ number_format($denda, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h5>Buku Terbaru</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($latest as $index => $book)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>
                        @if($book->available_stock > 0)
                            <span class="badge bg-success">Tersedia</span>
                        @else
                            <span class="badge bg-danger">Habis</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
