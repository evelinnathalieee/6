@extends('layouts.member')

@section('title', 'Keranjang — Westland Coffee')

@section('content')
    <x-page.title title="Keranjang" subtitle="Atur qty, lalu checkout." />

    @if (count($lines) === 0)
        <div class="mt-8 rounded-3xl border border-zinc-200 bg-white p-8 text-sm text-zinc-600">
            Keranjang masih kosong. Yuk pilih menu dulu.
            <div class="mt-4">
                <a href="{{ route('menu') }}" class="btn-primary">Lihat Menu</a>
            </div>
        </div>
    @else
        <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <form method="POST" action="{{ route('cart.update') }}">
                @csrf
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                            <tr>
                                <th class="px-5 py-4">Menu</th>
                                <th class="px-5 py-4">Harga</th>
                                <th class="px-5 py-4">Qty</th>
                                <th class="px-5 py-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200">
                            @foreach ($lines as $line)
                                @php($item = $line['item'])
                                <tr class="align-top">
                                    <td class="px-5 py-4">
                                        <div class="flex items-start gap-3">
                                        @if ($item->imageSrc())
                                            <img src="{{ $item->imageSrc() }}" alt="{{ $item->name }}" class="h-16 w-16 rounded-xl border border-zinc-200 object-cover shadow-sm" />
                                        @else
                                            <div class="h-16 w-16 rounded-xl border border-dashed border-zinc-300 bg-zinc-50"></div>
                                        @endif
                                            <div>
                                                <div class="font-semibold">{{ $item->name }}</div>
                                                <div class="mt-1 text-xs text-zinc-500">{{ $item->category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-zinc-800">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="inline-flex items-center overflow-hidden rounded-xl border border-zinc-200 bg-white">
                                            <button type="button" data-qty-minus="{{ $item->id }}" class="h-10 w-10 text-zinc-700 hover:bg-zinc-50">−</button>
                                            <input type="number" min="0" max="99" inputmode="numeric" name="items[{{ $item->id }}]" value="{{ $line['qty'] }}" class="h-10 w-20 border-x border-zinc-200 px-3 text-center text-sm font-semibold outline-none focus:border-brand-500" />
                                            <button type="button" data-qty-plus="{{ $item->id }}" class="h-10 w-10 text-zinc-700 hover:bg-zinc-50">+</button>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format($line['lineTotal'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col gap-3 border-t border-zinc-200 px-5 py-4 md:flex-row md:items-center md:justify-between">
                    <div class="text-sm text-zinc-600">
                        Total: <span class="text-lg font-semibold text-zinc-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('member.checkout') }}" class="btn-primary">Checkout</a>
                    </div>
                </div>
            </form>
        </div>

        <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
            @csrf
            <button class="rounded-xl bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 hover:bg-rose-100">Kosongkan Keranjang</button>
        </form>

        <script>
            (function () {
                const form = document.querySelector('form[action="{{ route('cart.update') }}"]');
                if (!form) return;

                function scheduleSubmit() {
                    if (form._t) clearTimeout(form._t);
                    form._t = setTimeout(() => form.submit(), 500);
                }

                form.querySelectorAll('[data-qty-minus]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-qty-minus');
                        const input = form.querySelector('input[name="items[' + id + ']"]');
                        if (!input) return;
                        const v = parseInt(input.value || '0', 10) || 0;
                        input.value = String(Math.max(0, v - 1));
                        scheduleSubmit();
                    });
                });

                form.querySelectorAll('[data-qty-plus]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-qty-plus');
                        const input = form.querySelector('input[name="items[' + id + ']"]');
                        if (!input) return;
                        const v = parseInt(input.value || '0', 10) || 0;
                        input.value = String(Math.min(99, v + 1));
                        scheduleSubmit();
                    });
                });

                let t = null;
                form.addEventListener('input', (e) => {
                    if (!e.target || e.target.tagName !== 'INPUT') return;
                    if (e.target.name && e.target.name.startsWith('items[')) {
                        if (t) clearTimeout(t);
                        t = setTimeout(() => form.submit(), 700);
                    }
                });
            })();
        </script>
    @endif
@endsection
