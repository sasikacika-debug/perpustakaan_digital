@extends('layouts.app')

@section('title', 'Riwayat Pengembalian')

@section('content')
    <h4>Riwayat Pengembalian</h4>
    <table class="table table-bordered">
        <thead>
            <tr><th>No</th><th>Anggota</th><th>Buku</th><th>Dipinjam</th><th>Dikembalikan</th><th>Denda</th><th>Status Denda</th><th>Aksi</th></tr>
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
                <td>
                    @if($loan->fine > 0)
                        @if($loan->fine_status === 'unpaid')
                            <span class="badge bg-danger">Belum Bayar</span>
                        @elseif($loan->fine_status === 'paid')
                            <span class="badge bg-warning">Menunggu Konfirmasi</span>
                        @elseif($loan->fine_status === 'confirmed')
                            <span class="badge bg-success">Sudah Bayar</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($loan->fine > 0)
                        <a href="{{ route('petugas.fine.receipt', $loan->id) }}" class="btn btn-sm btn-secondary">Cetak Struk</a>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="8">Tidak ada riwayat pengembalian.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection
