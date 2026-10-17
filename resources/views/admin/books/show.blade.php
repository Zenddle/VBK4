@extends('layouts.admin')
@section('title', $book->title)

@section('body')
<div class="panel mb-10 p-6 sm:p-8">
    <h1 class="font-display text-2xl font-semibold tracking-tight text-fog-100 sm:text-3xl">{{ $book->title }}</h1>
    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-fog-400">
        <span class="badge badge-neutral">{{ $book->category->name ?? '-' }}</span>
        <span class="tabular-nums">{{ $book->year }}</span>
        <span><span class="tabular-nums">{{ $book->available_copies }} / {{ $book->total_copies }}</span> eksemplar tersedia</span>
    </div>
    <p class="mt-5 max-w-prose leading-relaxed text-fog-300">{{ $book->description ?: 'Tidak ada deskripsi.' }}</p>
</div>

<h2 class="section-title mb-4">Riwayat Peminjaman Terbaru</h2>

@if ($loans->isEmpty())
    @include('partials.empty-state', ['message' => 'Buku ini belum memiliki riwayat peminjaman.'])
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Status</th>
                    <th>Diminta</th>
                    <th>Jatuh Tempo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $l)
                    <tr class="{{ $l->is_overdue ? 'is-overdue' : '' }}">
                        <td>
                            <div class="text-fog-100">{{ $l->user->name }}</div>
                            <div class="text-xs tabular-nums text-fog-400">{{ $l->user->nim }}</div>
                        </td>
                        <td>@include('partials.status-badge', ['key' => $l->display_status])</td>
                        <td class="whitespace-nowrap">{{ $l->requested_at?->format('d M Y') }}</td>
                        <td class="whitespace-nowrap">{{ $l->due_at?->format('d M Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
