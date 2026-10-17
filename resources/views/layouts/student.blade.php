@extends('layouts.app')

@section('nav')
@php
    // [nama route, pola route aktif, label]
    $navItems = [
        ['books.index',      'books.*',          'Buku'],
        ['student.loans',    'student.loans',    'Dipinjam'],
        ['student.wishlist', 'student.wishlist', 'Wishlist'],
    ];
    $userName = auth()->user()->name;
    $userInitial = mb_strtoupper(mb_substr($userName, 0, 1));
@endphp
<nav class="sticky top-0 z-40 border-b border-ink-600/60 bg-ink-900/85 backdrop-blur-md" x-data="{ open: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <a href="{{ route('student.dashboard') }}" class="flex shrink-0 items-center gap-3 rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lamp-400/70">
                @include('partials.brand-mark')
                <span class="hidden font-display text-lg font-semibold tracking-tight text-fog-100 sm:inline">Perpustakaan</span>
            </a>

            <form class="hidden max-w-md flex-1 md:flex" action="{{ route('books.index') }}" method="GET" role="search">
                <div class="relative w-full">
                    <input name="q" value="{{ request('q') }}" placeholder="Cari judul atau kategori..." aria-label="Cari buku"
                           class="field py-2 pr-10">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-fog-400 transition hover:text-lamp-300" aria-label="Cari">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>

            <div class="hidden items-center gap-1 lg:flex">
                @foreach ($navItems as [$routeName, $pattern, $label])
                    <a href="{{ route($routeName) }}" class="nav-link" @if (request()->routeIs($pattern)) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
                <div class="ml-3 flex items-center gap-3 border-l border-ink-600/70 pl-4">
                    <span class="grid h-7 w-7 place-items-center rounded-full bg-ink-600 text-xs font-semibold text-fog-100" aria-hidden="true">{{ $userInitial }}</span>
                    <span class="max-w-[10rem] truncate text-sm text-fog-300">{{ $userName }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="btn btn-secondary btn-sm">Keluar</button>
                    </form>
                </div>
            </div>

            <button @click="open = !open" class="shrink-0 rounded-lg p-2 text-fog-300 transition hover:bg-ink-700 hover:text-fog-100 lg:hidden" aria-label="Menu" :aria-expanded="open">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div x-show="open" x-cloak x-transition class="space-y-1 pb-4 lg:hidden">
            <form action="{{ route('books.index') }}" method="GET" class="mb-2 md:hidden" role="search">
                <input name="q" value="{{ request('q') }}" placeholder="Cari judul atau kategori..." aria-label="Cari buku" class="field py-2">
            </form>
            @foreach ($navItems as [$routeName, $pattern, $label])
                <a href="{{ route($routeName) }}" class="nav-link block" @if (request()->routeIs($pattern)) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <div class="flex items-center gap-3 px-3 py-2 text-sm text-fog-400">
                <span class="grid h-7 w-7 place-items-center rounded-full bg-ink-600 text-xs font-semibold text-fog-100" aria-hidden="true">{{ $userInitial }}</span>
                {{ $userName }}
            </div>
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
