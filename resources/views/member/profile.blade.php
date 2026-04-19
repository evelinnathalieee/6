@extends('layouts.member')

@section('title', 'Profil Member — Westland Coffee')

@section('content')
    <x-page.title title="Profil" subtitle="Stamp, reward, dan akun member." />

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)] md:col-span-2">
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-4">
                    @if ($user->avatarSrc())
                        <img src="{{ $user->avatarSrc() }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-2xl object-cover ring-1 ring-zinc-200" />
                    @else
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-50 text-2xl font-black text-brand-700 ring-1 ring-brand-100">
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="text-sm text-zinc-600">Nama</div>
                        <div class="mt-1 text-xl font-extrabold">{{ $user->name }}</div>
                    </div>
                </div>
                <a href="{{ route('member.profile.edit') }}" class="rounded-xl bg-white px-4 py-2 text-xs font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Edit</a>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="text-xs text-zinc-500">Member Code</div>
                    <div class="mt-1 font-mono text-sm">{{ $user->member_code }}</div>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="text-xs text-zinc-500">Email</div>
                    <div class="mt-1 text-sm">{{ $user->email }}</div>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-5">
                    <div class="text-xs text-zinc-500">No. HP</div>
                    <div class="mt-1 text-sm">{{ $user->phone ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-b from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Loyalty</div>
            <div class="mt-2 text-4xl font-semibold tracking-tight">{{ $user->loyalty_stamps }}</div>
            <div class="mt-1 text-sm text-white/90">1 minuman = 1 stamp</div>

            <div class="mt-5 rounded-2xl bg-white/95 p-4 text-zinc-900 shadow-sm ring-1 ring-white/30">
                <div class="text-xs text-zinc-500">Reward tersedia</div>
                <div class="mt-1 text-lg font-extrabold">{{ $availableRewards }}</div>
                <div class="mt-1 text-xs text-zinc-500">Setiap {{ $stampsPerReward }} stamp = 1 reward</div>
            </div>
        </div>
    </div>

@endsection
