@extends('layouts.student')
@section('title', 'Wishlist')

@section('body')
<h4 class="mb-3">My Wishlist</h4>
@if ($books->isEmpty())
    @include('partials.empty-state', ['message' => 'Your wishlist is empty.'])
@else
<div class="row row-cols-2 row-cols-md-4 g-3">
  @foreach ($books as $book)<div class="col">@include('partials.book-card', ['book' => $book])</div>@endforeach
</div>
@endif
@endsection
