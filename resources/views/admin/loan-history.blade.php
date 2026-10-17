@extends('layouts.admin')
@section('title', 'Loan History')

@section('body')
<h4 class="mb-3">Loan History</h4>
<form method="GET" class="row g-2 mb-3">
  <div class="col-md-2"><select name="user_id" class="form-select form-select-sm"><option value="">All users</option>
    @foreach ($users as $u)<option value="{{ $u->id }}" @selected(request('user_id') == $u->id)>{{ $u->nim }} {{ $u->name }}</option>@endforeach</select></div>
  <div class="col-md-2"><select name="book_id" class="form-select form-select-sm"><option value="">All books</option>
    @foreach ($books as $b)<option value="{{ $b->id }}" @selected(request('book_id') == $b->id)>{{ $b->title }}</option>@endforeach</select></div>
  <div class="col-md-2"><select name="status" class="form-select form-select-sm"><option value="">All status</option>
    @foreach (['pending','approved','overdue','declined','returned'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>@endforeach</select></div>
  <div class="col-md-2"><input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm"></div>
  <div class="col-md-2"><input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm"></div>
  <div class="col-md-2"><button class="btn btn-sm btn-secondary w-100">Filter</button></div>
</form>

@if ($loans->isEmpty())
  @include('partials.empty-state', ['message' => 'No loans match.'])
@else
<table class="table table-bordered table-sm bg-white">
  <thead><tr><th>Student</th><th>Book</th><th>Status</th><th>Requested</th><th>Approved</th><th>Due</th><th>Returned</th></tr></thead>
  <tbody>
  @foreach ($loans as $l)
    <tr class="{{ $l->is_overdue ? 'table-danger' : '' }}">
      <td>{{ $l->user->nim }} — {{ $l->user->name }}</td><td>{{ $l->book->title }}</td>
      <td>{{ $l->display_status }}</td>
      <td>{{ $l->requested_at?->format('d M Y') }}</td><td>{{ $l->approved_at?->format('d M Y') ?? '-' }}</td>
      <td>{{ $l->due_at?->format('d M Y') ?? '-' }}</td><td>{{ $l->returned_at?->format('d M Y') ?? '-' }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $loans->links() }}
@endif
@endsection
