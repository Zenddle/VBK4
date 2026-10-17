@extends('layouts.admin')
@section('title', 'Riwayat Pinjaman')

@section('body')
<div class="page-head">
    <h1 class="page-title">Riwayat Pinjaman</h1>
    <p class="page-sub">Filter dan lihat seluruh riwayat peminjaman</p>
</div>

<form method="GET" class="mb-6 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-6">
    <select name="user_id" aria-label="Pengguna" class="field px-3 py-2">
        <option value="">Semua pengguna</option>
        @foreach ($users as $u)
            <option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->nim }} {{ $u->name }}</option>
        @endforeach
    </select>
    <select name="book_id" aria-label="Buku" class="field px-3 py-2">
        <option value="">Semua buku</option>
        @foreach ($books as $b)
            <option value="{{ $b->id }}" @selected(request('book_id') == $b->id)>{{ $b->title }}</option>
        @endforeach
    </select>
    <select name="status" aria-label="Status" class="field px-3 py-2">
        <option value="">Semua status</option>
        @foreach (['pending' => 'Menunggu', 'approved' => 'Disetujui', 'overdue' => 'Terlambat', 'declined' => 'Ditolak', 'returned' => 'Dikembalikan'] as $s => $label)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ $label }}</option>
        @endforeach
    </select>
    <input type="date" name="from" value="{{ request('from') }}" aria-label="Dari tanggal" class="field px-3 py-2">
    <input type="date" name="to" value="{{ request('to') }}" aria-label="Sampai tanggal" class="field px-3 py-2">
    <button class="btn btn-primary px-3 py-2">Filter</button>
</form>

@if ($loans->isEmpty())
    @include('partials.empty-state', ['message' => 'Tidak ada data yang cocok.'])
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Buku</th>
                    <th>Status</th>
                    <th>Diminta</th>
                    <th>Disetujui</th>
                    <th>Jatuh Tempo</th>
                    <th>Dikembalikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $l)
                    <tr class="{{ $l->is_overdue ? 'is-overdue' : '' }}">
                        <td>
                            <div class="text-fog-100">{{ $l->user->name }}</div>
                            <div class="text-xs tabular-nums text-fog-400">{{ $l->user->nim }}</div>
                        </td>
                        <td class="text-fog-200">{{ $l->book->title }}</td>
                        <td>@include('partials.status-badge', ['key' => $l->display_status])</td>
                        <td class="whitespace-nowrap">{{ $l->requested_at?->format('d M Y') }}</td>
                        <td class="whitespace-nowrap">{{ $l->approved_at?->format('d M Y') ?? '-' }}</td>
                        <td class="whitespace-nowrap">{{ $l->due_at?->format('d M Y') ?? '-' }}</td>
                        <td class="whitespace-nowrap">{{ $l->returned_at?->format('d M Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $loans->links() }}</div>
@endif
@endsection
