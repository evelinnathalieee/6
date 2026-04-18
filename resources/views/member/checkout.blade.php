@extends('layouts.member')

@section('title', 'Checkout — Westland Coffee')

@section('content')
    <x-page.title title="Checkout" subtitle="Pilih dine in / take away, lalu bayar." />

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="md:col-span-2 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                        <tr>
                            <th class="px-5 py-4">Menu</th>
                            <th class="px-5 py-4">Qty</th>
                            <th class="px-5 py-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @foreach ($lines as $line)
                            @php($item = $line['item'])
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="font-semibold">{{ $item->name }}</div>
                                    <div class="mt-1 text-xs text-zinc-500">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-5 py-4 text-zinc-800">x{{ $line['qty'] }}</td>
                                <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format($line['lineTotal'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-zinc-200 px-5 py-4 text-right">
                <div class="text-sm text-zinc-600">Subtotal</div>
                <div class="text-lg font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="rounded-3xl bg-gradient-to-b from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Pembayaran</div>
            <div class="mt-3 text-xl font-black">Buat pesanan</div>
            <div class="mt-2 text-sm text-white/90">Status awal: pending. Admin akan konfirmasi saat pembayaran.</div>

            <form method="POST" action="{{ route('member.checkout.pay') }}" class="mt-6">
                @csrf

                <div>
                    <label class="text-sm font-extrabold text-white/95">Tipe pesanan</label>
                    <select name="order_type" class="mt-1 w-full rounded-xl border border-white/20 bg-white/95 px-4 py-3 text-sm font-semibold text-zinc-900 outline-none focus:border-white" required>
                        <option value="dine_in" {{ old('order_type', 'dine_in') === 'dine_in' ? 'selected' : '' }}>Dine in</option>
                        <option value="take_away" {{ old('order_type') === 'take_away' ? 'selected' : '' }}>Take away</option>
                    </select>
                    @error('order_type') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <label class="text-sm font-extrabold text-white/95">Metode bayar</label>
                    <select name="payment_method" class="mt-1 w-full rounded-xl border border-white/20 bg-white/95 px-4 py-3 text-sm font-semibold text-zinc-900 outline-none focus:border-white" required>
                        <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                    @error('payment_method') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <label class="text-sm font-extrabold text-white/95">Voucher / Promo</label>
                    <select id="promo_select" name="promo_id" class="mt-1 w-full rounded-xl border border-white/20 bg-white/95 px-4 py-3 text-sm font-semibold text-zinc-900 outline-none focus:border-white">
                        <option value="" data-discount-type="amount" data-discount-value="0">Tanpa promo</option>
                        @foreach ($promos as $p)
                            <option
                                value="{{ $p['id'] }}"
                                {{ (string) old('promo_id') === (string) $p['id'] ? 'selected' : '' }}
                                {{ $p['selectable'] ? '' : 'disabled' }}
                                data-promo-name="{{ $p['name'] }}"
                                data-discount-type="{{ $p['discount_type'] }}"
                                data-discount-value="{{ (int) $p['discount_value'] }}"
                            >
                                {{ $p['name'] }} — {{ $p['label'] }}@if($p['reason']) ({{ $p['reason'] }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('promo_id') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4 rounded-2xl bg-white/10 p-4 text-sm ring-1 ring-white/15">
                    <div class="flex items-center justify-between">
                        <span class="text-white/85">Subtotal</span>
                        <span class="font-extrabold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div id="promo_row" class="mt-1 hidden items-center justify-between text-white/85">
                        <span id="promo_name">Promo</span>
                        <span id="promo_value" class="font-extrabold">Rp 0</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between border-t border-white/15 pt-2">
                        <span class="text-white/90">Total</span>
                        <span id="grand_total" class="text-xl font-black">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button class="btn-primary w-full">Kirim Pesanan</button>
            </form>

            <div class="mt-3">
                <a href="{{ route('cart.show') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Kembali ke Keranjang</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const grandTotal = document.getElementById('grand_total');

            const promoRow = document.getElementById('promo_row');
            const promoName = document.getElementById('promo_name');
            const promoValueEl = document.getElementById('promo_value');
            const promoSelect = document.getElementById('promo_select');

            const subtotal = {{ (int) $total }};

            function calcPromoDiscount() {
                const selected = promoSelect?.selectedOptions?.[0];
                if (!selected || !selected.value) {
                    promoRow?.classList.add('hidden');
                    return 0;
                }

                const type = selected.dataset.discountType;
                const value = parseInt(selected.dataset.discountValue || '0', 10) || 0;
                const name = selected.dataset.promoName || 'Promo';

                let d = 0;
                if (type === 'percent') d = Math.floor((subtotal * Math.min(100, Math.max(0, value))) / 100);
                else d = Math.min(subtotal, Math.max(0, value));

                if (promoName) promoName.textContent = name;
                if (promoValueEl) promoValueEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(d);
                promoRow?.classList.toggle('hidden', d <= 0);

                return d;
            }

            function render() {
                const promoDiscount = calcPromoDiscount();

                const totalDiscount = Math.min(subtotal, promoDiscount);
                const total = Math.max(0, subtotal - totalDiscount);

                if (grandTotal) grandTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            }

            promoSelect?.addEventListener('change', render);
            render();
        })();
    </script>
@endsection
