<footer class="mt-16 border-t border-zinc-200 bg-white">
    <div class="mx-auto grid max-w-6xl gap-6 px-4 py-10 md:grid-cols-3">
        <div>
            <div class="text-base font-extrabold tracking-tight text-zinc-900">{{ config('app.name', 'Westland Coffee') }}</div>
            <div class="mt-2 text-sm text-zinc-600">Kopi & non-kopi untuk nongkrong. Promo jelas, stamp jalan.</div>
        </div>
        <div class="text-sm text-zinc-600">
            <div class="font-semibold text-zinc-900">Jam Operasional</div>
            <div class="mt-2">Senin–Minggu • 18.00–02.00</div>
            <div class="mt-1">Cut Nyak Dien, Pekanbaru</div>
        </div>
        <div class="text-sm text-zinc-600">
            <div class="font-semibold text-zinc-900">Navigasi</div>
            <div class="mt-2 grid grid-cols-2 gap-2">
                <a class="hover:text-zinc-900" href="{{ route('menu') }}">Menu</a>
                <a class="hover:text-zinc-900" href="{{ route('promos') }}">Promo</a>
                <a class="hover:text-zinc-900" href="{{ route('loyalty') }}">Member</a>
                <a class="hover:text-zinc-900" href="{{ route('cart.show') }}">Keranjang</a>
            </div>
        </div>
    </div>
    <div class="border-t border-zinc-200 py-4 text-center text-xs text-zinc-500">
        © {{ date('Y') }} {{ config('app.name', 'Westland Coffee') }}
    </div>
</footer>
