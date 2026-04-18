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
            <div class="mt-3 text-xl font-black">Bayar sekarang</div>
            <div class="mt-2 text-sm text-white/90">Nomor pesanan + stamp otomatis. Reward bisa dipakai langsung.</div>

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

                <div class="mt-4 rounded-2xl bg-white/95 p-4 text-zinc-900 shadow-sm ring-1 ring-white/30">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="text-sm font-extrabold">Voucher / Promo</div>
                            <div class="mt-1 text-xs text-zinc-600" id="promo_selected_text">Tidak pakai promo</div>
                        </div>
                        <details class="relative">
                            <summary class="cursor-pointer list-none rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Pilih</summary>
                            <div class="absolute right-0 mt-2 w-[320px] max-w-[calc(100vw-4rem)] overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xl">
                                <div class="px-4 py-3 text-xs font-extrabold text-zinc-500">Promo tersedia</div>
                                <div class="max-h-72 overflow-y-auto divide-y divide-zinc-200">
                                    <label class="flex cursor-pointer items-start gap-3 px-4 py-3 hover:bg-zinc-50">
                                        <input type="radio" name="promo_id" value="" {{ old('promo_id') ? '' : 'checked' }} class="mt-1 h-4 w-4 text-brand-600 focus:ring-brand-500">
                                        <div>
                                            <div class="text-sm font-extrabold text-zinc-900">Tanpa promo</div>
                                            <div class="mt-0.5 text-xs text-zinc-500">Bayar normal.</div>
                                        </div>
                                    </label>

                                    @foreach ($promos as $p)
                                        @php($disabled = ! $p['selectable'])
                                        <label class="flex items-start gap-3 px-4 py-3 {{ $disabled ? 'opacity-60' : 'cursor-pointer hover:bg-zinc-50' }}">
                                            <input
                                                type="radio"
                                                name="promo_id"
                                                value="{{ $p['id'] }}"
                                                class="mt-1 h-4 w-4 text-brand-600 focus:ring-brand-500"
                                                {{ $disabled ? 'disabled' : '' }}
                                                {{ (string) old('promo_id') === (string) $p['id'] ? 'checked' : '' }}
                                                data-promo-name="{{ $p['name'] }}"
                                                data-discount-type="{{ $p['discount_type'] }}"
                                                data-discount-value="{{ (int) $p['discount_value'] }}"
                                                data-min-subtotal="{{ (int) $p['min_subtotal'] }}"
                                            >
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="text-sm font-extrabold text-zinc-900">{{ $p['name'] }}</div>
                                                    <div class="badge bg-brand-50 text-brand-700">{{ $p['label'] }}</div>
                                                </div>
                                                <div class="mt-1 text-xs text-zinc-600">{{ \Illuminate\Support\Str::limit($p['description'], 70) }}</div>
                                                <div class="mt-2 text-[11px] text-zinc-500">
                                                    @if ((int) $p['min_subtotal'] > 0) Min Rp {{ number_format((int) $p['min_subtotal'], 0, ',', '.') }} @endif
                                                    @if ($p['reason']) • {{ $p['reason'] }} @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                    </div>
                    @error('promo_id') <div class="mt-2 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                @if ($canUseReward)
                    <div class="mt-4 rounded-2xl bg-white/95 p-4 text-zinc-900 shadow-sm ring-1 ring-white/30">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-extrabold">Pakai 1 reward</div>
                                <div class="mt-1 text-xs text-zinc-600">
                                    {{ $rewardLabel ?? 'Gratis 1 minuman' }} • Diskon otomatis sebesar <span class="font-extrabold">Rp {{ number_format($rewardValue, 0, ',', '.') }}</span> (minuman termurah di keranjang).
                                </div>
                                <div class="mt-2 text-[11px] text-zinc-500">
                                    Reward tersedia sekarang: <span class="font-semibold">{{ $availableRewardsNow }}</span>
                                </div>
                            </div>
                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input id="use_reward" type="checkbox" name="use_reward" value="1" {{ old('use_reward') ? 'checked' : '' }} class="h-5 w-5 rounded border-zinc-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-sm font-extrabold text-brand-700">Pakai</span>
                            </label>
                        </div>
                    </div>
                @else
                    <div class="mt-4 rounded-2xl bg-white/10 p-4 text-xs text-white/85 ring-1 ring-white/15">
                        Reward tersedia: <span class="font-extrabold">{{ $availableRewardsNow }}</span> • Kumpulkan {{ $stampsPerReward }} stamp untuk 1 reward.
                    </div>
                @endif

                <div class="mt-4 rounded-2xl bg-white/10 p-4 text-sm ring-1 ring-white/15">
                    <div class="flex items-center justify-between">
                        <span class="text-white/85">Subtotal</span>
                        <span class="font-extrabold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div id="promo_row" class="mt-1 hidden items-center justify-between text-white/85">
                        <span id="promo_name">Promo</span>
                        <span id="promo_value" class="font-extrabold">Rp 0</span>
                    </div>
                    <div id="discount_row" class="{{ $canUseReward ? '' : 'hidden' }} mt-1 flex items-center justify-between text-white/85">
                        <span>Diskon reward</span>
                        <span id="discount_value" class="font-extrabold">Rp 0</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between border-t border-white/15 pt-2">
                        <span class="text-white/90">Total bayar</span>
                        <span id="grand_total" class="text-xl font-black">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button class="btn-primary w-full">Bayar Sekarang</button>
            </form>

            <div class="mt-3">
                <a href="{{ route('cart.show') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Kembali ke Keranjang</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const checkbox = document.getElementById('use_reward');
            const discountRow = document.getElementById('discount_row');
            const discountValue = document.getElementById('discount_value');
            const grandTotal = document.getElementById('grand_total');

            const promoRow = document.getElementById('promo_row');
            const promoName = document.getElementById('promo_name');
            const promoValueEl = document.getElementById('promo_value');
            const promoSelectedText = document.getElementById('promo_selected_text');
            const promoRadios = document.querySelectorAll('input[name="promo_id"]');

            const subtotal = {{ (int) $total }};
            const rewardValue = {{ (int) $rewardValue }};

            function calcPromoDiscount() {
                const selected = document.querySelector('input[name="promo_id"]:checked');
                if (!selected || !selected.value) {
                    promoRow?.classList.add('hidden');
                    if (promoSelectedText) promoSelectedText.textContent = 'Tidak pakai promo';
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
                if (promoSelectedText) promoSelectedText.textContent = name + ' (hemat Rp ' + new Intl.NumberFormat('id-ID').format(d) + ')';

                return d;
            }

            function render() {
                const useReward = checkbox ? checkbox.checked : false;
                const rewardDiscount = useReward ? rewardValue : 0;
                const promoDiscount = calcPromoDiscount();

                const totalDiscount = Math.min(subtotal, promoDiscount + rewardDiscount);
                const total = Math.max(0, subtotal - totalDiscount);

                if (discountRow) discountRow.classList.toggle('hidden', !useReward);
                if (discountValue) discountValue.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(rewardDiscount);
                if (grandTotal) grandTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            }

            checkbox?.addEventListener('change', render);
            promoRadios.forEach(r => r.addEventListener('change', render));
            render();
        })();
    </script>
@endsection
