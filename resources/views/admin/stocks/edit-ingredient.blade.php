@extends('layouts.admin')

@section('title', 'Edit Bahan — Admin Westland Coffee')

@section('content')
    <x-page.title title="Edit Bahan" subtitle="Perbarui: {{ $ingredient->name }}" />

    <form method="POST" action="{{ route('admin.stocks.update', $ingredient) }}" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf
        @method('PUT')

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm text-zinc-700">Nama bahan</label>
                <input name="name" value="{{ old('name', $ingredient->name) }}" class="input" required />
                @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Satuan</label>
                <input name="unit" value="{{ old('unit', $ingredient->unit) }}" class="input" required />
                @error('unit') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Batas menipis</label>
                <input name="low_stock_threshold" value="{{ old('low_stock_threshold', $ingredient->low_stock_threshold) }}" class="input" required />
                @error('low_stock_threshold') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-700">
                <div class="font-semibold">Info</div>
                <div class="mt-1">Stok saat ini: <span class="font-semibold">{{ $ingredient->current_stock }}</span> {{ $ingredient->unit }}</div>
                <div class="mt-1 text-xs text-zinc-600">Jika ingin mengubah stok, gunakan fitur “Update stok”.</div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan</button>
            <a href="{{ route('admin.stocks.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endsection
