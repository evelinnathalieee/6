@extends('layouts.admin')

@section('title', 'Tambah Bahan — Admin Westland Coffee')

@section('content')
    <x-page.title title="Tambah Bahan" subtitle="Buat data bahan baku baru." />

    <div class="mt-8 grid gap-5 lg:grid-cols-[minmax(0,1fr)_340px]">
        <form method="POST" action="{{ route('admin.stocks.store') }}" class="overflow-hidden rounded-[28px] border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            @csrf

            <div class="border-b border-zinc-200 bg-gradient-to-r from-brand-50 via-white to-white px-6 py-5">
                <div class="text-sm font-extrabold text-zinc-900">Form bahan baru</div>
                <div class="mt-1 text-xs text-zinc-500">Lengkapi data dasar sebelum bahan dipakai di sistem.</div>
            </div>

            <div class="space-y-5 px-6 py-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Nama bahan</label>
                        <input name="name" value="{{ old('name') }}" class="input mt-2" placeholder="Contoh: kopi arabica" required />
                        @error('name') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Satuan</label>
                        <input name="unit" value="{{ old('unit', 'pcs') }}" class="input mt-2" placeholder="g / ml / pcs" required />
                        @error('unit') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Stok awal</label>
                        <input name="opening_stock" type="number" min="0" step="0.01" value="{{ old('opening_stock', 0) }}" class="input mt-2" required />
                        @error('opening_stock') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Batas menipis</label>
                        <input name="low_stock_threshold" type="number" min="0" step="0.01" value="{{ old('low_stock_threshold', 0) }}" class="input mt-2" required />
                        @error('low_stock_threshold') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4">
                <a href="{{ route('admin.stocks.index') }}" class="inline-flex items-center rounded-2xl px-4 py-2.5 text-sm font-extrabold text-zinc-600 transition hover:bg-white hover:text-zinc-900">Batal</a>
                <button class="inline-flex items-center rounded-2xl bg-brand-500 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-md">Simpan bahan</button>
            </div>
        </form>

        <div class="space-y-5">
            <div class="rounded-[28px] border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="text-sm font-extrabold text-zinc-900">Tips isi cepat</div>
                <div class="mt-4 space-y-3 text-sm text-zinc-600">
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="font-bold text-zinc-900">Nama</div>
                        <div class="mt-1">Gunakan nama bahan yang dipakai sehari-hari di kasir.</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="font-bold text-zinc-900">Satuan</div>
                        <div class="mt-1">Contoh paling umum: `pcs`, `ml`, `gr`, atau `liter`.</div>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 px-4 py-3">
                        <div class="font-bold text-zinc-900">Batas menipis</div>
                        <div class="mt-1">Isi angka minimum saat stok mulai perlu dibeli lagi.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
