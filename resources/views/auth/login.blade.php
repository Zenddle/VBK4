@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h4 class="mb-3 text-center">Library Login</h4>

        <ul class="nav nav-pills nav-fill mb-3">
          <li class="nav-item"><button class="nav-link {{ old('type', 'student') === 'student' ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#tab-student" type="button">Student</button></li>
          <li class="nav-item"><button class="nav-link {{ old('type') === 'admin' ? 'active' : '' }}" data-bs-toggle="pill" data-bs-target="#tab-admin" type="button">Admin</button></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade {{ old('type', 'student') === 'student' ? 'show active' : '' }}" id="tab-student">
            <form method="POST" action="{{ route('login.attempt') }}">@csrf
              <input type="hidden" name="type" value="student">
              <div class="mb-3"><label class="form-label">NIM</label>
                <input name="identifier" class="form-control" value="{{ old('type') === 'student' ? old('identifier') : '' }}"></div>
              <div class="mb-3"><label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"></div>
              <div class="form-check mb-3"><input type="checkbox" name="remember" class="form-check-input" id="r1"><label class="form-check-label" for="r1">Remember me</label></div>
              <button class="btn btn-primary w-100">Login as Student</button>
            </form>
          </div>

          <div class="tab-pane fade {{ old('type') === 'admin' ? 'show active' : '' }}" id="tab-admin">
            <form method="POST" action="{{ route('login.attempt') }}">@csrf
              <input type="hidden" name="type" value="admin">
              <div class="mb-3"><label class="form-label">Username</label>
                <input name="identifier" class="form-control" value="{{ old('type') === 'admin' ? old('identifier') : '' }}"></div>
              <div class="mb-3"><label class="form-label">Password</label>
                <input type="password" name="password" class="form-control"></div>
              <button class="btn btn-dark w-100">Login as Admin</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
