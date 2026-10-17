@extends('layouts.admin')
@section('title', 'Dasbor Admin')

@section('body')
<div class="page-head">
    <h1 class="page-title">Dasbor Admin</h1>
    <p class="page-sub">Ringkasan status peminjaman mahasiswa</p>
</div>

@foreach ($groups as $label => $students)
    @php
        $labelId = [
            'Pending' => 'Menunggu Persetujuan',
            'Active' => 'Aktif',
            'Overdue' => 'Terlambat',
        ][$label] ?? $label;
        $badgeClass = $label === 'Overdue' ? 'badge-overdue' : 'badge-neutral';
    @endphp
    <div class="mb-10">
        <h2 class="section-title mb-3 flex items-center gap-2.5">
            {{ $labelId }}
            <span class="badge tabular-nums {{ $badgeClass }}">{{ $students->count() }}</span>
        </h2>

        @if ($students->isEmpty())
            <p class="text-sm text-fog-500">Tidak ada data.</p>
        @else
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Pinjaman Aktif</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $s)
                            <tr class="{{ $label === 'Overdue' ? 'is-overdue' : '' }}" x-data="{ open: false }">
                                <td class="font-medium tabular-nums text-fog-100">{{ $s->nim }}</td>
                                <td class="text-fog-200">{{ $s->name }}</td>
                                <td class="tabular-nums">{{ $s->loans->count() }}</td>
                                <td class="text-right">
                                    @if ($s->loans->isNotEmpty())
                                        <button @click="open = true" class="btn btn-primary btn-sm">Lihat</button>

                                        <!-- Modal -->
                                        <div x-show="open" x-cloak x-transition.opacity @keydown.escape.window="open = false"
                                             class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
                                            <div class="modal-backdrop" @click="open = false"></div>
                                            <div class="modal-panel max-h-[85vh] max-w-2xl overflow-y-auto" role="dialog" aria-modal="true" @click.stop>
                                                <div class="sticky top-0 z-10 flex items-center justify-between rounded-t-2xl border-b border-ink-600/70 bg-ink-800 px-6 py-4">
                                                    <h3 class="font-display font-semibold text-fog-100">
                                                        <span class="mr-2 font-sans text-sm font-normal tabular-nums text-fog-400">{{ $s->nim }}</span>{{ $s->name }}
                                                    </h3>
                                                    <button @click="open = false" class="rounded p-1 text-2xl leading-none text-fog-400 transition hover:text-fog-100" aria-label="Tutup">&times;</button>
                                                </div>
                                                <div class="space-y-3 p-6">
                                                    @foreach ($s->loans as $loan)
                                                        <div class="rounded-lg border p-4 {{ $loan->is_overdue ? 'border-rose-400/30 bg-rose-500/[0.07]' : 'border-ink-600/70 bg-ink-900/50' }}">
                                                            <div class="flex items-start justify-between gap-3">
                                                                <div>
                                                                    <p class="font-medium text-fog-100">{{ $loan->book->title }}</p>
                                                                    <div class="mt-1.5">
                                                                        @include('partials.status-badge', ['key' => $loan->display_status])
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @if ($loan->status === 'pending')
                                                                <div class="mt-4 flex flex-wrap gap-2">
                                                                    <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">@csrf
                                                                        <button class="btn btn-success btn-sm">Setujui</button>
                                                                    </form>
                                                                    <form method="POST" action="{{ route('admin.loans.decline', $loan) }}" class="flex flex-1 gap-2">@csrf
                                                                        <input name="reason" placeholder="Alasan (opsional)" class="field field-sm flex-1">
                                                                        <button class="btn btn-danger btn-sm">Tolak</button>
                                                                    </form>
                                                                </div>
                                                            @else
                                                                <p class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-fog-400">
                                                                    <span>Jatuh tempo {{ $loan->due_at->format('d M Y') }}</span>
                                                                    @if ($loan->days_remaining >= 0)
                                                                        <span>{{ $loan->days_remaining }} hari tersisa</span>
                                                                    @else
                                                                        <span class="text-rose-300">{{ abs($loan->days_remaining) }} hari terlambat</span>
                                                                    @endif
                                                                </p>
                                                                <form method="POST" action="{{ route('admin.loans.return', $loan) }}" class="mt-3">@csrf
                                                                    <button class="btn btn-secondary btn-sm">Tandai Dikembalikan</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endforeach
@endsection
