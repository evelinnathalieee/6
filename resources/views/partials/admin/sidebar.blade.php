@php
    $itemClass = fn (bool $active) => $active
        ? 'block rounded-xl bg-white/95 px-3 py-2 font-extrabold text-brand-700 shadow-sm'
        : 'block rounded-xl px-3 py-2 font-semibold text-white/90 hover:bg-white/15 hover:text-white';
@endphp

<aside class="border-b border-white/15 bg-gradient-to-b from-brand-600 via-brand-500 to-brand-600 text-white md:min-h-screen md:w-64 md:border-b-0 md:border-r md:border-white/15">
    <div class="flex items-center justify-between px-4 py-4 md:block">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-extrabold tracking-tight">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/95 text-brand-700 shadow-sm ring-1 ring-white/30">W</span>
            <span>Admin</span>
        </a>
    </div>

    <nav class="px-2 pb-4 text-sm">
        <a class="{{ $itemClass(request()->routeIs('admin.dashboard')) }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a class="{{ $itemClass(request()->routeIs('admin.orders.*') || request()->routeIs('admin.pos') || request()->routeIs('admin.sales.*')) }}" href="{{ route('admin.orders.index') }}">Penjualan</a>
        <a class="{{ $itemClass(request()->routeIs('admin.menu.*')) }}" href="{{ route('admin.menu.index') }}">Menu</a>
        <a class="{{ $itemClass(request()->routeIs('admin.stocks.*')) }}" href="{{ route('admin.stocks.index') }}">Stok</a>
        <a class="{{ $itemClass(request()->routeIs('admin.promos.*')) }}" href="{{ route('admin.promos.index') }}">Promo</a>
        <a class="{{ $itemClass(request()->routeIs('admin.members.*')) }}" href="{{ route('admin.members.index') }}">Member</a>
        <a class="{{ $itemClass(request()->routeIs('admin.loyalty.*')) }}" href="{{ route('admin.loyalty.edit') }}">Loyalty</a>

        <div class="mt-2 border-t border-white/20 pt-2">
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                @csrf
                <button class="w-full rounded-xl bg-white/95 px-3 py-2 text-left font-extrabold text-brand-700 shadow-sm hover:bg-white">Logout</button>
            </form>
        </div>
    </nav>
</aside>
