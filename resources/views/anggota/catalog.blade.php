@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4 gap-3">
        <div>
            <h4 class="mb-2">Daftar Buku</h4>
            <p class="text-muted mb-0">Temukan dan pinjam buku dari koleksi perpustakaan kami. Gunakan pencarian atau filter kategori untuk menemukan buku yang Anda cari.</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('anggota.catalog') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cari Judul atau Penulis</label>
                    <input type="text" name="search" class="form-control" placeholder="Ketik judul atau nama penulis..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">Cari</button>
                    <a href="{{ route('anggota.catalog') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row g-3 mb-4">
        @forelse($books as $book)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm border-0 overflow-hidden">
                    <img src="{{ $book->cover_url }}" class="card-img-top" alt="Cover {{ $book->title }}" style="height:220px; object-fit:cover;">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-2 flex-grow-0">{{ $book->title }}</h6>
                        <p class="text-muted small mb-2">oleh {{ $book->author }}</p>
                        <div class="mb-3">
                            <span class="badge bg-info">{{ $book->category->name }}</span>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Penerbit: {{ $book->publisher ?? '-' }}</small><br>
                            <small class="text-muted">Tahun: {{ $book->year ?? '-' }}</small>
                        </div>
                        <div class="mt-auto">
                            <a href="{{ route('anggota.book.detail', $book->id) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">Detail Buku</a>
                            <div class="mb-3">
                                @if($book->available_stock > 0)
                                    <div class="text-success fw-bold">Tersedia: {{ $book->available_stock }}</div>
                                @else
                                    <div class="text-danger fw-bold">Tidak Tersedia</div>
                                @endif
                            </div>
                            @if($book->available_stock > 0)
                                <form action="{{ route('anggota.borrow', $book->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Pinjam</button>
                                </form>
                            @else
                                <button class="btn btn-secondary btn-sm w-100" disabled>Kosong</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Tidak ada buku yang sesuai dengan pencarian Anda.</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="d-flex justify-content-center">
            {{ $books->links() }}
        </div>
    @endif
@endsection
