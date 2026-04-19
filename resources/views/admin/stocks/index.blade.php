@extends('layouts.admin')

@section('title', 'Manajemen Stok — Westland Coffee')

@section('content')
    <x-page.title title="Stok" subtitle="Cek stok cepat, lalu update seperlunya." />

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.stocks.create') }}" class="rounded-xl bg-brand-500 px-4 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-brand-600">Tambah Bahan</a>
        <a href="{{ route('admin.stocks.movements') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Log Stok</a>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-4">
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Total bahan</div>
            <div class="mt-2 text-2xl font-black text-zinc-900">{{ $summary['total_items'] }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Aman</div>
            <div class="mt-2 text-2xl font-black text-emerald-600">{{ $summary['safe_items'] }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Menipis</div>
            <div class="mt-2 text-2xl font-black text-amber-600">{{ $summary['low_items'] }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Habis</div>
            <div class="mt-2 text-2xl font-black text-rose-600">{{ $summary['empty_items'] }}</div>
        </div>
    </div>

    <div class="mt-8 grid gap-4 xl:grid-cols-[minmax(0,2fr)_360px]">
        <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <div class="flex items-center justify-between gap-3 border-b border-zinc-200 bg-zinc-50 px-5 py-4">
                <div>
                    <div class="text-sm font-extrabold text-zinc-900">Daftar Bahan</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-5 py-4">Bahan</th>
                            <th class="px-5 py-4">Stok sekarang</th>
                            <th class="px-5 py-4">Batas</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Kelola</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @forelse ($ingredients as $ing)
                            @php($status = $ing->stockStatus())
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-zinc-900">{{ $ing->name }}</div>
                                    <div class="mt-1 text-xs text-zinc-500">
                                        Awal {{ $ing->formatStock((float) $ing->opening_stock) }} {{ $ing->unit }}
                                        • Masuk {{ $ing->formatStock((float) ($ing->stock_in ?? 0)) }}
                                        • Keluar {{ $ing->formatStock((float) ($ing->stock_out ?? 0)) }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-zinc-900">
                                    <div class="font-extrabold">{{ $ing->formatStock((float) $ing->current_stock) }}</div>
                                    <div class="text-xs text-zinc-500">{{ $ing->unit }}</div>
                                </td>
                                <td class="px-5 py-4 text-zinc-800">{{ $ing->formatStock((float) $ing->low_stock_threshold) }} {{ $ing->unit }}</td>
                                <td class="px-5 py-4">
                                    <span class="badge {{ $status === 'aman' ? 'bg-emerald-50 text-emerald-700' : ($status === 'menipis' ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-700') }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2 whitespace-nowrap">
                                        <a href="{{ route('admin.stocks.movement.create', $ing) }}" class="inline-flex items-center rounded-xl bg-brand-500 px-3.5 py-2 text-xs font-extrabold text-white shadow-sm">
                                            Update stok
                                        </a>
                                        <a href="{{ route('admin.stocks.edit', $ing) }}" class="inline-flex items-center rounded-xl border border-zinc-200 bg-white px-3.5 py-2 text-xs font-extrabold text-zinc-700 shadow-sm">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-600">Belum ada bahan baku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="text-sm font-extrabold text-zinc-900">Perlu perhatian</div>
                <div class="mt-4 space-y-3">
                    @php($alertItems = $ingredients->filter(fn ($ingredient) => $ingredient->stockStatus() !== 'aman')->values())
                    @forelse ($alertItems as $ing)
                        @php($status = $ing->stockStatus())
                        <div class="rounded-2xl border border-zinc-200 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="font-semibold text-zinc-900">{{ $ing->name }}</div>
                                    <div class="mt-1 text-xs text-zinc-500">{{ $ing->formatStock((float) $ing->current_stock) }} {{ $ing->unit }} tersisa</div>
                                </div>
                                <span class="badge {{ $status === 'menipis' ? 'bg-amber-50 text-amber-800' : 'bg-rose-50 text-rose-700' }}">{{ $status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                            Semua stok aman.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-zinc-200 bg-white p-5 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm font-extrabold text-zinc-900">Update Terakhir</div>
                    <a href="{{ route('admin.stocks.movements') }}" class="text-xs font-extrabold text-brand-700 hover:text-brand-800">Lihat semua</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($recentMovements as $movement)
                        <div class="rounded-2xl border border-zinc-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="font-semibold text-zinc-900">{{ $movement->ingredient?->name ?? '-' }}</div>
                                <span class="badge {{ $movement->type === 'in' ? 'bg-emerald-50 text-emerald-700' : ($movement->type === 'out' ? 'bg-amber-50 text-amber-800' : 'bg-zinc-100 text-zinc-800') }}">
                                    {{ $movement->type === 'in' ? 'masuk' : ($movement->type === 'out' ? 'keluar' : 'set stok') }}
                                </span>
                            </div>
                            <div class="mt-1 text-sm text-zinc-700">
                                {{ $movement->formatQuantity() }} {{ $movement->ingredient?->unit ?? '' }}
                            </div>
                            <div class="mt-1 text-xs text-zinc-500">{{ $movement->moved_at?->format('d M Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-zinc-200 px-4 py-3 text-sm text-zinc-600">Belum ada update stok.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
