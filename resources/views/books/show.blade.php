@extends('layouts.student')
@section('title', $book->title)

@section('body')
<div class="panel overflow-hidden">
    <div class="grid gap-0 md:grid-cols-5">
        <div class="flex items-center justify-center border-b border-ink-600/60 bg-ink-900/60 p-8 md:col-span-2 md:border-b-0 md:border-r">
            @if ($book->cover_url)
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="max-h-80 rounded-lg object-cover shadow-2xl shadow-black/60 ring-1 ring-white/10">
            @else
                <div class="flex aspect-[3/4] w-full max-w-xs flex-col items-center justify-center gap-2 rounded-lg bg-ink-700 text-fog-500">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                    </svg>
                    <span class="text-sm">Tidak ada sampul</span>
                </div>
            @endif
        </div>
        <div class="p-6 sm:p-8 md:col-span-3">
            <h1 class="font-display text-2xl font-semibold leading-tight tracking-tight text-fog-100 sm:text-3xl">{{ $book->title }}</h1>

            <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-fog-400">
                <span class="badge badge-neutral">{{ $book->category->name ?? '-' }}</span>
                <span class="tabular-nums">{{ $book->year }}</span>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <span class="badge {{ $book->is_available ? 'badge-approved' : 'badge-declined' }}">
                    {{ $book->is_available ? 'Tersedia' : 'Dipinjam' }}
                </span>
                <span class="text-sm text-fog-400"><span class="tabular-nums">{{ $book->available_copies }} / {{ $book->total_copies }}</span> eksemplar</span>
            </div>

            <p class="mt-6 max-w-prose leading-relaxed text-fog-300">{{ $book->description ?: 'Tidak ada deskripsi.' }}</p>

            <div class="mt-8 flex flex-wrap gap-3">
                <form method="POST" action="{{ route('loans.store') }}">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit"
                            @disabled($reason)
                            title="{{ $reason }}"
                            class="btn px-5 {{ $reason ? 'btn-disabled' : 'btn-primary' }}">
                        Pinjam
                    </button>
                </form>
                <form method="POST" action="{{ route('wishlist.toggle', $book) }}">
                    @csrf
                    <button type="submit" class="btn px-5 {{ $wishlisted ? 'btn-danger' : 'btn-secondary' }}">
                        {{ $wishlisted ? '♥ Hapus dari Wishlist' : '♡ Tambah ke Wishlist' }}
                    </button>
                </form>
            </div>

            @if ($reason)
                <p class="mt-4 text-sm text-rose-300">{{ $reason }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
