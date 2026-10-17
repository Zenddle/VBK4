@extends('layouts.admin')
@section('title', 'Loan Requests')

@section('body')
<h4 class="mb-3">Pending Loan Requests</h4>
@if ($loans->isEmpty())
  @include('partials.empty-state', ['message' => 'No pending requests.'])
@else
<table class="table table-bordered bg-white align-middle">
  <thead><tr><th>Student</th><th>Book</th><th>Requested</th><th>Action</th></tr></thead>
  <tbody>
  @foreach ($loans as $l)
    <tr>
      <td>{{ $l->user->nim }} — {{ $l->user->name }}</td>
      <td>{{ $l->book->title }} <span class="text-muted small">({{ $l->book->available_copies }} left)</span></td>
      <td>{{ $l->requested_at?->diffForHumans() }}</td>
      <td class="d-flex gap-2">
        <form method="POST" action="{{ route('admin.loans.approve', $l) }}">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
        <form method="POST" action="{{ route('admin.loans.decline', $l) }}" class="d-flex gap-2">@csrf
          <input name="reason" class="form-control form-control-sm" placeholder="Reason (optional)">
          <button class="btn btn-sm btn-outline-danger">Decline</button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@endif
@endsection
