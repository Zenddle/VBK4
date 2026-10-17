@extends('layouts.student')
@section('title', 'Borrowed Books')

@section('body')
<h4 class="mb-3">Borrowed Books</h4>
<p class="text-muted small">Loan duration is 7 days from approval. Max 3 active loans.</p>
@include('partials.loan-table', ['loans' => $loans])
@endsection
