@extends('layouts.admin')

@section('title', 'Edit Bahan — Admin Westland Coffee')

@section('content')
    <x-page.title title="Edit Bahan" subtitle="Atur nama, satuan, dan batas stok untuk {{ $ingredient->name }}." />

    <div class="mt-8 grid gap-5 lg:grid-cols-[minmax(0,1fr)_340px]">
        <form method="POST" action="{{ route('admin.stocks.update', $ingredient) }}" class="overflow-hidden rounded-[28px] border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            @csrf
            @method('PUT')

            <div class="border-b border-zinc-200 bg-gradient-to-r from-brand-50 via-white to-white px-6 py-5">
                <div class="text-sm font-extrabold text-zinc-900">Form bahan</div>
                <div class="mt-1 text-xs text-zinc-500">Isi data utama bahan baku di sini.</div>
            </div>

            <div class="space-y-5 px-6 py-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Nama bahan</label>
                        <input name="name" value="{{ old('name', $ingredient->name) }}" class="input mt-2" required />
                        @error('name') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Satuan</label>
                        <input name="unit" value="{{ old('unit', $ingredient->unit) }}" class="input mt-2" required />
                        @error('unit') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-extrabold text-zinc-800">Batas menipis</label>
                        <input name="low_stock_threshold" type="number" min="0" step="0.01" value="{{ old('low_stock_threshold', $ingredient->low_stock_threshold) }}" class="input mt-2" required />
                        @error('low_stock_threshold') <div class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</div> @enderror
                    </div>

                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50/70 p-4">
                        <div class="text-xs font-extrabold uppercase tracking-wide text-zinc-500">Catatan</div>
                        <div class="mt-2 text-sm font-semibold text-zinc-900">Stok aktif diubah dari halaman `Update stok`.</div>
                        <div class="mt-1 text-xs text-zinc-500">Halaman ini khusus ubah identitas bahan.</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-zinc-200 bg-zinc-50 px-6 py-4">
                <a href="{{ route('admin.stocks.index') }}" class="inline-flex items-center rounded-2xl px-4 py-2.5 text-sm font-extrabold text-zinc-600 transition hover:bg-white hover:text-zinc-900">Batal</a>
                <button class="inline-flex items-center rounded-2xl bg-brand-500 px-5 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-md">Simpan perubahan</button>
            </div>
        </form>

        <div class="space-y-5">
            <div class="rounded-[28px] border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="text-sm font-extrabold text-zinc-900">Ringkasan bahan</div>
                <div class="mt-4 rounded-2xl bg-brand-50 px-4 py-4">
                    <div class="text-xs font-extrabold text-brand-700">Stok sekarang</div>
                    <div class="mt-1 text-2xl font-black text-zinc-900">{{ rtrim(rtrim(number_format((float) $ingredient->current_stock, 2, '.', ''), '0'), '.') }} {{ $ingredient->unit }}</div>
                </div>
                <div class="mt-3 rounded-2xl border border-zinc-200 px-4 py-4">
                    <div class="text-xs font-extrabold text-zinc-500">Stok awal</div>
                    <div class="mt-1 text-sm font-bold text-zinc-900">{{ rtrim(rtrim(number_format((float) $ingredient->opening_stock, 2, '.', ''), '0'), '.') }} {{ $ingredient->unit }}</div>
                </div>
            </div>

            <div class="rounded-[28px] border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="text-sm font-extrabold text-zinc-900">Aksi cepat</div>
                <a href="{{ route('admin.stocks.movement.create', $ingredient) }}" class="mt-4 inline-flex w-full items-center justify-center rounded-2xl bg-brand-500 px-4 py-3 text-sm font-extrabold text-white transition hover:-translate-y-0.5 hover:bg-brand-600 hover:shadow-md">
                    Buka update stok
                </a>
            </div>
        </div>
    </div>
@endsection
