@extends('layouts.app')

@section('title', 'Data Anggota')

@section('content')
    <h4>Data Anggota</h4>
    <table class="table table-bordered">
        <thead><tr><th>No</th><th>Nama</th><th>Email</th><th>Terdaftar</th></tr></thead>
        <tbody>
        @forelse($members as $idx => $member)
            <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $member->name }}</td>
                <td>{{ $member->email }}</td>
                <td>{{ $member->created_at->format('d-m-Y') }}</td>
            </tr>
        @empty
            <tr><td colspan="4">Belum ada anggota.</td></tr>
        @endforelse
        </tbody>
    </table>
@endsection