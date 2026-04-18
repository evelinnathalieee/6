@extends('layouts.admin')

@section('title', 'Update Stok — Westland Coffee')

@section('content')
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-semibold tracking-tight">Update Stok</h1>
        <p class="text-sm text-zinc-600">Bahan: <span class="font-semibold text-zinc-900">{{ $ingredient->name }}</span> (stok saat ini: {{ $ingredient->current_stock }} {{ $ingredient->unit }})</p>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <form method="POST" action="{{ route('admin.stocks.movement.store', $ingredient) }}" class="rounded-3xl border border-zinc-200 bg-white p-6 md:col-span-2">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm text-zinc-700">Tipe</label>
                    <select name="type" class="input">
                        <option value="in">Stok masuk</option>
                        <option value="out">Stok keluar</option>
                        <option value="adjust">Set stok (adjust)</option>
                    </select>
                    @error('type') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="text-sm text-zinc-700">Jumlah ({{ $ingredient->unit }})</label>
                    <input name="quantity" value="{{ old('quantity') }}" class="input" required />
                    @error('quantity') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm text-zinc-700">Catatan (opsional)</label>
                <textarea name="note" rows="3" class="input">{{ old('note') }}</textarea>
                @error('note') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="mt-4">
                <label class="text-sm text-zinc-700">Tanggal/Waktu (opsional)</label>
                <input type="datetime-local" name="moved_at" value="{{ old('moved_at') }}" class="input" />
                @error('moved_at') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="mt-6 flex gap-3">
                <button class="btn-primary">Simpan</button>
                <a href="{{ route('admin.stocks.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6">
            <div class="text-sm font-semibold">Panduan cepat</div>
            <ul class="mt-3 space-y-2 text-sm text-zinc-600">
                <li><span class="font-semibold text-zinc-900">Stok masuk</span>: tambah stok (barang datang).</li>
                <li><span class="font-semibold text-zinc-900">Stok keluar</span>: kurangi stok (pemakaian).</li>
                <li><span class="font-semibold text-zinc-900">Set stok</span>: set angka stok terbaru (stock opname).</li>
            </ul>
        </div>
    </div>
@endsection
