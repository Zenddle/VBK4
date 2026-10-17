@extends('layouts.admin')
@section('title', 'Kategori')

@section('body')
<div class="page-head">
    <h1 class="page-title">Kategori</h1>
    <p class="page-sub">Kelola kategori buku</p>
</div>

<form method="POST" action="{{ route('admin.categories.store') }}" class="mb-6 flex gap-2">
    @csrf
    <input name="name" placeholder="Nama kategori baru" required class="field flex-1">
    <button class="btn btn-primary">Tambah</button>
</form>

@if ($categories->isEmpty())
    @include('partials.empty-state', ['message' => 'Belum ada kategori.'])
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah Buku</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $c)
                    <tr>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.update', $c) }}" class="flex gap-2">
                                @csrf @method('PUT')
                                <input name="name" value="{{ $c->name }}" aria-label="Nama kategori" class="field field-sm flex-1 text-sm">
                                <button class="btn btn-accent btn-sm">Simpan</button>
                            </form>
                        </td>
                        <td class="tabular-nums">{{ $c->books_count }}</td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('admin.categories.destroy', $c) }}"
                                  onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
