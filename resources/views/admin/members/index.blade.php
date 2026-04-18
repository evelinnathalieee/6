@extends('layouts.admin')

@section('title', 'Member — Westland Coffee')

@section('content')
    <x-page.title title="Member" subtitle="Daftar member + stamp + transaksi." />

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Member</th>
                        <th class="px-5 py-4">Kontak</th>
                        <th class="px-5 py-4">Transaksi</th>
                        <th class="px-5 py-4">Stamp</th>
                        <th class="px-5 py-4">Reward</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach ($members as $m)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="font-semibold">{{ $m->name }}</div>
                                <div class="mt-1 font-mono text-xs text-zinc-500">{{ $m->member_code }}</div>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div>{{ $m->email }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ $m->phone ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">{{ $m->transactions_count }}</td>
                            <td class="px-5 py-4 text-zinc-800">{{ $m->loyalty_stamps }}</td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="font-semibold">{{ $m->availableRewards($stampsPerReward) }}</div>
                                <div class="mt-1 text-xs text-zinc-500">Dipakai: {{ $m->loyalty_redeemed }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4">
            {{ $members->links() }}
        </div>
    </div>
@endsection
