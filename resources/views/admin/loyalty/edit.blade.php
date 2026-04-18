@extends('layouts.admin')

@section('title', 'Pengaturan Loyalty — Westland Coffee')

@section('content')
    <x-page.title title="Pengaturan Loyalty" subtitle="Admin bisa atur stamp dan label reward." />

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.loyalty.redemptions') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Riwayat penukaran</a>
    </div>

    <form method="POST" action="{{ route('admin.loyalty.update') }}" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf
        @method('PUT')

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-sm text-zinc-700">Stamp per reward</label>
                <input name="stamps_per_reward" value="{{ old('stamps_per_reward', $stampsPerReward) }}" class="input" />
                @error('stamps_per_reward') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                <div class="mt-1 text-xs text-zinc-500">Contoh: 5 = beli 5 minuman, dapat 1 reward.</div>
            </div>

            <div>
                <label class="text-sm text-zinc-700">Label reward</label>
                <input name="reward_label" value="{{ old('reward_label', $rewardLabel) }}" class="input" />
                @error('reward_label') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                <div class="mt-1 text-xs text-zinc-500">Ditampilkan di riwayat penukaran.</div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
@endsection
