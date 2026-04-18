@extends('layouts.admin')

@section('title', 'Manajemen Stok — Westland Coffee')

@section('content')
    <x-page.title title="Stok" subtitle="Bahan baku dan status stok." />

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.stocks.create') }}" class="rounded-xl bg-brand-500 px-4 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-brand-600">Tambah Bahan</a>
        <a href="{{ route('admin.stocks.movements') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Log Stok</a>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Bahan</th>
                        <th class="px-5 py-4">Stok awal</th>
                        <th class="px-5 py-4">Masuk</th>
                        <th class="px-5 py-4">Keluar</th>
                        <th class="px-5 py-4">Stok akhir</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach ($ingredients as $ing)
                        @php($status = $ing->stockStatus())
                        <tr>
                            <td class="px-5 py-4 font-semibold">{{ $ing->name }}</td>
                            <td class="px-5 py-4 text-zinc-800">{{ $ing->opening_stock }} {{ $ing->unit }}</td>
                            <td class="px-5 py-4 text-zinc-800">{{ $ing->stock_in ?? 0 }} {{ $ing->unit }}</td>
                            <td class="px-5 py-4 text-zinc-800">{{ $ing->stock_out ?? 0 }} {{ $ing->unit }}</td>
                            <td class="px-5 py-4 text-zinc-800">{{ $ing->current_stock }} {{ $ing->unit }}</td>
                            <td class="px-5 py-4">
                                <span class="badge {{ $status === 'aman' ? 'bg-emerald-50 text-emerald-700' : ($status === 'menipis' ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.stocks.movement.create', $ing) }}" class="rounded-lg bg-brand-500 px-3 py-2 text-xs font-semibold text-white hover:bg-brand-600">Update stok</a>
                                <a href="{{ route('admin.stocks.edit', $ing) }}" class="ml-2 rounded-lg bg-zinc-100 px-3 py-2 text-xs font-semibold hover:bg-zinc-200">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
