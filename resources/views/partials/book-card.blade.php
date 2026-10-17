<div class="card h-100 shadow-sm">
    @if ($book->cover_url)
        <img src="{{ $book->cover_url }}" class="card-img-top" style="height:220px;object-fit:cover" alt="{{ $book->title }}">
    @else
        <div class="bg-secondary-subtle d-flex align-items-center justify-content-center" style="height:220px">No cover</div>
    @endif
    <div class="card-body d-flex flex-column">
        <h6 class="card-title">{{ $book->title }}</h6>
        <p class="text-muted small mb-2">{{ $book->year }} · {{ $book->category->name ?? '-' }}</p>
        <span class="badge mb-3 align-self-start {{ $book->is_available ? 'text-bg-success' : 'text-bg-secondary' }}">
            {{ $book->is_available ? 'available' : 'borrowed' }}
        </span>
        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary mt-auto">Details</a>
    </div>
</div>
