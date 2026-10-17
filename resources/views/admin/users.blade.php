@extends('layouts.admin')
@section('title', 'Pengguna')

@section('body')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" x-data="{ addOpen: false }">
    <div>
        <h1 class="page-title">Pengguna</h1>
        <p class="page-sub">Kelola akun mahasiswa dan admin</p>
    </div>
    <button @click="addOpen = true" class="btn btn-primary">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.25" d="M12 4v16m8-8H4"/></svg>
        Tambah Pengguna
    </button>

    <!-- Add User Modal -->
    <div x-show="addOpen" x-cloak x-transition.opacity @keydown.escape.window="addOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="modal-backdrop" @click="addOpen = false"></div>
        <form method="POST" action="{{ route('admin.users.store') }}"
              class="modal-panel max-h-[90vh] max-w-md overflow-y-auto p-6" role="dialog" aria-modal="true" @click.stop>
            @csrf
            <div class="mb-5 flex items-center justify-between">
                <h3 class="section-title">Tambah Pengguna</h3>
                <button type="button" @click="addOpen = false" class="rounded p-1 text-2xl leading-none text-fog-400 transition hover:text-fog-100" aria-label="Tutup">&times;</button>
            </div>
            @include('admin.user-form', ['user' => null])
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" @click="addOpen = false" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<form method="GET" class="mb-6 flex gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama / NIM / username..." class="field flex-1">
    <button class="btn btn-secondary">Cari</button>
</form>

@if ($users->isEmpty())
    @include('partials.empty-state', ['message' => 'Tidak ada pengguna ditemukan.'])
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>NIM / Username</th>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $u)
                    <tr x-data="{ editOpen: false }">
                        <td class="font-medium tabular-nums text-fog-100">{{ $u->nim ?? $u->username }}</td>
                        <td class="text-fog-200">{{ $u->name }}</td>
                        <td>
                            <span class="badge {{ $u->role === 'admin' ? 'badge-admin' : 'badge-returned' }}">
                                {{ $u->role === 'admin' ? 'Admin' : 'Mahasiswa' }}
                            </span>
                        </td>
                        <td class="space-x-1 whitespace-nowrap text-right">
                            <button @click="editOpen = true" class="btn btn-accent btn-sm">Edit</button>
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" class="inline"
                                  onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>

                            <!-- Edit Modal -->
                            <div x-show="editOpen" x-cloak x-transition.opacity @keydown.escape.window="editOpen = false"
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                <div class="modal-backdrop" @click="editOpen = false"></div>
                                <form method="POST" action="{{ route('admin.users.update', $u) }}"
                                      class="modal-panel max-h-[90vh] max-w-md overflow-y-auto p-6" role="dialog" aria-modal="true" @click.stop>
                                    @csrf @method('PUT')
                                    <div class="mb-5 flex items-center justify-between">
                                        <h3 class="section-title">Edit Pengguna</h3>
                                        <button type="button" @click="editOpen = false" class="rounded p-1 text-2xl leading-none text-fog-400 transition hover:text-fog-100" aria-label="Tutup">&times;</button>
                                    </div>
                                    @include('admin.user-form', ['user' => $u])
                                    <div class="mt-6 flex justify-end gap-2">
                                        <button type="button" @click="editOpen = false" class="btn btn-ghost">Batal</button>
                                        <button type="submit" class="btn btn-primary">Perbarui</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
@endif
@endsection
