@if ($loans->isEmpty())
    @include('partials.empty-state', ['message' => 'No borrowed books yet.'])
@else
<div class="table-responsive"><table class="table table-bordered bg-white align-middle">
  <thead><tr><th>Book</th><th>Status</th><th>Due</th><th>Remaining</th><th>Note</th></tr></thead>
  <tbody>
  @foreach ($loans as $loan)
    <tr class="{{ $loan->is_overdue ? 'table-danger' : '' }}">
      <td>{{ $loan->book->title }}</td>
      <td><span class="badge text-bg-{{ ['pending'=>'warning','approved'=>'success','declined'=>'secondary','returned'=>'info','overdue'=>'danger'][$loan->display_status] }}">{{ $loan->display_status }}</span></td>
      <td>{{ $loan->due_at?->format('d M Y') ?? '-' }}</td>
      <td>
        @if ($loan->status === 'approved')
          {{ $loan->days_remaining >= 0 ? $loan->days_remaining . ' day(s)' : abs($loan->days_remaining) . ' day(s) late' }}
        @else - @endif
      </td>
      <td>{{ $loan->decline_reason ?? '' }}</td>
    </tr>
  @endforeach
  </tbody></table></div>
@endif
