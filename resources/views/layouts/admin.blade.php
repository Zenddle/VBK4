@extends('layouts.app')

@section('nav')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">📚 Admin</a>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.users.index') }}">Users</a>
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.books.index') }}">Books</a>
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.categories.index') }}">Categories</a>
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.loans.requests') }}">Loan Requests</a>
            <a class="btn btn-sm btn-secondary" href="{{ route('admin.loans.history') }}">Loan History</a>
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
