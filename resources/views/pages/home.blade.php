@extends('layouts.public')

@section('title', 'Westland Coffee — Cut Nyak Dien, Pekanbaru')

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-brand-600 p-8 text-white shadow-sm ring-1 ring-white/10 md:p-12">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-3xl"></div>

        <div class="relative grid gap-8 md:grid-cols-2 md:items-center">
            <div>
                <div class="chip">Nongkrong vibes • Pekanbaru</div>
                <h1 class="mt-4 text-4xl font-black tracking-tight md:text-6xl">Westland Coffee</h1>
                <p class="mt-4 max-w-xl text-sm text-white/90 md:text-base">
                    Kopi & non-kopi yang cocok buat nongkrong, nugas, dan ngobrol santai. Menu simple, rasa konsisten, promo jelas.
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('menu') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Belanja Menu</a>
                    <a href="{{ route('promos') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Lihat Promo</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Daftar</a>
                </div>

                <div class="mt-6 grid max-w-xl grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
                        <div class="text-xs text-white/80">Loyalty</div>
                        <div class="mt-1 text-sm font-extrabold">Beli 5 gratis 1</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
                        <div class="text-xs text-white/80">Fast Order</div>
                        <div class="mt-1 text-sm font-extrabold">Keranjang & CO</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
                        <div class="text-xs text-white/80">Promo</div>
                        <div class="mt-1 text-sm font-extrabold">Terlihat jelas</div>
                    </div>
                </div>
            </div>

            <div class="grid gap-3">
                <div class="rounded-3xl bg-white p-6 text-zinc-900 shadow-sm">
                    <div class="text-sm font-extrabold">Menu unggulan hari ini</div>
                    <div class="mt-1 text-xs text-zinc-500">Tambah ke keranjang, checkout, selesai.</div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach ($featuredMenu->take(4) as $item)
                            <a href="{{ route('menu') }}" class="rounded-2xl border border-zinc-200 bg-white p-4 hover:bg-zinc-50">
                                <div class="text-xs text-zinc-500">{{ $item->category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</div>
                                <div class="mt-1 font-extrabold">{{ $item->name }}</div>
                                <div class="mt-2 text-xs font-extrabold text-brand-700">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </a>
                        @endforeach
                        @if ($featuredMenu->count() === 0)
                            <div class="col-span-2 rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-4 text-sm text-zinc-600">
                                Belum ada menu. Admin bisa isi dari halaman Menu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-14" id="unggulan">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight">Menu Unggulan</h2>
                <p class="mt-1 text-sm text-zinc-600">Favorit yang sering dibeli pelanggan.</p>
            </div>
            <a class="text-sm font-semibold text-brand-600 hover:text-brand-700" href="{{ route('menu') }}">Lihat semua →</a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @forelse ($featuredMenu as $item)
                <div class="card card-hover overflow-hidden p-0">
                    @if ($item->imageSrc())
                        <img src="{{ $item->imageSrc() }}" alt="{{ $item->name }}" class="h-44 w-full object-cover" />
                    @else
                        <div class="h-44 w-full bg-gradient-to-br from-zinc-50 to-zinc-100"></div>
                    @endif
                    <div class="p-6">
                        <div class="text-xs font-extrabold text-zinc-500">{{ $item->category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</div>
                        <div class="mt-1 text-lg font-extrabold">{{ $item->name }}</div>
                        <div class="mt-2 text-sm text-zinc-600">{{ $item->description }}</div>
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <div class="inline-flex items-center rounded-lg bg-brand-50 px-3 py-2 text-sm font-extrabold text-brand-700">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>
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
            @empty
                <div class="card p-6 md:col-span-3">
                    <div class="text-sm text-zinc-600">Menu unggulan belum diisi. Admin bisa menambahkan menu lewat database.</div>
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-14" id="promo">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold tracking-tight">Promo</h2>
                <p class="mt-1 text-sm text-zinc-600">Promo yang sedang aktif.</p>
            </div>
            <a class="text-sm font-semibold text-brand-600 hover:text-brand-700" href="{{ route('promos') }}">Lihat promo →</a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            @forelse ($promos as $promo)
                <div class="card card-hover p-6">
                    <div class="text-lg font-extrabold">{{ $promo->name }}</div>
                    <div class="mt-2 text-sm text-zinc-600">{{ $promo->description }}</div>
                    <div class="mt-4 text-xs text-zinc-500">
                        @if ($promo->starts_at) Mulai: {{ $promo->starts_at->format('d M Y') }} @endif
                        @if ($promo->ends_at) • Selesai: {{ $promo->ends_at->format('d M Y') }} @endif
                    </div>
                </div>
            @empty
                <div class="card p-6 text-sm text-zinc-600">
                    Belum ada promo aktif.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-14 overflow-hidden rounded-3xl bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 p-8 text-white shadow-sm ring-1 ring-white/10 md:p-10">
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <div class="chip">Program Member</div>
                <div class="mt-3 text-2xl font-black tracking-tight">Kumpulkan stamp, tukar reward.</div>
                <div class="mt-2 text-sm text-white/90">Belanja pakai akun member → stamp otomatis masuk.</div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Daftar</a>
                <a href="{{ route('loyalty') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Detail Benefit</a>
            </div>
        </div>
    </section>
@endsection
