@extends('layouts.member')

@section('title', 'Reward — Westland Coffee')

@section('content')
    <x-page.title title="Reward" subtitle="Tukar reward berdasarkan stamp." />

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)] md:col-span-2">
            <div class="text-lg font-extrabold">Aturan</div>
            <div class="mt-2 text-sm text-zinc-600">
                Beli {{ $stampsPerReward }} minuman, dapat 1 reward (gratis 1 minuman).
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="text-xs text-zinc-500">Stamp kamu</div>
                    <div class="mt-1 text-2xl font-extrabold">{{ $user->loyalty_stamps }}</div>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="text-xs text-zinc-500">Reward tersedia</div>
                    <div class="mt-1 text-2xl font-extrabold">{{ $availableRewards }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-b from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Status</div>
            <div class="mt-3 text-sm text-white/90">
                @if ($availableRewards > 0)
                    Kamu bisa pakai reward langsung saat checkout (tanpa perlu klaim ke kasir).
                @else
                    Kumpulkan stamp lagi untuk unlock reward.
                @endif
            </div>

            <div class="mt-6 rounded-2xl bg-white/95 p-4 text-xs text-zinc-800 shadow-sm ring-1 ring-white/30">
                Total reward dipakai: <span class="font-extrabold text-zinc-900">{{ $user->loyalty_redeemed }}</span>
            </div>
        </div>
    </div>

    <div class="mt-10 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="border-b border-zinc-200 bg-zinc-50 px-6 py-4">
            <div class="text-sm font-extrabold text-zinc-900">Riwayat penukaran reward</div>
            <div class="mt-1 text-xs text-zinc-500">Setiap kali kamu pakai reward saat checkout, tercatat di sini.</div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Reward</th>
                        <th class="px-6 py-4 text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($redemptions as $r)
                        <tr>
                            <td class="px-6 py-4 text-zinc-800">{{ $r->redeemed_at?->format('d M Y H:i') ?? '—' }}</td>
                            <td class="px-6 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $r->transaction?->order_number ?? '—' }}</div>
                                <div class="mt-1 font-mono text-xs text-zinc-500">{{ $r->transaction?->transaction_code ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-zinc-800">{{ $r->reward_label }}</td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format((int) $r->reward_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-sm text-zinc-600">Belum ada penukaran reward.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-6 py-4">
            {{ $redemptions->links() }}
        </div>
    </div>
@endsection
