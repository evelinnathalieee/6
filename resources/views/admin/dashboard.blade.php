@extends('layouts.admin')

@section('title', 'Dashboard Admin — Westland Coffee')

@section('content')
    <x-page.title title="Dashboard" subtitle="Ringkasan penjualan, stok, dan notifikasi transaksi." />

    <div class="mt-8 grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-brand-200 bg-white p-5">
            <div class="text-xs text-brand-700">Total penjualan hari ini</div>
            <div class="mt-2 text-xl font-semibold">Rp {{ number_format($todaySales, 0, ',', '.') }}</div>
        </div>
        <div class="rounded-2xl border border-brand-200 bg-white p-5">
            <div class="text-xs text-brand-700">Total transaksi hari ini</div>
            <div class="mt-2 text-xl font-semibold">{{ $todayTransactions }}</div>
        </div>
        <div class="rounded-2xl border border-brand-200 bg-white p-5">
            <div class="text-xs text-brand-700">Produk terlaris hari ini</div>
            <div class="mt-2 text-base font-semibold">
                {{ $bestSeller?->menu_name_snapshot ?? '—' }}
            </div>
            <div class="mt-1 text-xs text-zinc-500">{{ $bestSeller?->qty ? 'Qty: '.$bestSeller->qty : '' }}</div>
        </div>
        <div class="rounded-2xl border border-brand-200 bg-white p-5">
            <div class="text-xs text-brand-700">Jumlah member</div>
            <div class="mt-2 text-xl font-semibold">{{ $membersCount }}</div>
        </div>
    </div>

    <div class="mt-10 grid gap-4 md:grid-cols-2">
        <div class="rounded-3xl border border-brand-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <div class="flex items-center justify-between gap-3">
                <div class="text-lg font-semibold">Notifikasi stok</div>
                <a href="{{ route('admin.stocks.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Kelola →</a>
            </div>
            <div class="mt-4 space-y-2">
                @forelse ($lowStock as $i)
                    @php($status = $i->stockStatus())
                    <div class="flex items-center justify-between rounded-2xl border border-zinc-200 bg-white px-4 py-3">
                        <div>
                            <div class="font-semibold">{{ $i->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $i->current_stock }} {{ $i->unit }} • threshold {{ $i->low_stock_threshold }}</div>
                        </div>
                        <div class="badge {{ $status === 'habis' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-800' }}">
                            {{ $status }}
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-zinc-200 bg-white px-4 py-3 text-sm text-zinc-600">
                        Semua stok aman.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-3xl border border-brand-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <div class="text-lg font-semibold">Akses cepat</div>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                <a href="{{ route('admin.orders.index') }}" class="rounded-2xl border border-zinc-200 bg-white p-5 hover:bg-zinc-50">
                    <div class="text-sm font-semibold">Penjualan</div>
                    <div class="mt-1 text-xs text-zinc-500">Order online, kasir, dan riwayat</div>
                </a>
                <a href="{{ route('admin.promos.index') }}" class="rounded-2xl border border-zinc-200 bg-white p-5 hover:bg-zinc-50">
                    <div class="text-sm font-semibold">Promo</div>
                    <div class="mt-1 text-xs text-zinc-500">Kelola promo aktif/nonaktif</div>
                </a>
                <a href="{{ route('admin.stocks.movements') }}" class="rounded-2xl border border-zinc-200 bg-white p-5 hover:bg-zinc-50">
                    <div class="text-sm font-semibold">Log Stok</div>
                    <div class="mt-1 text-xs text-zinc-500">Riwayat stok masuk/keluar</div>
                </a>
                <a href="{{ route('admin.members.index') }}" class="rounded-2xl border border-zinc-200 bg-white p-5 hover:bg-zinc-50">
                    <div class="text-sm font-semibold">Member</div>
                    <div class="mt-1 text-xs text-zinc-500">Data pelanggan & stamp</div>
                </a>
            </div>

            <div class="mt-6 rounded-2xl border border-zinc-200 bg-white p-5 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="flex items-center justify-between gap-3">
                    <div class="text-sm font-semibold">Notifikasi transaksi (live)</div>
                    <div id="liveStatus" class="text-xs text-zinc-500">menghubungkan…</div>
                </div>
                <div class="mt-3 text-sm text-zinc-700">
                    <div>Transaksi terbaru: <span class="font-mono" id="latestCode">—</span></div>
                    <div class="mt-1 text-xs text-zinc-500" id="latestMeta">—</div>
                </div>
            </div>
        </div>
    </div>

@endsection
