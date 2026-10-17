<a href="{{ route('books.show', $book) }}"
   class="group block h-full overflow-hidden rounded-xl border border-ink-600/70 bg-ink-800 transition-colors hover:border-lamp-400/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lamp-400/70">
    <div class="relative aspect-[3/4] overflow-hidden bg-ink-700">
        @if ($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" loading="lazy" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-fog-500">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>
                </svg>
                <span class="text-xs">Tidak ada sampul</span>
            </div>
        @endif
        <span class="absolute right-2 top-2 rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset backdrop-blur
            {{ $book->is_available ? 'bg-emerald-950/70 text-emerald-300 ring-emerald-400/30' : 'bg-ink-950/75 text-fog-300 ring-fog-400/25' }}">
            {{ $book->is_available ? 'Tersedia' : 'Dipinjam' }}
        </span>
    </div>
    <div class="p-4">
        <h3 class="font-display line-clamp-2 text-[15px] font-semibold leading-snug text-fog-100 transition-colors group-hover:text-lamp-300">{{ $book->title }}</h3>
        <div class="mt-2 flex items-center justify-between gap-3 text-xs text-fog-400">
            <span class="truncate">{{ $book->category->name ?? '-' }}</span>
            <span class="shrink-0 tabular-nums text-fog-500">{{ $book->year }}</span>
        </div>
    </div>
</a>
