@extends('layouts.app')

@section('title', 'Daftar Buku (Petugas)')

@section('content')
    <h4>Daftar Buku</h4>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">Tambah Buku</button>

    <!-- Modal Tambah Buku -->
    <div class="modal fade" id="addBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('petugas.books.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Buku</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Judul</label>
                            <input name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Penulis</label>
                            <input name="author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Penerbit</label>
                            <input name="publisher" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Tahun</label>
                            <input name="year" class="form-control" maxlength="4">
                        </div>
                        <div class="mb-3">
                            <label>Kategori</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach(App\Models\Category::all() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Total Stok</label>
                            <input name="total_stock" type="number" class="form-control" value="1" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label>Sinopsis</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Cover Buku</label>
                            <input name="cover_image" type="file" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr><th>No</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        @forelse($books as $idx => $book)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->category->name }}</td>
                <td>{{ $book->available_stock }} / {{ $book->total_stock }}</td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editBookModal{{ $book->id }}">Edit</button>
                    <form method="POST" action="{{ route('petugas.books.delete', $book->id) }}" style="display:inline;" onsubmit="return confirm('Hapus buku ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <!-- Modal Edit Buku -->
            <div class="modal fade" id="editBookModal{{ $book->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('petugas.books.update', $book->id) }}" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Buku</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Judul</label>
                                    <input name="title" class="form-control" value="{{ $book->title }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Penulis</label>
                                    <input name="author" class="form-control" value="{{ $book->author }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Penerbit</label>
                                    <input name="publisher" class="form-control" value="{{ $book->publisher }}">
                                </div>
                                <div class="mb-3">
                                    <label>Tahun</label>
                                    <input name="year" class="form-control" value="{{ $book->year }}" maxlength="4">
                                </div>
                                <div class="mb-3">
                                    <label>Kategori</label>
                                    <select name="category_id" class="form-select" required>
                                        @foreach(App\Models\Category::all() as $cat)
                                            <option value="{{ $cat->id }}" {{ $cat->id == $book->category_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Total Stok</label>
                                    <input name="total_stock" type="number" class="form-control" value="{{ $book->total_stock }}" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label>Sinopsis</label>
                                    <textarea name="description" class="form-control" rows="4" required>{{ $book->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label>Cover Buku</label>
                                    <input name="cover_image" type="file" class="form-control" accept="image/*">
                                    @if($book->cover_image)
                                        <img src="{{ asset('book_covers/' . $book->cover_image) }}" alt="Cover {{ $book->title }}" class="img-fluid mt-2" style="max-height:120px;">
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <tr><td colspan="6">Belum ada buku.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection