@extends('layouts.student')
@section('title', 'Wishlist')

@section('body')
<div class="page-head">
    <h1 class="page-title">Wishlist Saya</h1>
    <p class="page-sub">Buku yang kamu simpan untuk nanti</p>
</div>

@if ($books->isEmpty())
    @include('partials.empty-state', ['message' => 'Wishlist masih kosong.'])
@else
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($books as $book)
            @include('partials.book-card', ['book' => $book])
        @endforeach
    </div>
@endif
@endsection
