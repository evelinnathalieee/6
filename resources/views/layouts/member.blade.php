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
    <header class="border-b border-brand-600/30 bg-brand-500 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="rounded-lg bg-white/15 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">← Home</a>
                <a href="{{ route('menu') }}" class="hidden rounded-lg bg-white/15 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20 md:inline-flex">Menu</a>
                <a href="{{ route('promos') }}" class="hidden rounded-lg bg-white/15 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20 md:inline-flex">Promo</a>
            </div>

            <nav class="flex items-center gap-2 text-sm">
                @php($cartCount = array_sum(session('cart', [])))
                <a href="{{ route('cart.show') }}" class="relative rounded-lg bg-white/95 px-3 py-2 font-semibold text-brand-700 hover:bg-white">
                    Keranjang
                    @if ($cartCount > 0)
                        <span class="absolute -right-2 -top-2 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-700 px-1 text-xs font-bold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('member.profile') }}" class="rounded-lg px-3 py-2 font-semibold text-white/90 hover:bg-white/15 hover:text-white">Profil</a>
                <a href="{{ route('member.rewards') }}" class="rounded-lg px-3 py-2 font-semibold text-white/90 hover:bg-white/15 hover:text-white">Reward</a>
                <a href="{{ route('member.transactions') }}" class="rounded-lg px-3 py-2 font-semibold text-white/90 hover:bg-white/15 hover:text-white">Riwayat</a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button class="rounded-lg bg-white/95 px-3 py-2 font-semibold text-brand-700 hover:bg-white">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-10">
        @include('partials.flash')
        @yield('content')
    </main>
</body>
</html>
