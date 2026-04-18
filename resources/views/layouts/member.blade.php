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
    <header class="sticky top-0 z-40 border-b border-brand-600/20 bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 text-white shadow-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-3 px-4 py-3">
            <a href="{{ route('menu') }}" class="flex items-center gap-2 font-extrabold tracking-tight">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/95 text-brand-700 shadow-sm ring-1 ring-white/30">W</span>
                <span>Member</span>
            </a>

            <nav class="hidden items-center gap-4 text-sm font-semibold text-white/90 md:flex">
                <a class="{{ request()->routeIs('menu') ? 'text-white' : 'hover:text-white' }}" href="{{ route('menu') }}">Menu</a>
                <a class="{{ request()->routeIs('promos') ? 'text-white' : 'hover:text-white' }}" href="{{ route('promos') }}">Promo</a>
                <a class="{{ request()->routeIs('member.profile') ? 'text-white' : 'hover:text-white' }}" href="{{ route('member.profile') }}">Profil</a>
                <a class="{{ request()->routeIs('member.rewards') ? 'text-white' : 'hover:text-white' }}" href="{{ route('member.rewards') }}">Reward</a>
                <a class="{{ request()->routeIs('member.transactions') ? 'text-white' : 'hover:text-white' }}" href="{{ route('member.transactions') }}">Riwayat</a>
            </nav>

            <div class="flex items-center gap-2">
                @php($cartCount = array_sum(session('cart', [])))
                <a href="{{ route('cart.show') }}" class="relative inline-flex items-center gap-2 rounded-xl bg-white/95 px-3 py-2 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white">
                    <span>Keranjang</span>
                    @if ($cartCount > 0)
                        <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-700 px-1 text-[11px] font-black text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button class="rounded-xl bg-white/15 px-3 py-2 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20">Logout</button>
                </form>
            </div>
        </div>

        <div class="border-t border-white/15 md:hidden">
            <div class="mx-auto grid max-w-6xl grid-cols-5 gap-1 px-2 py-2 text-xs font-extrabold text-white/90">
                <a class="rounded-xl px-2 py-2 text-center hover:bg-white/10 {{ request()->routeIs('menu') ? 'bg-white/15 text-white' : '' }}" href="{{ route('menu') }}">Menu</a>
                <a class="rounded-xl px-2 py-2 text-center hover:bg-white/10 {{ request()->routeIs('promos') ? 'bg-white/15 text-white' : '' }}" href="{{ route('promos') }}">Promo</a>
                <a class="rounded-xl px-2 py-2 text-center hover:bg-white/10 {{ request()->routeIs('member.profile') ? 'bg-white/15 text-white' : '' }}" href="{{ route('member.profile') }}">Profil</a>
                <a class="rounded-xl px-2 py-2 text-center hover:bg-white/10 {{ request()->routeIs('member.rewards') ? 'bg-white/15 text-white' : '' }}" href="{{ route('member.rewards') }}">Reward</a>
                <a class="rounded-xl px-2 py-2 text-center hover:bg-white/10 {{ request()->routeIs('member.transactions') ? 'bg-white/15 text-white' : '' }}" href="{{ route('member.transactions') }}">Riwayat</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-10">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
