<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin - Westland Coffee')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @include('partials.vite')
</head>
<body class="min-h-screen bg-brand-50 text-zinc-900">
    <div class="min-h-screen md:flex">
        @include('partials.admin.sidebar')

        <main class="flex-1 px-4 py-8">
            <div class="mx-auto max-w-6xl">
                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <div id="adminToast" class="fixed right-4 top-4 z-50 hidden w-[360px] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-brand-200 bg-white shadow-xl">
        <div class="bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 px-4 py-2 text-xs font-extrabold text-white">Order baru</div>
        <div class="px-4 py-3">
            <div class="text-sm font-extrabold text-zinc-900" id="adminToastTitle">—</div>
            <div class="mt-1 text-xs text-zinc-600" id="adminToastMeta">—</div>
            <div class="mt-3 flex items-center justify-end gap-2">
                <a href="{{ route('admin.sales.index') }}" class="rounded-xl bg-brand-500 px-3 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Lihat Penjualan</a>
                <button type="button" id="adminToastClose" class="rounded-xl bg-white px-3 py-2 text-xs font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const toastEl = document.getElementById('adminToast');
            const toastTitleEl = document.getElementById('adminToastTitle');
            const toastMetaEl = document.getElementById('adminToastMeta');
            const toastCloseEl = document.getElementById('adminToastClose');

            const liveStatusEl = document.getElementById('liveStatus');
            const latestCodeEl = document.getElementById('latestCode');
            const latestMetaEl = document.getElementById('latestMeta');

            let lastCode = null;
            let hideTimer = null;

            function showToast(title, meta) {
                if (!toastEl) return;
                toastTitleEl.textContent = title || 'Order baru';
                toastMetaEl.textContent = meta || '';
                toastEl.classList.remove('hidden');

                if (hideTimer) clearTimeout(hideTimer);
                hideTimer = setTimeout(() => toastEl.classList.add('hidden'), 8000);
            }

            toastCloseEl?.addEventListener('click', () => toastEl.classList.add('hidden'));

            function orderTypeLabel(v) {
                return v === 'take_away' ? 'Take away' : 'Dine in';
            }

            async function tick() {
                try {
                    const res = await fetch(@json(route('admin.notifications')), {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin',
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();

                    if (liveStatusEl) {
                        liveStatusEl.textContent = 'live';
                        liveStatusEl.className = 'text-xs text-emerald-700';
                    }

                    const latest = data.latest_transaction;
                    if (!latest) return;

                    const title = (latest.order_number ? latest.order_number + ' • ' : '') + orderTypeLabel(latest.order_type);
                    const meta = (latest.customer || 'Walk-in') + ' • ' + (latest.items_count || 0) + ' item • Rp ' + new Intl.NumberFormat('id-ID').format(latest.total);

                    if (latestCodeEl) latestCodeEl.textContent = latest.code;
                    if (latestMetaEl) latestMetaEl.textContent = meta;

                    if (lastCode && lastCode !== latest.code) {
                        showToast(title, meta);
                        if (latestMetaEl) {
                            latestMetaEl.className = 'mt-1 text-xs text-brand-700';
                            setTimeout(() => latestMetaEl.className = 'mt-1 text-xs text-zinc-500', 2500);
                        }
                    }

                    lastCode = latest.code;
                } catch (e) {
                    if (liveStatusEl) {
                        liveStatusEl.textContent = 'offline';
                        liveStatusEl.className = 'text-xs text-rose-700';
                    }
                }
            }

            tick();
            setInterval(tick, 4000);
        })();
    </script>
</body>
</html>
