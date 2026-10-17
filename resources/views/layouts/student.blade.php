@extends('layouts.app')

@section('nav')
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="{{ route('student.dashboard') }}">📚 Library</a>
        <form class="d-flex flex-grow-1 mx-lg-4 my-2 my-lg-0" action="{{ route('books.index') }}" method="GET">
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Search title or category...">
        </form>
        <div class="d-flex gap-2 align-items-center">
            <a class="btn btn-sm btn-light" href="{{ route('books.index') }}">Books</a>
            <a class="btn btn-sm btn-light" href="{{ route('student.loans') }}">Borrowed</a>
            <a class="btn btn-sm btn-light" href="{{ route('student.wishlist') }}">Wishlist</a>
            <span class="text-white small">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>
    </div>
</nav>
@endsection

@section('content')
    @yield('body')
@endsection
