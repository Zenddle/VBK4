{{-- Shared fields for create/edit modal. Pass $user (null for create) --}}
@php $u = $user ?? null; @endphp
<div class="mb-2"><label class="form-label">Full name</label>
  <input name="name" class="form-control" value="{{ $u->name ?? '' }}" required></div>
<div class="mb-2"><label class="form-label">Role</label>
  <select name="role" class="form-select">
    <option value="student" @selected(($u->role ?? 'student') === 'student')>Student</option>
    <option value="admin" @selected(($u->role ?? '') === 'admin')>Admin</option>
  </select></div>
<div class="mb-2"><label class="form-label">NIM (students)</label>
  <input name="nim" class="form-control" value="{{ $u->nim ?? '' }}"></div>
<div class="mb-2"><label class="form-label">Username (admins)</label>
  <input name="username" class="form-control" value="{{ $u->username ?? '' }}"></div>
<div class="mb-2"><label class="form-label">Password {{ $u ? '(leave blank to keep)' : '' }}</label>
  <input type="password" name="password" class="form-control"></div>
<div class="mb-2"><label class="form-label">Confirm password</label>
  <input type="password" name="password_confirmation" class="form-control"></div>
