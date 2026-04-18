@extends('layouts.admin')

@section('title', 'Order Online — Westland Coffee')

@section('content')
    <x-page.title title="Order Online" subtitle="Approval pembayaran pesanan dari member." />
    @include('partials.admin.sales-tabs')

    <div class="mt-4 flex items-center justify-end">
        <form class="flex items-center gap-2" method="GET" action="{{ route('admin.orders.index') }}">
            <input type="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="rounded-xl border border-zinc-200 bg-white px-4 py-3 text-sm font-semibold outline-none focus:border-brand-500" />
            <button class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Filter</button>
        </form>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Menunggu approval</div>
            <div class="mt-2 text-lg font-extrabold">{{ $pendingOrders->count() }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Selesai hari ini</div>
            <div class="mt-2 text-lg font-extrabold">{{ $processedOrders->where('payment_status', \App\Models\Transaction::PAYMENT_PAID)->count() }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs font-extrabold text-zinc-500">Dibatalkan hari ini</div>
            <div class="mt-2 text-lg font-extrabold">{{ $processedOrders->where('payment_status', \App\Models\Transaction::PAYMENT_CANCELED)->count() }}</div>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="border-b border-zinc-200 bg-zinc-50 px-6 py-4">
            <div class="text-sm font-extrabold text-zinc-900">Perlu Diproses</div>
            <div class="mt-1 text-xs text-zinc-500">Semua pesanan member yang belum dikonfirmasi pembayarannya.</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Member</th>
                        <th class="px-6 py-4">Item</th>
                        <th class="px-6 py-4">Bayar</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($pendingOrders as $trx)
                        <tr class="align-top">
                            <td class="px-6 py-4 text-zinc-800">{{ $trx->purchased_at?->format('H:i') }}</td>
                            <td class="px-6 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $trx->order_number ?? '—' }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ $trx->order_type === 'take_away' ? 'Take away' : 'Dine in' }}</div>
                            </td>
                            <td class="px-6 py-4 text-zinc-800">{{ $trx->user?->name ?? 'Member' }}</td>
                            <td class="px-6 py-4 text-zinc-800">{{ (int) $trx->items->sum('quantity') }} item</td>
                            <td class="px-6 py-4 text-zinc-800">{{ $trx->payment_method === 'qris' ? 'QRIS' : 'Cash' }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((int) $trx->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.sales.show', $trx) }}" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Acc</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-zinc-600">Tidak ada order online yang menunggu approval.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="border-b border-zinc-200 bg-zinc-50 px-6 py-4">
            <div class="text-sm font-extrabold text-zinc-900">Aktivitas Hari Ini</div>
            <div class="mt-1 text-xs text-zinc-500">Order online yang sudah selesai atau dibatalkan.</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Member</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($processedOrders as $trx)
                        <tr>
                            <td class="px-6 py-4 text-zinc-800">{{ $trx->purchased_at?->format('H:i') }}</td>
                            <td class="px-6 py-4 text-zinc-800">{{ $trx->order_number ?? '—' }}</td>
                            <td class="px-6 py-4 text-zinc-800">{{ $trx->user?->name ?? 'Member' }}</td>
                            <td class="px-6 py-4">
                                @php($status = $trx->payment_status)
                                <span class="badge {{ $status === \App\Models\Transaction::PAYMENT_PAID ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $status === \App\Models\Transaction::PAYMENT_PAID ? 'paid' : 'canceled' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((int) $trx->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-zinc-600">Belum ada aktivitas order online pada tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
