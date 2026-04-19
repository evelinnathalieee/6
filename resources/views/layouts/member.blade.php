<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Member - Westland Coffee')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @include('partials.vite')
</head>
<body class="min-h-screen bg-brand-50 text-zinc-900">
    <header class="sticky top-0 z-40 border-b border-white/15 bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 text-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-4 md:py-5">
            <a href="{{ route('member.profile') }}" class="flex items-center gap-2">
                @if (auth()->user()?->avatarSrc())
                    <img src="{{ auth()->user()->avatarSrc() }}" alt="{{ auth()->user()->name }}" class="h-10 w-10 rounded-2xl object-cover bg-white/95 shadow-sm ring-1 ring-white/30 md:h-11 md:w-11" />
                @else
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/95 text-sm font-black text-brand-700 shadow-sm ring-1 ring-white/30 md:h-11 md:w-11 md:text-base">
                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name ?? 'M', 0, 1)) }}
                    </span>
                @endif
                <div class="leading-tight">
                    <div class="text-base font-extrabold tracking-tight md:text-lg">{{ auth()->user()->name ?? 'Member' }}</div>
                    <div class="text-xs text-white/80">Member</div>
                </div>
            </a>

            <nav class="hidden items-center gap-6 text-base font-extrabold text-white/90 md:flex">
                <a class="{{ request()->routeIs('menu') ? 'text-white' : 'hover:text-white' }}" href="{{ route('menu') }}">Menu</a>
                <a class="{{ request()->routeIs('promos') ? 'text-white' : 'hover:text-white' }}" href="{{ route('promos') }}">Promo</a>
                <a class="{{ request()->routeIs('member.profile') ? 'text-white' : 'hover:text-white' }}" href="{{ route('member.profile') }}">Profil</a>
                <a class="{{ request()->routeIs('member.rewards') ? 'text-white' : 'hover:text-white' }}" href="{{ route('member.rewards') }}">Reward</a>
                <a class="{{ request()->routeIs('member.transactions') ? 'text-white' : 'hover:text-white' }}" href="{{ route('member.transactions') }}">Riwayat</a>
            </nav>

            <div class="flex items-center gap-2 md:gap-3">
                @php($cartCount = array_sum(session('cart', [])))
                <a href="{{ route('cart.show') }}" class="relative inline-flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white md:text-base">
                    <span>Keranjang</span>
                    @if ($cartCount > 0)
                        <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-700 px-1 text-[11px] font-black text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button class="rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20 md:text-base">Logout</button>
                </form>
            </div>
        </div>

        <div class="border-t border-white/15 md:hidden">
            <div class="mx-auto grid max-w-6xl grid-cols-5 gap-2 px-3 py-3 text-sm font-extrabold text-white/90">
                <a class="rounded-2xl px-2 py-2.5 text-center hover:bg-white/10 {{ request()->routeIs('menu') ? 'bg-white/15 text-white' : '' }}" href="{{ route('menu') }}">Menu</a>
                <a class="rounded-2xl px-2 py-2.5 text-center hover:bg-white/10 {{ request()->routeIs('promos') ? 'bg-white/15 text-white' : '' }}" href="{{ route('promos') }}">Promo</a>
                <a class="rounded-2xl px-2 py-2.5 text-center hover:bg-white/10 {{ request()->routeIs('member.profile') ? 'bg-white/15 text-white' : '' }}" href="{{ route('member.profile') }}">Profil</a>
                <a class="rounded-2xl px-2 py-2.5 text-center hover:bg-white/10 {{ request()->routeIs('member.rewards') ? 'bg-white/15 text-white' : '' }}" href="{{ route('member.rewards') }}">Reward</a>
                <a class="rounded-2xl px-2 py-2.5 text-center hover:bg-white/10 {{ request()->routeIs('member.transactions') ? 'bg-white/15 text-white' : '' }}" href="{{ route('member.transactions') }}">Riwayat</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-10">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
