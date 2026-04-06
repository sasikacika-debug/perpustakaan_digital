@extends('layouts.app')

@section('title', 'Profile Kepala Perpustakaan')

@section('content')
    <div class="mb-4">
        <h4 class="mb-2">Profile Saya</h4>
        <p class="text-muted">Informasi akun Anda sebagai kepala perpustakaan.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pribadi</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Nama Lengkap</p>
                            <p class="fw-medium">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="fw-medium">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Status</p>
                            <p class="fw-medium"><span class="badge bg-danger">{{ ucfirst(auth()->user()->role) }}</span></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Tanggal Terdaftar</p>
                            <p class="fw-medium">{{ auth()->user()->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Admin ID</p>
                            <p class="fw-medium">#{{ str_pad(auth()->user()->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ringkasan Perpustakaan</h5>
                    <div class="d-grid gap-3">
                        <div class="border-bottom pb-3">
                            <div class="text-muted small mb-1">Total Buku</div>
                            <div class="display-6 text-primary">{{ \App\Models\Book::count() }}</div>
                        </div>
                        <div class="border-bottom pb-3">
                            <div class="text-muted small mb-1">Total Anggota</div>
                            <div class="display-6 text-success">{{ \App\Models\User::where('role', 'anggota')->count() }}</div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('kepala.dashboard') }}" class="btn btn-primary btn-sm w-100">Ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
