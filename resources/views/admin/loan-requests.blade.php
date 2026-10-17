@extends('layouts.admin')
@section('title', 'Permintaan Pinjaman')

@section('body')
<div class="page-head">
    <h1 class="page-title">Permintaan Pinjaman</h1>
    <p class="page-sub">Permintaan yang menunggu persetujuan</p>
</div>

@if ($loans->isEmpty())
    @include('partials.empty-state', ['message' => 'Tidak ada permintaan tertunda.'])
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mahasiswa</th>
                    <th>Buku</th>
                    <th>Diminta</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loans as $l)
                    <tr>
                        <td>
                            <div class="text-fog-100">{{ $l->user->name }}</div>
                            <div class="text-xs tabular-nums text-fog-400">{{ $l->user->nim }}</div>
                        </td>
                        <td>
                            <div class="font-medium text-fog-100">{{ $l->book->title }}</div>
                            <div class="text-xs text-fog-400">{{ $l->book->available_copies }} tersisa</div>
                        </td>
                        <td class="whitespace-nowrap">{{ $l->requested_at?->diffForHumans() }}</td>
                        <td>
                            <div class="flex flex-wrap gap-2">
                                <form method="POST" action="{{ route('admin.loans.approve', $l) }}">@csrf
                                    <button class="btn btn-success btn-sm">Setujui</button>
                                </form>
                                <form method="POST" action="{{ route('admin.loans.decline', $l) }}" class="flex gap-2">@csrf
                                    <input name="reason" placeholder="Alasan (opsional)" aria-label="Alasan penolakan" class="field field-sm w-40">
                                    <button class="btn btn-danger btn-sm">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
