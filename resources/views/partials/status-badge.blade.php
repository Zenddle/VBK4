{{-- Lencana status pinjaman. Pakai: @include('partials.status-badge', ['key' => $loan->display_status]) --}}
@php
    $badgeMap = [
        'pending'  => 'badge-pending',
        'approved' => 'badge-approved',
        'declined' => 'badge-declined',
        'returned' => 'badge-returned',
        'overdue'  => 'badge-overdue',
    ];
    $badgeLabel = [
        'pending'  => 'Menunggu',
        'approved' => 'Disetujui',
        'declined' => 'Ditolak',
        'returned' => 'Dikembalikan',
        'overdue'  => 'Terlambat',
    ];
@endphp
<span class="badge {{ $badgeMap[$key] ?? 'badge-neutral' }}">{{ $badgeLabel[$key] ?? $key }}</span>
