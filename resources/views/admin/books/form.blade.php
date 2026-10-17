@extends('layouts.admin')
@section('title', $book ? 'Edit Buku' : 'Tambah Buku')

@section('body')
<div class="page-head">
    <h1 class="page-title">{{ $book ? 'Edit Buku' : 'Tambah Buku' }}</h1>
    <p class="page-sub">{{ $book ? 'Perbarui data buku' : 'Tambahkan buku baru ke koleksi' }}</p>
</div>

<form method="POST" enctype="multipart/form-data"
      action="{{ $book ? route('admin.books.update', $book) : route('admin.books.store') }}"
      class="panel max-w-3xl p-6 sm:p-8">
    @csrf
    @if ($book) @method('PUT') @endif

    <div class="mb-5">
        <label for="cover" class="label">Sampul (jpg/png/webp, maks 2MB)</label>
        <input type="file" name="cover" accept="image/*" id="cover"
               class="w-full text-sm text-fog-400 transition file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-ink-700 file:px-4 file:py-2 file:text-sm file:font-medium file:text-fog-200 hover:file:bg-ink-600">
        <img id="preview" class="mt-3 max-h-40 rounded-lg ring-1 ring-white/10 {{ $book?->cover_url ? '' : 'hidden' }}"
             src="{{ $book?->cover_url }}" alt="">
    </div>

    <div class="mb-5">
        <label for="title" class="label">Judul</label>
        <input id="title" name="title" value="{{ old('title', $book->title ?? '') }}" required class="field">
    </div>

    <div class="mb-5">
        <label for="description" class="label">Deskripsi</label>
        <textarea id="description" name="description" rows="3" class="field">{{ old('description', $book->description ?? '') }}</textarea>
    </div>

    <div class="mb-7 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div>
            <label for="year" class="label">Tahun Terbit</label>
            <input id="year" type="number" name="year" value="{{ old('year', $book->year ?? '') }}" required class="field">
        </div>
        <div>
            <label for="category_id" class="label">Kategori</label>
            <select id="category_id" name="category_id" required class="field">
                <option value="">Pilih...</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id', $book->category_id ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="total_copies" class="label">Jumlah Eksemplar</label>
            <input id="total_copies" type="number" name="total_copies" min="1" value="{{ old('total_copies', $book->total_copies ?? 1) }}" required class="field">
        </div>
    </div>

    <div class="flex items-center gap-3">
        <button type="submit" class="btn btn-primary px-5">Simpan</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-ghost px-5">Batal</a>
    </div>
</form>

<script>
document.getElementById('cover').addEventListener('change', e => {
    const f = e.target.files[0];
    if (!f) return;
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(f);
    img.classList.remove('hidden');
});
</script>
@endsection
