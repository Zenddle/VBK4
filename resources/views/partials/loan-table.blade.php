@if ($loans->isEmpty())
    @include('partials.empty-state', ['message' => 'Belum ada buku yang dipinjam.'])
@else
<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>Buku</th>
                <th>Status</th>
                <th>Jatuh Tempo</th>
                <th>Sisa Waktu</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $loan)
                <tr class="{{ $loan->is_overdue ? 'is-overdue' : '' }}">
                    <td class="font-medium text-fog-100">{{ $loan->book->title }}</td>
                    <td>@include('partials.status-badge', ['key' => $loan->display_status])</td>
                    <td class="whitespace-nowrap">{{ $loan->due_at?->format('d M Y') ?? '-' }}</td>
                    <td class="whitespace-nowrap">
                        @if ($loan->status === 'approved')
                            @if ($loan->days_remaining >= 0)
                                {{ $loan->days_remaining }} hari
                            @else
                                <span class="text-rose-300">{{ abs($loan->days_remaining) }} hari terlambat</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-fog-400">{{ $loan->decline_reason ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
