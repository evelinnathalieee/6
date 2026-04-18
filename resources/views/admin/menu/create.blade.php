@extends('layouts.admin')

@section('title', 'Tambah Menu — Admin Westland Coffee')

@section('content')
    <x-page.title title="Tambah Menu" subtitle="Tambahkan item menu kopi/non-kopi." />

    <form method="POST" action="{{ route('admin.menu.store') }}" enctype="multipart/form-data" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Nama</label>
                <input name="name" value="{{ old('name') }}" class="input" required />
                @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Kategori</label>
                <select name="category" class="input">
                    <option value="kopi" {{ old('category') === 'kopi' ? 'selected' : '' }}>Kopi</option>
                    <option value="non_kopi" {{ old('category') === 'non_kopi' ? 'selected' : '' }}>Non-kopi</option>
                </select>
                @error('category') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Harga (Rp)</label>
                <input name="price" value="{{ old('price') }}" class="input" required />
                @error('price') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Deskripsi</label>
                <textarea name="description" rows="3" class="input" required>{{ old('description') }}</textarea>
                @error('description') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Gambar</label>
                <input type="file" name="image" accept="image/*" class="input" required />
                @error('image') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <label class="mt-4 flex items-center gap-2 text-sm text-zinc-700">
            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="accent-brand-500" />
            Tampilkan sebagai menu unggulan
        </label>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan</button>
            <a href="{{ route('admin.menu.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endsection
