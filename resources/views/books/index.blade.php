@extends('layouts.student')
@section('title', 'Books')

@section('body')
<div class="mb-3 d-flex flex-wrap gap-2">
  <a href="{{ route('books.index', ['q' => request('q')]) }}" class="btn btn-sm {{ request('category') ? 'btn-outline-primary' : 'btn-primary' }}">All</a>
  @foreach ($categories as $c)
    <a href="{{ route('books.index', ['category' => $c->id, 'q' => request('q')]) }}"
       class="btn btn-sm {{ request('category') == $c->id ? 'btn-primary' : 'btn-outline-primary' }}">{{ $c->name }}</a>
  @endforeach
</div>

@if ($books->isEmpty())
    @include('partials.empty-state', ['message' => 'No books found.'])
@else
<div class="row row-cols-2 row-cols-md-4 g-3">
  @foreach ($books as $book)<div class="col">@include('partials.book-card', ['book' => $book])</div>@endforeach
</div>
<div class="mt-4">{{ $books->links() }}</div>
@endif
@endsection
