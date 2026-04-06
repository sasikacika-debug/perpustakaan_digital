@extends('layouts.app')

@section('title', 'Profile Anggota')

@section('content')
    <div class="mb-4">
        <h4 class="mb-2">Profile Saya</h4>
        <p class="text-muted">Informasi akun dan data pribadi Anda sebagai anggota perpustakaan.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informasi Pribadi</h5>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Nama Lengkap</p>
                            <p class="fw-medium">{{ $user->name }}</p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="fw-medium">{{ $user->email }}</p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Status</p>
                            <p class="fw-medium"><span class="badge bg-success">{{ ucfirst($user->role) }}</span></p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Tanggal Terdaftar</p>
                            <p class="fw-medium">{{ $user->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="text-muted small mb-1">Member ID</p>
                            <p class="fw-medium">#{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ringkasan Aktivitas</h5>
                    <div class="d-grid gap-3">
                        <div class="border-bottom pb-3">
                            <div class="text-muted small mb-1">Total Peminjaman</div>
                            <div class="display-6">{{ \App\Models\Loan::where('user_id', $user->id)->count() }}</div>
                        </div>
                        <div class="border-bottom pb-3">
                            <div class="text-muted small mb-1">Buku Dipinjam Saat Ini</div>
                            <div class="display-6">{{ \App\Models\Loan::where('user_id', $user->id)->where('status', 'approved')->count() }}</div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('anggota.history') }}" class="btn btn-primary btn-sm w-100">Lihat Riwayat Lengkap</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
