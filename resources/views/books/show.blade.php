@extends('layouts.student')
@section('title', $book->title)

@section('body')
<div class="card shadow-sm"><div class="card-body"><div class="row">
  <div class="col-md-4 mb-3">
    @if ($book->cover_url)<img src="{{ $book->cover_url }}" class="img-fluid rounded" alt="">
    @else<div class="bg-secondary-subtle d-flex align-items-center justify-content-center rounded" style="height:300px">No cover</div>@endif
  </div>
  <div class="col-md-8">
    <h3>{{ $book->title }}</h3>
    <p class="text-muted">{{ $book->year }} · {{ $book->category->name ?? '-' }}</p>
    <span class="badge {{ $book->is_available ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $book->is_available ? 'available' : 'borrowed' }}</span>
    <span class="text-muted small ms-2">{{ $book->available_copies }} / {{ $book->total_copies }} copies</span>
    <p class="mt-3">{{ $book->description ?: 'No description.' }}</p>

    <div class="d-flex gap-2">
      <form method="POST" action="{{ route('loans.store') }}">@csrf
        <input type="hidden" name="book_id" value="{{ $book->id }}">
        <span @if ($reason) data-bs-toggle="tooltip" title="{{ $reason }}" @endif class="d-inline-block">
          <button class="btn btn-primary" @disabled($reason)>Borrow</button>
        </span>
      </form>
      <form method="POST" action="{{ route('wishlist.toggle', $book) }}">@csrf
        <button class="btn btn-outline-danger">{{ $wishlisted ? '♥ Remove from wishlist' : '♡ Add to wishlist' }}</button>
      </form>
    </div>
    @if ($reason)<p class="text-danger small mt-2">{{ $reason }}</p>@endif
  </div>
</div></div></div>

<script>document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));</script>
@endsection
