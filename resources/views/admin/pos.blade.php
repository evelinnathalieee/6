@extends('layouts.admin')

@section('title', 'Kasir — Westland Coffee')

@section('content')
    <x-page.title title="Kasir" subtitle="Transaksi langsung untuk pembeli walk-in." />
    @include('partials.admin.sales-tabs')

    <div class="mt-8 grid gap-4 lg:grid-cols-12">
        <section class="card p-5 lg:col-span-7">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-sm font-extrabold">Kasir</div>
                    <div class="mt-1 text-xs text-zinc-500">Klik menu untuk tambah ke keranjang.</div>
                </div>
                <div class="flex gap-2">
                    <input id="menuSearch" placeholder="Cari menu..." class="input w-full md:w-72" />
                    <select id="menuCategory" class="input md:w-44">
                        <option value="">Semua</option>
                        <option value="kopi">Kopi</option>
                        <option value="non_kopi">Non-kopi</option>
                    </select>
                </div>
            </div>

            <div id="menuGrid" class="mt-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($menu as $m)
                    <button
                        type="button"
                        class="group overflow-hidden rounded-2xl border border-zinc-200 bg-white text-left hover:bg-zinc-50"
                        data-menu-card
                        data-id="{{ $m->id }}"
                        data-name="{{ $m->name }}"
                        data-price="{{ (int) $m->price }}"
                        data-category="{{ $m->category }}"
                    >
                        @if ($m->imageSrc())
                            <img src="{{ $m->imageSrc() }}" alt="{{ $m->name }}" class="h-28 w-full object-cover" />
                        @else
                            <div class="h-28 w-full bg-gradient-to-br from-zinc-50 to-zinc-100"></div>
                        @endif
                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-extrabold text-zinc-900">{{ $m->name }}</div>
                                    <div class="mt-0.5 text-xs text-zinc-500">{{ $m->category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</div>
                                </div>
                                <div class="shrink-0 rounded-xl bg-brand-50 px-3 py-2 text-xs font-extrabold text-brand-700">
                                    Rp {{ number_format((int) $m->price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </section>

        <section class="card p-5 lg:col-span-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <div class="text-sm font-extrabold">Keranjang</div>
                    <div class="mt-1 text-xs text-zinc-500">Qty bisa + / -.</div>
                </div>
                <button type="button" id="cartClear" class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-extrabold text-rose-700 hover:bg-rose-100">Clear</button>
            </div>

            <form id="posForm" method="POST" action="{{ route('admin.sales.store') }}" class="mt-5 space-y-4">
                @csrf

                <div id="cartLines" class="space-y-2"></div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-4">
                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-extrabold text-zinc-700">Order</label>
                            <select name="order_type" class="input" required>
                                <option value="dine_in">Dine in</option>
                                <option value="take_away">Take away</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-extrabold text-zinc-700">Metode bayar</label>
                            <select name="payment_method" class="input" required>
                                <option value="cash">Cash</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-extrabold text-zinc-700">Email member (opsional)</label>
                            <input name="member_email" class="input" placeholder="member@email.com" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-extrabold text-zinc-700">Promo</label>
                            <select name="promo_id" class="input">
                                <option value="">Tanpa promo</option>
                                @foreach ($promos as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }} ({{ $p->discountLabel() }}{{ (int) $p->min_subtotal > 0 ? ', min Rp '.number_format((int) $p->min_subtotal, 0, ',', '.') : '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-extrabold text-zinc-700">Catatan</label>
                            <textarea name="note" rows="2" class="input" required></textarea>
                        </div>
                    </div>

                    <label class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-zinc-800">
                        <input type="checkbox" name="use_reward" value="1" class="h-5 w-5 rounded border-zinc-300 text-brand-600 focus:ring-brand-500">
                        Pakai 1 reward
                    </label>

                    <div class="mt-4 rounded-2xl bg-brand-50 p-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-zinc-600">Subtotal</span>
                            <span class="font-extrabold text-zinc-900" id="subtotalText">Rp 0</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between border-t border-brand-100 pt-2">
                            <span class="text-zinc-700">Total</span>
                            <span class="text-lg font-black text-brand-700" id="totalText">Rp 0</span>
                        </div>
                    </div>
                </div>

                <button id="payBtn" class="btn-primary w-full" type="submit" disabled>Bayar</button>
            </form>
        </section>
    </div>

    <template id="cartLineTpl">
        <div class="flex items-center justify-between gap-3 rounded-2xl border border-zinc-200 bg-white px-4 py-3">
            <div class="min-w-0">
                <div class="truncate text-sm font-extrabold text-zinc-900" data-name>—</div>
                <div class="mt-1 text-xs font-extrabold text-brand-700" data-price>Rp 0</div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" class="h-10 w-10 rounded-xl border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50" data-minus>−</button>
                <div class="w-10 text-center text-sm font-extrabold text-zinc-900" data-qty>1</div>
                <button type="button" class="h-10 w-10 rounded-xl border border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50" data-plus>+</button>
            </div>
        </div>
    </template>

    <script>
        (function () {
            const cards = Array.from(document.querySelectorAll('[data-menu-card]'));
            const cartLines = document.getElementById('cartLines');
            const tpl = document.getElementById('cartLineTpl');
            const subtotalText = document.getElementById('subtotalText');
            const totalText = document.getElementById('totalText');
            const payBtn = document.getElementById('payBtn');
            const form = document.getElementById('posForm');

            const search = document.getElementById('menuSearch');
            const cat = document.getElementById('menuCategory');

            const cart = new Map(); // id -> {id,name,price,qty}

            function fmt(n) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);
            }

            function renderMenuFilter() {
                const q = (search.value || '').toLowerCase().trim();
                const c = cat.value;
                cards.forEach((btn) => {
                    const name = (btn.dataset.name || '').toLowerCase();
                    const category = btn.dataset.category || '';
                    const ok = (!q || name.includes(q)) && (!c || c === category);
                    btn.classList.toggle('hidden', !ok);
                });
            }

            function subtotal() {
                let s = 0;
                cart.forEach((it) => s += (it.price * it.qty));
                return s;
            }

            function rebuildHiddenInputs() {
                form.querySelectorAll('input[name^="items["]').forEach((el) => el.remove());

                let i = 0;
                cart.forEach((it) => {
                    const idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = `items[${i}][menu_item_id]`;
                    idInput.value = String(it.id);
                    form.appendChild(idInput);

                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = `items[${i}][quantity]`;
                    qtyInput.value = String(it.qty);
                    form.appendChild(qtyInput);
                    i++;
                });
            }

            function renderCart() {
                cartLines.innerHTML = '';

                cart.forEach((it) => {
                    const node = tpl.content.cloneNode(true);
                    node.querySelector('[data-name]').textContent = it.name;
                    node.querySelector('[data-price]').textContent = fmt(it.price);
                    node.querySelector('[data-qty]').textContent = String(it.qty);

                    node.querySelector('[data-minus]').addEventListener('click', () => {
                        it.qty = Math.max(0, it.qty - 1);
                        if (it.qty === 0) cart.delete(it.id);
                        renderCart();
                    });
                    node.querySelector('[data-plus]').addEventListener('click', () => {
                        it.qty = Math.min(99, it.qty + 1);
                        renderCart();
                    });

                    cartLines.appendChild(node);
                });

                const s = subtotal();
                subtotalText.textContent = fmt(s);
                totalText.textContent = fmt(s);
                payBtn.disabled = cart.size === 0;
                rebuildHiddenInputs();
            }

            cards.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.dataset.id || '0', 10);
                    const price = parseInt(btn.dataset.price || '0', 10) || 0;
                    const name = btn.dataset.name || 'Menu';
                    if (!id) return;

                    const cur = cart.get(id) || { id, name, price, qty: 0 };
                    cur.qty = Math.min(99, cur.qty + 1);
                    cart.set(id, cur);
                    renderCart();
                });
            });

            document.getElementById('cartClear')?.addEventListener('click', () => {
                cart.clear();
                renderCart();
            });

            search?.addEventListener('input', renderMenuFilter);
            cat?.addEventListener('change', renderMenuFilter);
            renderMenuFilter();
            renderCart();
        })();
    </script>
@endsection
