@extends('layouts.admin')

@section('title', 'Tambah Bahan — Admin Westland Coffee')

@section('content')
    <x-page.title title="Tambah Bahan" subtitle="Buat data bahan baku baru." />

    <form method="POST" action="{{ route('admin.stocks.store') }}" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm text-zinc-700">Nama bahan</label>
                <input name="name" value="{{ old('name') }}" class="input" placeholder="contoh: kopi" required />
                @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Satuan</label>
                <input name="unit" value="{{ old('unit', 'pcs') }}" class="input" placeholder="g / ml / pcs" required />
                @error('unit') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Stok awal</label>
                <input name="opening_stock" value="{{ old('opening_stock', 0) }}" class="input" required />
                @error('opening_stock') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Batas menipis</label>
                <input name="low_stock_threshold" value="{{ old('low_stock_threshold', 0) }}" class="input" required />
                @error('low_stock_threshold') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan</button>
            <a href="{{ route('admin.stocks.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endsection
