@php
    $cartCount = array_sum(session('cart', []));
@endphp

<header class="sticky top-0 z-50 border-b border-white/15 bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 text-white shadow-sm">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-4 md:py-5">
        <a href="{{ route('home') }}" class="group flex items-center gap-2">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/95 text-brand-700 shadow-sm ring-1 ring-white/30 md:h-11 md:w-11">
                W
            </span>
            <div class="leading-tight">
                <div class="text-base font-extrabold tracking-tight md:text-lg">{{ config('app.name', 'Westland Coffee') }}</div>
                <div class="text-xs text-white/80">Pekanbaru • Cut Nyak Dien</div>
            </div>
        </a>

        <nav class="hidden items-center gap-6 text-base font-extrabold text-white/90 md:flex">
            <a class="{{ request()->routeIs('home') ? 'text-white' : 'hover:text-white' }}" href="{{ route('home') }}">Home</a>
            <a class="{{ request()->routeIs('menu') ? 'text-white' : 'hover:text-white' }}" href="{{ route('menu') }}">Menu</a>
            <a class="{{ request()->routeIs('promos') ? 'text-white' : 'hover:text-white' }}" href="{{ route('promos') }}">Promo</a>
            <a class="{{ request()->routeIs('loyalty') ? 'text-white' : 'hover:text-white' }}" href="{{ route('loyalty') }}">Member</a>
        </nav>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('cart.show') }}" class="relative inline-flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white md:text-base">
                    <span>Keranjang</span>
                    @if ($cartCount > 0)
                        <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-700 px-1 text-[11px] font-black text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <details class="relative">
                    <summary class="cursor-pointer list-none rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20 md:text-base">
                        Akun
                    </summary>
                    <div class="absolute right-0 mt-2 w-60 overflow-hidden rounded-2xl border border-zinc-200 bg-white text-zinc-900 shadow-xl">
                        <div class="px-4 py-3">
                            <div class="text-xs text-zinc-500">Login sebagai</div>
                            <div class="mt-0.5 text-sm font-semibold">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="border-t border-zinc-200">
                            @if (auth()->user()->isMember())
                                <a href="{{ route('member.profile') }}" class="block px-4 py-2 text-sm hover:bg-zinc-50">Profil</a>
                                <a href="{{ route('member.transactions') }}" class="block px-4 py-2 text-sm hover:bg-zinc-50">Riwayat</a>
                                <a href="{{ route('member.rewards') }}" class="block px-4 py-2 text-sm hover:bg-zinc-50">Reward</a>
                                <div class="border-t border-zinc-200">
                                    <form method="POST" action="{{ route('member.logout') }}">
                                        @csrf
                                        <button class="w-full px-4 py-2 text-left text-sm font-semibold text-rose-700 hover:bg-rose-50">Logout</button>
                                    </form>
                                </div>
                            @elseif (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-zinc-50">Dashboard Admin</a>
                                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm hover:bg-zinc-50">Penjualan</a>
                                <div class="border-t border-zinc-200">
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button class="w-full px-4 py-2 text-left text-sm font-semibold text-rose-700 hover:bg-rose-50">Logout</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white md:text-base">
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20 md:text-base">
                    <span>Daftar</span>
                </a>
            @endauth
        </div>
    </div>
</header>
