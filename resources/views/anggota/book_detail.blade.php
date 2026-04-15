@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div class="mb-4">
        <a href="{{ route('anggota.catalog') }}" class="btn btn-outline-secondary">← Kembali ke Daftar Buku</a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <img src="{{ $book->cover_url }}" class="card-img-top" alt="Cover {{ $book->title }}" style="height:420px; object-fit:cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $book->title }}</h5>
                    <p class="text-muted mb-1">oleh {{ $book->author }}</p>
                    <span class="badge bg-info">{{ $book->category->name }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h4 class="mb-3">Sinopsis Buku</h4>
                <p class="text-muted">{{ $book->description ?? 'Belum ada sinopsis tersedia untuk buku ini.' }}</p>

                <hr>

                <div class="row mb-3">
                    <div class="col-sm-6 mb-2">
                        <strong>Penerbit</strong>
                        <p class="mb-0">{{ $book->publisher ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <strong>Tahun Terbit</strong>
                        <p class="mb-0">{{ $book->year ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6 mb-2">
                        <strong>Stok Tersedia</strong>
                        <p class="mb-0">{{ $book->available_stock }} dari {{ $book->total_stock }}</p>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @if($book->available_stock > 0)
                        <form action="{{ route('anggota.borrow', $book->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Pinjam Buku</button>
                        </form>
                    @else
                        <button class="btn btn-secondary" disabled>Tidak Tersedia</button>
                    @endif
                    <a href="{{ route('anggota.catalog') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
