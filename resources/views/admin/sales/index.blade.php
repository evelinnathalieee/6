@extends('layouts.admin')

@section('title', 'Penjualan — Westland Coffee')

@section('content')
    <x-page.title title="Penjualan" subtitle="Transaksi harian dan ringkasannya." />

    <div class="mt-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <a href="{{ route('admin.sales.create') }}" class="rounded-xl bg-brand-500 px-4 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-brand-600">Catat Transaksi</a>
        <form class="flex items-center gap-2" method="GET" action="{{ route('admin.sales.index') }}">
            <input type="date" name="date" value="{{ request('date', $summary['date']->format('Y-m-d')) }}" class="rounded-xl border border-zinc-200 bg-white px-4 py-3 text-sm font-semibold outline-none focus:border-brand-500" />
            <button class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Filter</button>
        </form>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Tanggal</div>
            <div class="mt-2 text-lg font-extrabold">{{ $summary['date']->format('d M Y') }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Total penjualan</div>
            <div class="mt-2 text-lg font-extrabold text-brand-700">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Total transaksi</div>
            <div class="mt-2 text-lg font-extrabold">{{ $summary['total_transactions'] }}</div>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Waktu</th>
                        <th class="px-5 py-4">Order</th>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Pelanggan</th>
                        <th class="px-5 py-4">Item</th>
                        <th class="px-5 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($transactions as $trx)
                        <tr class="align-top">
                            <td class="px-5 py-4 text-zinc-800">{{ $trx->purchased_at->format('H:i') }}</td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $trx->order_number ?? '—' }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ $trx->order_type === 'take_away' ? 'Take away' : 'Dine in' }}</div>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-zinc-600">{{ $trx->transaction_code }}</td>
                            <td class="px-5 py-4 text-zinc-800">{{ $trx->user?->name ?? 'Walk-in' }}</td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="space-y-1">
                                    @foreach ($trx->items as $it)
                                        <div class="flex items-center justify-between gap-4">
                                            <div>{{ $it->menu_name_snapshot }}</div>
                                            <div class="text-xs text-zinc-500">x{{ $it->quantity }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="font-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</div>
                                @if ((int) $trx->promo_discount > 0)
                                    <div class="mt-1 text-xs font-semibold text-brand-700">Promo (-Rp {{ number_format((int) $trx->promo_discount, 0, ',', '.') }})</div>
                                @endif
                                @if ((int) $trx->reward_discount > 0)
                                    <div class="mt-1 text-xs font-semibold text-brand-700">Reward (-Rp {{ number_format((int) $trx->reward_discount, 0, ',', '.') }})</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-zinc-600">Belum ada transaksi pada tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection
