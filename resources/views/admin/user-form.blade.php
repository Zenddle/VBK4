{{-- Shared fields for create/edit modal. Pass $user (null for create) --}}
@php $u = $user ?? null; @endphp
<div class="space-y-4">
    <div>
        <label class="label">Nama Lengkap</label>
        <input name="name" value="{{ $u->name ?? '' }}" required class="field">
    </div>
    <div>
        <label class="label">Peran</label>
        <select name="role" class="field">
            <option value="student" @selected(($u->role ?? 'student') === 'student')>Mahasiswa</option>
            <option value="admin" @selected(($u->role ?? '') === 'admin')>Admin</option>
        </select>
    </div>
    <div>
        <label class="label">NIM (mahasiswa)</label>
        <input name="nim" value="{{ $u->nim ?? '' }}" class="field">
    </div>
    <div>
        <label class="label">Username (admin)</label>
        <input name="username" value="{{ $u->username ?? '' }}" class="field">
    </div>
    <div>
        <label class="label">Kata Sandi {{ $u ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input type="password" name="password" class="field" autocomplete="new-password">
    </div>
    <div>
        <label class="label">Konfirmasi Kata Sandi</label>
        <input type="password" name="password_confirmation" class="field" autocomplete="new-password">
    </div>
</div>
