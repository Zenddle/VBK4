@extends('layouts.app')

@section('nav')
@php
    // [nama route, pola route aktif, label desktop, label mobile]
    $navItems = [
        ['admin.users.index',      'admin.users.*',      'Pengguna',   'Pengguna'],
        ['admin.books.index',      'admin.books.*',      'Buku',       'Buku'],
        ['admin.categories.index', 'admin.categories.*', 'Kategori',   'Kategori'],
        ['admin.loans.requests',   'admin.loans.requests', 'Permintaan', 'Permintaan Pinjaman'],
        ['admin.loans.history',    'admin.loans.history',  'Riwayat',    'Riwayat Pinjaman'],
    ];
@endphp
<nav class="sticky top-0 z-40 border-b border-ink-600/60 bg-ink-900/85 backdrop-blur-md" x-data="{ open: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lamp-400/70">
                @include('partials.brand-mark')
                <span class="font-display text-lg font-semibold tracking-tight text-fog-100">Perpustakaan</span>
                <span class="badge badge-admin">Admin</span>
            </a>

            <!-- Desktop menu -->
            <div class="hidden items-center gap-1 lg:flex">
                @foreach ($navItems as [$routeName, $pattern, $label])
                    <a href="{{ route($routeName) }}" class="nav-link" @if (request()->routeIs($pattern)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="ml-3 border-l border-ink-600/70 pl-3">@csrf
                    <button class="btn btn-secondary btn-sm">Keluar</button>
                </form>
            </div>

            <!-- Mobile toggle -->
            <button @click="open = !open" class="rounded-lg p-2 text-fog-300 transition hover:bg-ink-700 hover:text-fog-100 lg:hidden" aria-label="Menu" :aria-expanded="open">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile menu -->
        <div x-show="open" x-cloak x-transition class="space-y-1 pb-4 lg:hidden">
            @foreach ($navItems as [$routeName, $pattern, $label, $mobileLabel])
                <a href="{{ route($routeName) }}" class="nav-link block" @if (request()->routeIs($pattern)) aria-current="page" @endif>{{ $mobileLabel }}</a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="nav-link block w-full text-left">Keluar</button>
            </form>
        </div>
    </div>
</nav>
@endsection

@section('content')
    @yield('body')
@endsection
