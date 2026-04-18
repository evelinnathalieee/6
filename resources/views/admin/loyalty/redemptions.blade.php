@extends('layouts.admin')

@section('title', 'Riwayat Penukaran Reward — Westland Coffee')

@section('content')
    <x-page.title title="Riwayat Penukaran" subtitle="Semua pemakaian reward oleh member." />

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.loyalty.edit') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Pengaturan loyalty</a>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Waktu</th>
                        <th class="px-5 py-4">Member</th>
                        <th class="px-5 py-4">Order</th>
                        <th class="px-5 py-4">Reward</th>
                        <th class="px-5 py-4 text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($redemptions as $r)
                        <tr class="align-top">
                            <td class="px-5 py-4 text-zinc-800">{{ $r->redeemed_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $r->user?->name ?? '—' }}</div>
                                <div class="mt-1 font-mono text-xs text-zinc-500">{{ $r->user?->member_code ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $r->transaction?->order_number ?? '—' }}</div>
                                <div class="mt-1 font-mono text-xs text-zinc-500">{{ $r->transaction?->transaction_code ?? '' }}</div>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $r->reward_label }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ $r->note }}</div>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format((int) $r->reward_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-600">Belum ada penukaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4">
            {{ $redemptions->links() }}
        </div>
    </div>
@endsection

