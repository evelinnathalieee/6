@extends('layouts.member')

@section('title', 'Riwayat Transaksi — Westland Coffee')

@section('content')
    <x-page.title title="Riwayat Transaksi" subtitle="Semua transaksi checkout member akan tampil di sini." />

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Order</th>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Item</th>
                        <th class="px-5 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($transactions as $trx)
                        <tr class="align-top">
                            <td class="px-5 py-4 text-zinc-800">{{ $trx->purchased_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $trx->order_number ?? '—' }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ $trx->order_type === 'take_away' ? 'Take away' : 'Dine in' }}</div>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-zinc-600">{{ $trx->transaction_code }}</td>
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
                                    <div class="mt-1 text-xs font-semibold text-brand-700">
                                        Promo {{ $trx->promo_name_snapshot ?? '' }} (-Rp {{ number_format((int) $trx->promo_discount, 0, ',', '.') }})
                                    </div>
                                @endif
                                @if ((int) $trx->reward_discount > 0)
                                    <div class="mt-1 text-xs font-semibold text-brand-700">
                                        Reward (-Rp {{ number_format((int) $trx->reward_discount, 0, ',', '.') }})
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-600">Belum ada transaksi.</td>
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
