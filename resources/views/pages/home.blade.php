@extends('layouts.public')

@section('title', 'Westland Coffee — Cut Nyak Dien, Pekanbaru')

@section('content')
    @php
        $testimonials = [
            ['name' => 'Raka', 'text' => 'Es Kopi Susu-nya enak dan nggak bikin eneg. Tempatnya nyaman buat nugas.'],
            ['name' => 'Nayla', 'text' => 'Matcha Latte favorit! Ordernya juga cepet, promo jelas.'],
            ['name' => 'Dimas', 'text' => 'Harga ramah kantong, rasanya konsisten. Cocok buat nongkrong sore.'],
        ];
    @endphp

    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-500 to-brand-600 p-8 text-white shadow-sm ring-1 ring-white/10 md:p-12">
        <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-black/10 blur-3xl"></div>

        <div class="relative grid gap-10 md:grid-cols-2 md:items-center">
            <div>
                <div class="chip">Cut Nyak Dien • Pekanbaru</div>
                <h1 class="mt-4 text-4xl font-black tracking-tight md:text-6xl">Westland Coffee</h1>
                <p class="mt-4 max-w-xl text-sm text-white/90 md:text-base">
                    Coffee spot malam di Cut Nyak Dien. Tempat nongkrong santai dengan kopi & non-kopi favorit.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('menu') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Lihat Menu</a>
                    <a href="{{ route('promos') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Promo</a>
                    @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Daftar</a>
                    @endguest
                </div>

                <div class="mt-7 grid max-w-xl grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
                        <div class="text-xs text-white/80">Loyalty</div>
                        <div class="mt-1 text-sm font-extrabold">Beli {{ $stampsPerReward }} gratis 1</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
                        <div class="text-xs text-white/80">Jam buka</div>
                        <div class="mt-1 text-sm font-extrabold">18.00–02.00</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15">
                        <div class="text-xs text-white/80">Order</div>
                        <div class="mt-1 text-sm font-extrabold">Dine in / Take away</div>
                    </div>
                </div>
            </div>

            <div class="grid gap-4">
                <div class="rounded-3xl bg-white p-6 text-zinc-900 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-sm font-extrabold">Menu unggulan</div>
                            <div class="mt-1 text-xs text-zinc-500">Favorit pelanggan.</div>
                        </div>
                        <a class="text-xs font-extrabold text-brand-700 hover:text-brand-800" href="{{ route('menu') }}">Semua →</a>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @forelse ($featuredMenu->take(4) as $item)
                            <a href="{{ route('menu') }}" class="rounded-2xl border border-zinc-200 bg-white p-4 hover:bg-zinc-50">
                                <div class="text-xs text-zinc-500">{{ $item->category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</div>
                                <div class="mt-1 font-extrabold">{{ $item->name }}</div>
                                <div class="mt-2 text-xs font-extrabold text-brand-700">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </a>
                        @empty
                            <div class="col-span-2 rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-4 text-sm text-zinc-600">
                                Menu segera hadir.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl bg-white/10 p-6 ring-1 ring-white/15">
                    <div class="text-sm font-extrabold">Kenapa Westland?</div>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                            <div class="text-xs text-white/80">Vibes nongkrong</div>
                            <div class="mt-1 text-sm font-extrabold">Santai & rame</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                            <div class="text-xs text-white/80">Buka malam</div>
                            <div class="mt-1 text-sm font-extrabold">18.00–02.00</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                            <div class="text-xs text-white/80">Menu favorit</div>
                            <div class="mt-1 text-sm font-extrabold">Kopi & non-kopi</div>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/10">
                            <div class="text-xs text-white/80">Loyalty</div>
                            <div class="mt-1 text-sm font-extrabold">Stamp otomatis</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-14" id="unggulan">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight">Menu Unggulan</h2>
                <p class="mt-1 text-sm text-zinc-600">Paling sering dibeli.</p>
            </div>
            <a class="text-sm font-extrabold text-brand-700 hover:text-brand-800" href="{{ route('menu') }}">Lihat Menu →</a>
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
                                    <a href="{{ route('login') }}" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Login</a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Login</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="card p-6 md:col-span-3">
                    <div class="text-sm text-zinc-600">Menu segera hadir.</div>
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-14" id="promo">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight">Promo</h2>
                <p class="mt-1 text-sm text-zinc-600">Yang bisa dipakai sekarang.</p>
            </div>
            <a class="text-sm font-extrabold text-brand-700 hover:text-brand-800" href="{{ route('promos') }}">Lihat Promo →</a>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            @forelse ($promos as $promo)
                <div class="card card-hover p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="text-lg font-extrabold">{{ $promo->name }}</div>
                        <span class="badge bg-emerald-50 text-emerald-700">aktif</span>
                    </div>
                    <div class="mt-2 text-sm text-zinc-600">{{ $promo->description }}</div>
                    <div class="mt-3 text-sm font-extrabold text-brand-700">
                        Diskon {{ $promo->discountLabel() }}
                        @if ((int) $promo->min_subtotal > 0)
                            <span class="text-xs font-semibold text-zinc-600">• Min Rp {{ number_format((int) $promo->min_subtotal, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    <div class="mt-4 text-xs text-zinc-500">
                        @if ($promo->starts_at) Mulai: {{ $promo->starts_at->format('d M Y') }} @endif
                        @if ($promo->ends_at) • Selesai: {{ $promo->ends_at->format('d M Y') }} @endif
                    </div>
                </div>
            @empty
                <div class="card p-6 text-sm text-zinc-600">
                    Belum ada promo.
                </div>
            @endforelse
        </div>
    </section>

    <section class="mt-14">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight">Testimoni</h2>
                <p class="mt-1 text-sm text-zinc-600">Dari yang pernah nongkrong.</p>
            </div>
        </div>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach ($testimonials as $t)
                <div class="card p-6">
                    <div class="flex items-center gap-1 text-amber-500">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.539 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.39 8.81c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="mt-3 text-sm text-zinc-700">“{{ $t['text'] }}”</div>
                    <div class="mt-4 text-sm font-extrabold text-zinc-900">{{ $t['name'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mt-14 overflow-hidden rounded-3xl bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 p-8 text-white shadow-sm ring-1 ring-white/10 md:p-10">
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <div class="chip">Member</div>
                <div class="mt-3 text-2xl font-black tracking-tight">Kumpulkan stamp, tukar reward.</div>
                <div class="mt-2 text-sm text-white/90">Setiap {{ $stampsPerReward }} stamp = 1 reward.</div>
            </div>
            <div class="flex gap-3">
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Daftar</a>
                @endguest
                <a href="{{ route('loyalty') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Detail</a>
            </div>
        </div>
    </section>
@endsection
