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
            @guest
                <a href="/login" class="inline-flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white md:text-base">
                    <span>Login</span>
                </a>
                <a href="/register" class="inline-flex items-center gap-2 rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20 md:text-base">
                    <span>Daftar</span>
                </a>
            @else
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white md:text-base">
                        <span>Dashboard</span>
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20 md:text-base">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('member.profile') }}" class="inline-flex items-center gap-2 rounded-2xl bg-white/95 px-4 py-2.5 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-white/30 hover:bg-white md:text-base">
                        <span>Member</span>
                    </a>
                    <form method="POST" action="{{ route('member.logout') }}">
                        @csrf
                        <button class="inline-flex items-center gap-2 rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm ring-1 ring-white/20 hover:bg-white/20 md:text-base">
                            Logout
                        </button>
                    </form>
                @endif
            @endguest
        </div>
    </div>
</header>
