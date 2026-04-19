@extends('layouts.admin')

@section('title', 'Update Stok — Westland Coffee')

@section('content')
    <x-page.title title="Update Stok" subtitle="{{ $ingredient->name }} • stok saat ini {{ $ingredient->formatStock((float) $ingredient->current_stock) }} {{ $ingredient->unit }}" />

    <div class="mt-8 grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
        <form method="POST" action="{{ route('admin.stocks.movement.store', $ingredient) }}" class="overflow-hidden rounded-[28px] border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            @csrf

            <div class="border-b border-zinc-200 bg-gradient-to-r from-brand-50 via-white to-white px-6 py-5">
                <div class="text-sm font-extrabold text-zinc-900">Form update stok</div>
                <div class="mt-1 text-xs text-zinc-500">Pilih tipe update, isi jumlah, lalu simpan.</div>
            </div>

            <div class="space-y-5 px-6 py-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Tipe update</label>
                        <select name="type" class="input mt-2" required>
                            <option value="in">Stok masuk</option>
                            <option value="out">Stok keluar</option>
                            <option value="adjust">Set stok</option>
                        </select>
                        @error('type') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Jumlah ({{ $ingredient->unit }})</label>
                        <input name="quantity" type="number" min="0.01" step="any" value="{{ old('quantity') }}" class="input mt-2" required />
                        @error('quantity') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Catatan</label>
                        <textarea name="note" rows="4" class="input mt-2">{{ old('note') }}</textarea>
                        @error('note') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Tanggal / waktu</label>
                        <input type="datetime-local" name="moved_at" value="{{ old('moved_at') }}" class="input mt-2" />
                        @error('moved_at') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4">
                <a href="{{ route('admin.stocks.index') }}" class="inline-flex items-center rounded-2xl px-4 py-2.5 text-sm font-extrabold text-zinc-600 transition hover:bg-white hover:text-zinc-900">Batal</a>
                <button class="inline-flex items-center rounded-2xl bg-brand-500 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-md">Simpan update</button>
            </div>
        </form>

        <div class="space-y-4">
            <div class="rounded-[28px] border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="text-sm font-extrabold text-zinc-900">Ringkas</div>
                <div class="mt-4 space-y-3">
                    <div class="rounded-2xl bg-brand-50 px-4 py-3">
                        <div class="text-xs font-extrabold text-brand-700">Stok sekarang</div>
                        <div class="mt-1 text-xl font-black text-zinc-900">{{ $ingredient->formatStock((float) $ingredient->current_stock) }} {{ $ingredient->unit }}</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="text-xs font-extrabold text-zinc-500">Batas menipis</div>
                        <div class="mt-1 text-sm font-semibold text-zinc-900">{{ $ingredient->formatStock((float) $ingredient->low_stock_threshold) }} {{ $ingredient->unit }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="text-sm font-extrabold text-zinc-900">Pilihan update</div>
                <div class="mt-4 space-y-3 text-sm text-zinc-600">
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="font-bold text-zinc-900">Stok masuk</div>
                        <div class="mt-1">Tambah stok saat bahan baru datang.</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="font-bold text-zinc-900">Stok keluar</div>
                        <div class="mt-1">Kurangi stok untuk pemakaian harian.</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="font-bold text-zinc-900">Set stok</div>
                        <div class="mt-1">Samakan stok dengan hasil hitung aktual.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
