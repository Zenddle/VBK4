@extends('layouts.student')
@section('title', 'Buku Dipinjam')

@section('body')
<div class="page-head">
    <h1 class="page-title">Buku Dipinjam</h1>
    <p class="page-sub">Durasi pinjaman 7 hari sejak disetujui. Maksimal 3 pinjaman aktif.</p>
</div>

@include('partials.loan-table', ['loans' => $loans])
@endsection
