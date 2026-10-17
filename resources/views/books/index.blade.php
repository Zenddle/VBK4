@extends('layouts.student')
@section('title', 'Buku')

@section('body')
<div class="page-head">
    <h1 class="page-title">Katalog Buku</h1>
    <p class="page-sub">Jelajahi koleksi perpustakaan</p>
</div>

<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('books.index', ['q' => request('q')]) }}"
       class="rounded-full px-3.5 py-1.5 text-sm font-medium transition
              {{ request('category') ? 'border border-ink-600 text-fog-300 hover:border-ink-500 hover:bg-ink-700 hover:text-fog-100' : 'bg-lamp-400 text-ink-950' }}">
        Semua
    </a>
    @foreach ($categories as $c)
        <a href="{{ route('books.index', ['category' => $c->id, 'q' => request('q')]) }}"
           class="rounded-full px-3.5 py-1.5 text-sm font-medium transition
                  {{ request('category') == $c->id ? 'bg-lamp-400 text-ink-950' : 'border border-ink-600 text-fog-300 hover:border-ink-500 hover:bg-ink-700 hover:text-fog-100' }}">
            {{ $c->name }}
        </a>
    @endforeach
</div>

@if ($books->isEmpty())
    @include('partials.empty-state', ['message' => 'Tidak ada buku ditemukan.'])
@else
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($books as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
    <div class="mt-8">{{ $books->links() }}</div>
@endif
@endsection
