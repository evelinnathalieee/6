@extends('layouts.admin')

@php
    $isOnline = $transaction->sales_channel === \App\Models\Transaction::CHANNEL_ONLINE;
    $activeSalesTab = $isOnline
        ? ($transaction->payment_status === \App\Models\Transaction::PAYMENT_PENDING ? 'orders' : 'history_online')
        : 'history_pos';
@endphp

@section('title', ($isOnline ? 'Order Online' : 'Detail Kasir').' — Westland Coffee')

@section('content')
    <x-page.title :title="$isOnline ? 'Order Online' : 'Detail Kasir'" :subtitle="$isOnline ? 'Bayar dan konfirmasi order member di kasir dari sini.' : 'Detail transaksi kasir.'" />
    @include('partials.admin.sales-tabs')

    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ $transaction->payment_status === 'pending' && $isOnline ? route('admin.orders.index') : route('admin.sales.index', ['channel' => $isOnline ? \App\Models\Transaction::CHANNEL_ONLINE : \App\Models\Transaction::CHANNEL_POS]) }}" class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Kembali</a>
        <div class="flex items-center gap-2">
            @php($status = $transaction->payment_status)
            <span class="badge {{ $status === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($status === 'canceled' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-800') }}">
                {{ $status === 'paid' ? 'paid' : ($status === 'canceled' ? 'canceled' : 'pending') }}
            </span>
            <span class="badge bg-zinc-100 text-zinc-700">{{ $isOnline ? 'online' : 'kasir' }}</span>
            <span class="badge bg-zinc-100 text-zinc-700">{{ $transaction->payment_method === 'qris' ? 'QRIS' : 'Cash' }}</span>
        </div>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="card p-6 md:col-span-2">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="text-xs text-zinc-500">Order</div>
                    <div class="mt-1 text-xl font-extrabold">{{ $transaction->order_number ?? '—' }}</div>
                    <div class="mt-1 text-sm text-zinc-600">{{ $transaction->order_type === 'take_away' ? 'Take away' : 'Dine in' }}</div>
                </div>
                <div class="text-right">
                    <div class="text-xs text-zinc-500">Waktu</div>
                    <div class="mt-1 text-sm font-semibold">{{ $transaction->purchased_at?->format('d M Y H:i') }}</div>
                </div>
            </div>

            <div class="mt-5 rounded-2xl border border-zinc-200">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-5 py-4">Item</th>
                            <th class="px-5 py-4 text-right">Harga</th>
                            <th class="px-5 py-4 text-right">Qty</th>
                            <th class="px-5 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @foreach ($transaction->items as $it)
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-zinc-900">{{ $it->menu_name_snapshot }}</div>
                                </td>
                                <td class="px-5 py-4 text-right text-zinc-800">Rp {{ number_format((int) $it->unit_price_snapshot, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right text-zinc-800">{{ $it->quantity }}</td>
                                <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format((int) $it->line_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-b from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Ringkasan</div>

            <div class="mt-5 space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-white/85">Subtotal</span>
                    <span class="font-extrabold">Rp {{ number_format((int) $transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                @if ((int) $transaction->promo_discount > 0)
                    <div class="flex items-center justify-between text-white/90">
                        <span>Promo</span>
                        <span class="font-extrabold">-Rp {{ number_format((int) $transaction->promo_discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ((int) $transaction->reward_discount > 0)
                    <div class="flex items-center justify-between text-white/90">
                        <span>Reward</span>
                        <span class="font-extrabold">-Rp {{ number_format((int) $transaction->reward_discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex items-center justify-between border-t border-white/15 pt-2">
                    <span class="text-white/90">Total</span>
                    <span class="text-xl font-black">Rp {{ number_format((int) $transaction->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 rounded-2xl bg-white/95 p-4 text-zinc-900 shadow-sm ring-1 ring-white/30">
                <div class="text-xs text-zinc-500">Pelanggan</div>
                <div class="mt-1 text-sm font-extrabold">{{ $transaction->user?->name ?? 'Pelanggan kasir' }}</div>
            </div>

            @if ($transaction->payment_status === 'pending')
                <form method="POST" action="{{ route('admin.sales.pay', $transaction) }}" class="mt-6 space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs font-extrabold text-white/90">Metode bayar</label>
                        <select name="payment_method" class="mt-1 w-full rounded-xl border border-white/20 bg-white/95 px-4 py-3 text-sm font-semibold text-zinc-900 outline-none focus:border-white" required>
                            <option value="cash" {{ $transaction->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="qris" {{ $transaction->payment_method === 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>

                    @if ($transaction->user)
                        <label class="inline-flex items-center gap-2 text-sm font-extrabold text-white/95">
                            <input type="checkbox" name="use_reward" value="1" class="h-5 w-5 rounded border-white/30 text-brand-600 focus:ring-white">
                            Pakai 1 reward (jika tersedia)
                        </label>
                    @endif

                    <button class="btn-primary w-full">Bayar</button>
                </form>

                <form method="POST" action="{{ route('admin.sales.cancel', $transaction) }}" class="mt-3">
                    @csrf
                    <button class="w-full rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Batalkan</button>
                </form>
            @endif
        </div>
    </div>
@endsection
