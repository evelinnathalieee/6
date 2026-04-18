@extends('layouts.admin')

@section('title', 'Edit Promo — Westland Coffee')

@section('content')
    <x-page.title title="Edit Promo" subtitle="Perbarui promo: {{ $promo->name }}" />

    <form method="POST" action="{{ route('admin.promos.update', $promo) }}" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf
        @method('PUT')

        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Nama promo</label>
                <input name="name" value="{{ old('name', $promo->name) }}" class="input" required />
                @error('name') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Deskripsi</label>
                <textarea name="description" rows="4" class="input" required>{{ old('description', $promo->description) }}</textarea>
                @error('description') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Tipe diskon</label>
                <select name="discount_type" class="input" required>
                    <option value="amount" {{ old('discount_type', $promo->discount_type ?? 'amount') === 'amount' ? 'selected' : '' }}>Potongan Rp</option>
                    <option value="percent" {{ old('discount_type', $promo->discount_type) === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                </select>
                @error('discount_type') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm text-zinc-700">Nilai diskon</label>
                <input name="discount_value" value="{{ old('discount_value', $promo->discount_value ?? 0) }}" class="input" required />
                @error('discount_value') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Minimal pembelanjaan (Rp)</label>
                <input name="min_subtotal" value="{{ old('min_subtotal', $promo->min_subtotal ?? 0) }}" class="input" required />
                @error('min_subtotal') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Mulai</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($promo->starts_at)->format('Y-m-d\\TH:i')) }}" class="input" required />
                @error('starts_at') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm text-zinc-700">Selesai</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($promo->ends_at)->format('Y-m-d\\TH:i')) }}" class="input" required />
                @error('ends_at') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="text-sm text-zinc-700">Status</label>
            <select name="is_enabled" class="input" required>
                <option value="1" {{ old('is_enabled', $promo->is_enabled ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_enabled', $promo->is_enabled ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('is_enabled') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan</button>
            <a href="{{ route('admin.promos.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endsection
