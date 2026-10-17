@extends('layouts.student')
@section('title', 'Dashboard')

@section('body')
<h4 class="mb-3">Hello, {{ auth()->user()->name }}</h4>
<div class="row g-3 mb-4">
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="text-muted small">Active loans</div><div class="fs-3">{{ $active }} / {{ $limit }}</div></div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="text-muted small">Overdue</div><div class="fs-3 {{ $overdue ? 'text-danger' : '' }}">{{ $overdue }}</div></div></div></div>
  <div class="col-md-4"><a href="{{ route('books.index') }}" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center">Browse books →</a></div>
</div>

<h5>Recent loans</h5>
@include('partials.loan-table', ['loans' => $loans])
@endsection
