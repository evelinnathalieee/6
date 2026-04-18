@extends(auth()->check() && auth()->user()->isMember() ? 'layouts.member' : 'layouts.public')

@section('title', 'Menu — Westland Coffee')

@section('content')
    <x-page.title title="Menu" subtitle="Pilih kopi atau non-kopi, tambah ke keranjang, lalu checkout." />

    @auth
        @if (auth()->user()->isMember())
            <div class="mt-6 rounded-3xl border border-brand-200 bg-white p-5 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="text-sm text-zinc-700">
                        Login sebagai <span class="font-extrabold text-zinc-900">{{ auth()->user()->name }}</span> • Stamp:
                        <span class="font-extrabold text-brand-700">{{ auth()->user()->loyalty_stamps }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('member.profile') }}" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Dashboard Member</a>
                        <a href="{{ route('member.rewards') }}" class="rounded-xl bg-white px-4 py-2 text-xs font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Reward</a>
                        <a href="{{ route('member.transactions') }}" class="rounded-xl bg-white px-4 py-2 text-xs font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Riwayat</a>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <div class="mt-8 grid gap-8">
        @forelse ($menuByCategory as $category => $items)
            <section class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
                <div class="flex items-end justify-between gap-3">
                    <h2 class="text-lg font-extrabold">{{ $category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</h2>
                    <div class="text-xs text-zinc-500">{{ $items->count() }} item</div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    @foreach ($items as $item)
                        <div class="card card-hover overflow-hidden p-0">
                            @if ($item->imageSrc())
                                <img src="{{ $item->imageSrc() }}" alt="{{ $item->name }}" class="h-44 w-full object-cover" />
                            @else
                                <div class="h-44 w-full bg-gradient-to-br from-zinc-50 to-zinc-100"></div>
                            @endif
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="text-base font-extrabold">{{ $item->name }}</div>
                                    @if ($item->is_featured)
                                        <div class="badge bg-brand-50 text-brand-700">unggulan</div>
                                    @endif
                                </div>
                                <div class="mt-2 text-sm text-zinc-600">{{ $item->description }}</div>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <div class="text-sm font-extrabold text-brand-700">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    @auth
                                        @if (auth()->user()->isMember())
                                            <form method="POST" action="{{ route('cart.add', $item) }}">
                                                @csrf
                                                <button class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">+ Keranjang</button>
                                            </form>
                                        @else
                                            <span class="text-xs font-semibold text-zinc-500">Login sebagai member</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Login dulu</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 text-sm text-zinc-600">
                Menu belum tersedia. Silakan isi data menu di database terlebih dahulu.
            </div>
        @endforelse
    </div>

    @guest
        <div class="mt-10 overflow-hidden rounded-3xl bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Member</div>
            <div class="mt-3 text-xl font-black">Dapatkan stamp otomatis</div>
            <div class="mt-2 text-sm text-white/90">Login/daftar member supaya riwayat transaksi dan reward tercatat.</div>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Daftar</a>
                <a href="{{ route('loyalty') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Detail Program</a>
            </div>
        </div>
    @endguest
@endsection
