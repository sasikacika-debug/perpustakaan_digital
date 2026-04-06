@extends('layouts.app')

@section('title', 'Daftar Pengguna - Kepala Perpustakaan')

@section('content')
    <h4>Daftar Pengguna</h4>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addPetugasModal">Tambah Petugas</button>

    <!-- Modal Tambah Petugas -->
    <div class="modal fade" id="addPetugasModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('kepala.users.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Petugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input name="email" type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input name="password" type="password" class="form-control" required>
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

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role == 'anggota')
                                    <span class="badge bg-info">Anggota</span>
                                @elseif($user->role == 'petugas')
                                    <span class="badge bg-primary">Petugas</span>
                                @elseif($user->role == 'kepala')
                                    <span class="badge bg-success">Kepala</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection