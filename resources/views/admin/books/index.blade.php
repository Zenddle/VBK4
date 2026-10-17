@extends('layouts.admin')
@section('title', 'Buku')

@section('body')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="page-title">Buku</h1>
        <p class="page-sub">Kelola koleksi buku perpustakaan</p>
    </div>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.25" d="M12 4v16m8-8H4"/></svg>
        Tambah Buku
    </a>
</div>

<form method="GET" class="mb-6 flex gap-2">
    <input name="q" value="{{ request('q') }}" placeholder="Cari judul..." class="field flex-1">
    <button class="btn btn-secondary">Cari</button>
</form>

@if ($books->isEmpty())
    @include('partials.empty-state', ['message' => 'Belum ada buku.'])
@else
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sampul</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Eksemplar</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $b)
                    <tr>
                        <td>
                            @if ($b->cover_url)
                                <img src="{{ $b->cover_url }}" alt="" class="h-16 w-12 rounded-md object-cover ring-1 ring-white/10">
                            @else
                                <div class="flex h-16 w-12 items-center justify-center rounded-md bg-ink-700 text-xs text-fog-500">—</div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.books.show', $b) }}" class="font-display font-semibold text-fog-100 transition hover:text-lamp-300">{{ $b->title }}</a>
                        </td>
                        <td>{{ $b->category->name ?? '-' }}</td>
                        <td class="tabular-nums">{{ $b->year }}</td>
                        <td class="tabular-nums">{{ $b->available_copies }} / {{ $b->total_copies }}</td>
                        <td class="space-x-1 whitespace-nowrap text-right">
                            <a href="{{ route('admin.books.edit', $b) }}" class="btn btn-accent btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.books.destroy', $b) }}" class="inline"
                                  onsubmit="return confirm('Hapus buku ini?')">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $books->links() }}</div>
@endif
@endsection
