@extends('layouts.admin')
@section('title', 'Admin Dashboard')

@section('body')
<h4 class="mb-3">Students by loan status</h4>

@foreach ($groups as $label => $students)
  <h5 class="mt-4">{{ $label }} <span class="badge text-bg-secondary">{{ $students->count() }}</span></h5>
  @if ($students->isEmpty())
    <p class="text-muted small">None.</p>
  @else
  <div class="table-responsive"><table class="table table-sm table-bordered bg-white align-middle">
    <thead><tr><th>NIM</th><th>Name</th><th>Active loans</th><th></th></tr></thead>
    <tbody>
    @foreach ($students as $s)
      <tr class="{{ $label === 'Overdue' ? 'table-danger' : '' }}">
        <td>{{ $s->nim }}</td><td>{{ $s->name }}</td><td>{{ $s->loans->count() }}</td>
        <td class="text-end">
          @if ($s->loans->isNotEmpty())
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#m{{ $s->id }}">Open</button>
          @endif
        </td>
      </tr>
    @endforeach
    </tbody></table></div>
  @endif
@endforeach

{{-- one modal per student that has active loans --}}
@foreach ($groups as $students)
  @foreach ($students as $s)
    @continue($s->loans->isEmpty())
    <div class="modal fade" id="m{{ $s->id }}" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">{{ $s->nim }} — {{ $s->name }}</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        @foreach ($s->loans as $loan)
          <div class="border rounded p-3 mb-2 {{ $loan->is_overdue ? 'border-danger' : '' }}">
            <strong>{{ $loan->book->title }}</strong>
            <span class="badge text-bg-{{ $loan->display_status === 'overdue' ? 'danger' : ($loan->status === 'pending' ? 'warning' : 'success') }}">{{ $loan->display_status }}</span>

            @if ($loan->status === 'pending')
              <div class="d-flex gap-2 mt-2">
                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                <form method="POST" action="{{ route('admin.loans.decline', $loan) }}" class="d-flex gap-2">@csrf
                  <input name="reason" class="form-control form-control-sm" placeholder="Reason (optional)">
                  <button class="btn btn-sm btn-outline-danger">Decline</button></form>
              </div>
            @else
              <div class="small mt-2">Due {{ $loan->due_at->format('d M Y') }} ·
                {{ $loan->days_remaining >= 0 ? $loan->days_remaining . ' day(s) left' : abs($loan->days_remaining) . ' day(s) late' }}</div>
              <form method="POST" action="{{ route('admin.loans.return', $loan) }}" class="mt-2">@csrf
                <button class="btn btn-sm btn-dark">Mark as Returned</button></form>
            @endif
          </div>
        @endforeach
      </div>
    </div></div></div>
  @endforeach
@endforeach
@endsection
