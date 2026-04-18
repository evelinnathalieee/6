@extends(auth()->check() && auth()->user()->isMember() ? 'layouts.member' : 'layouts.public')

@section('title', 'Program Member — Westland Coffee')

@section('content')
    <div class="rounded-3xl border border-zinc-200 bg-white p-8">
        <h1 class="text-2xl font-semibold tracking-tight">Program Member</h1>
        <p class="mt-2 text-sm text-zinc-600">
            Program loyalitas sederhana: kumpulkan stamp dari transaksi, lalu tukar reward.
        </p>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="text-sm font-semibold text-brand-600">Mudah daftar</div>
                <div class="mt-2 text-sm text-zinc-600">Daftar pakai nama + email. Bisa langsung login.</div>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="text-sm font-semibold text-brand-600">Stamp otomatis</div>
                <div class="mt-2 text-sm text-zinc-600">1 minuman = 1 stamp. Total stamp terlihat di profil.</div>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
                <div class="text-sm font-semibold text-brand-600">Reward</div>
                <div class="mt-2 text-sm text-zinc-600">Setiap {{ $stampsPerReward }} stamp bisa ditukar 1 reward (gratis 1 minuman).</div>
            </div>
        </div>

        @guest
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                <a href="{{ route('login') }}" class="btn-secondary">Login</a>
            </div>
        @endguest
    </div>
@endsection
