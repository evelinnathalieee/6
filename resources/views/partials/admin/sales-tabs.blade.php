@php
    use App\Models\Transaction;

    $tabClass = fn (bool $active) => $active
        ? 'rounded-xl bg-brand-500 px-4 py-2 text-sm font-extrabold text-white shadow-sm'
        : 'rounded-xl bg-white px-4 py-2 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50';

    $historyChannel = request('channel') === Transaction::CHANNEL_ONLINE
        ? Transaction::CHANNEL_ONLINE
        : Transaction::CHANNEL_POS;
@endphp

<div class="mt-4 flex flex-wrap gap-2">
    <a href="{{ route('admin.orders.index') }}" class="{{ $tabClass(request()->routeIs('admin.orders.*')) }}">Order Online</a>
    <a href="{{ route('admin.pos') }}" class="{{ $tabClass(request()->routeIs('admin.pos')) }}">Kasir</a>
    <a href="{{ route('admin.sales.index', ['channel' => Transaction::CHANNEL_POS]) }}" class="{{ $tabClass(request()->routeIs('admin.sales.*') && $historyChannel === Transaction::CHANNEL_POS) }}">Riwayat Kasir</a>
    <a href="{{ route('admin.sales.index', ['channel' => Transaction::CHANNEL_ONLINE]) }}" class="{{ $tabClass(request()->routeIs('admin.sales.*') && $historyChannel === Transaction::CHANNEL_ONLINE) }}">Riwayat Online</a>
</div>
