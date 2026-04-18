@extends('layouts.admin')

@section('title', 'Tambah Promo — Westland Coffee')

@section('content')
    <x-page.title title="Tambah Promo" subtitle="Buat promo baru untuk halaman umum." />

    <form method="POST" action="{{ route('admin.promos.store') }}" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Nama promo</label>
                <input name="name" value="{{ old('name') }}" class="input" required />
                @error('name') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Deskripsi</label>
                <textarea name="description" rows="4" class="input" required>{{ old('description') }}</textarea>
                @error('description') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Tipe diskon</label>
                <select name="discount_type" class="input" required>
                    <option value="amount" {{ old('discount_type', 'amount') === 'amount' ? 'selected' : '' }}>Potongan Rp</option>
                    <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                </select>
                @error('discount_type') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm text-zinc-700">Nilai diskon</label>
                <input name="discount_value" value="{{ old('discount_value', 0) }}" class="input" required />
                <div class="mt-1 text-xs text-zinc-500">Jika persen, isi 1–100. Jika Rp, isi nominal potongan.</div>
                @error('discount_value') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Minimal pembelanjaan (Rp)</label>
                <input name="min_subtotal" value="{{ old('min_subtotal', 0) }}" class="input" />
                @error('min_subtotal') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Mulai (opsional)</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="input" />
                @error('starts_at') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm text-zinc-700">Selesai (opsional)</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="input" />
                @error('ends_at') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>
        </div>

        <label class="mt-4 flex items-center gap-2 text-sm text-zinc-700">
            <input type="checkbox" name="is_enabled" value="1" checked class="accent-brand-500" />
            Aktifkan promo
        </label>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan</button>
            <a href="{{ route('admin.promos.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endsection
