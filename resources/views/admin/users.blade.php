@extends('layouts.admin')
@section('title', 'Users')

@section('body')
<div class="d-flex justify-content-between mb-3">
  <form class="d-flex gap-2" method="GET">
    <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name / NIM / username">
    <button class="btn btn-outline-secondary">Search</button>
  </form>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">+ Add User</button>
</div>

@if ($users->isEmpty())
  @include('partials.empty-state', ['message' => 'No users found.'])
@else
<table class="table table-bordered bg-white align-middle">
  <thead><tr><th>NIM / Username</th><th>Name</th><th>Role</th><th></th></tr></thead>
  <tbody>
  @foreach ($users as $u)
    <tr>
      <td>{{ $u->nim ?? $u->username }}</td><td>{{ $u->name }}</td><td>{{ $u->role }}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit{{ $u->id }}">Edit</button>
        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="d-inline"
              onsubmit="return confirm('Delete this user?')">@csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Delete</button></form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $users->links() }}
@endif

{{-- Add modal --}}
<div class="modal fade" id="addUser" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.store') }}">@csrf
  <div class="modal-header"><h5 class="modal-title">Add User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">@include('admin.user-form', ['user' => null])</div>
  <div class="modal-footer"><button class="btn btn-primary">Save</button></div>
</form></div></div>

{{-- Edit modals --}}
@foreach ($users as $u)
<div class="modal fade" id="edit{{ $u->id }}" tabindex="-1"><div class="modal-dialog"><form class="modal-content" method="POST" action="{{ route('admin.users.update', $u) }}">@csrf @method('PUT')
  <div class="modal-header"><h5 class="modal-title">Edit User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">@include('admin.user-form', ['user' => $u])</div>
  <div class="modal-footer"><button class="btn btn-primary">Update</button></div>
</form></div></div>
@endforeach
@endsection
