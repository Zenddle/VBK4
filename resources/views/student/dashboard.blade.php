@extends('layouts.student')
@section('title', 'Dasbor')

@section('body')
<div class="page-head">
    <h1 class="page-title">Halo, {{ auth()->user()->name }}</h1>
    <p class="page-sub">Selamat datang di perpustakaan</p>
</div>

<div class="mb-10 grid gap-4 sm:grid-cols-3">
    <div class="panel p-5">
        <p class="text-sm text-fog-400">Pinjaman Aktif</p>
        <p class="mt-2 font-display text-4xl font-semibold tabular-nums text-fog-100">{{ $active }}<span class="ml-1.5 text-lg font-normal text-fog-500">/ {{ $limit }}</span></p>
        <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-ink-700" role="progressbar" aria-valuemin="0" aria-valuemax="{{ $limit }}" aria-valuenow="{{ $active }}">
            <div class="h-full rounded-full bg-lamp-400" style="width: {{ min(100, round($active / max($limit, 1) * 100)) }}%"></div>
        </div>
    </div>
    <div class="panel p-5">
        <p class="text-sm text-fog-400">Terlambat</p>
        <p class="mt-2 font-display text-4xl font-semibold tabular-nums {{ $overdue ? 'text-rose-300' : 'text-fog-100' }}">{{ $overdue }}</p>
    </div>
    <a href="{{ route('books.index') }}"
       class="btn flex items-center justify-between rounded-xl bg-lamp-400 p-5 text-base font-semibold text-ink-950 hover:bg-lamp-300">
        Jelajahi Buku
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
        </svg>
    </a>
</div>

<h2 class="section-title mb-4">Peminjaman Terbaru</h2>
@include('partials.loan-table', ['loans' => $loans])
@endsection
