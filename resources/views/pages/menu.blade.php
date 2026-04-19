@extends(auth()->check() && auth()->user()->isMember() ? 'layouts.member' : 'layouts.public')

@section('title', 'Menu — Westland Coffee')

@section('content')
    <x-page.title title="Menu" subtitle="Pilih kopi atau non-kopi, tambah ke keranjang, lalu checkout." />

    @php
        $categoryLabels = [
            'kopi' => 'Kopi',
            'non_kopi' => 'Non-kopi',
        ];
        $firstCategory = $menuByCategory->keys()->first();
    @endphp

    @if ($menuByCategory->isNotEmpty())
        <section class="mt-8 rounded-3xl border border-zinc-200 bg-white p-5 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="text-sm font-extrabold text-zinc-900">Explore menu</div>
                    <div class="mt-1 text-xs text-zinc-500">Pilih kategori dulu biar lebih cepat lihat menu.</div>
                </div>
                <div class="w-full md:w-80">
                    <input
                        id="menuSearch"
                        type="text"
                        placeholder="Cari menu..."
                        class="input mt-0"
                    />
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($menuByCategory as $category => $items)
                    <button
                        type="button"
                        class="menu-tab rounded-2xl px-4 py-2.5 text-sm font-extrabold transition"
                        data-category-tab="{{ $category }}"
                    >
                        {{ $categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category)) }}
                        <span class="ml-1 text-xs opacity-75">{{ $items->count() }}</span>
                    </button>
                @endforeach
            </div>
        </section>
    @endif

    <div class="mt-8 grid gap-8">
        @forelse ($menuByCategory as $category => $items)
            <section
                class="menu-section rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]"
                data-category-section="{{ $category }}"
            >
                <div class="flex items-end justify-between gap-3">
                    <h2 class="text-lg font-extrabold">{{ $categoryLabels[$category] ?? ucfirst(str_replace('_', ' ', $category)) }}</h2>
                    <div class="text-xs text-zinc-500">{{ $items->count() }} item</div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    @foreach ($items as $item)
                        <div
                            class="menu-card card card-hover overflow-hidden p-0"
                            data-menu-name="{{ \Illuminate\Support\Str::lower($item->name.' '.$item->description) }}"
                        >
                            @if ($item->imageSrc())
                                <img src="{{ $item->imageSrc() }}" alt="{{ $item->name }}" class="h-44 w-full object-cover" />
                            @else
                                <div class="h-44 w-full bg-gradient-to-br from-zinc-50 to-zinc-100"></div>
                            @endif
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="text-base font-extrabold">{{ $item->name }}</div>
                                    @if ($item->is_featured)
                                        <div class="badge bg-brand-50 text-brand-700">unggulan</div>
                                    @endif
                                </div>
                                <div class="mt-2 text-sm text-zinc-600">{{ $item->description }}</div>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <div class="text-sm font-extrabold text-brand-700">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                    @auth
                                        @if (auth()->user()->isMember())
                                            <form method="POST" action="{{ route('cart.add', $item) }}">
                                                @csrf
                                                <button class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">+ Keranjang</button>
                                            </form>
                                        @else
                                            <span class="text-xs font-semibold text-zinc-500">Login sebagai member</span>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="rounded-xl bg-brand-500 px-4 py-2 text-xs font-extrabold text-white hover:bg-brand-600">Login dulu</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="menu-empty mt-4 hidden rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-4 text-sm text-zinc-600">
                    Menu yang kamu cari belum ada di kategori ini.
                </div>
            </section>
        @empty
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 text-sm text-zinc-600">
                Menu belum tersedia. Silakan isi data menu di database terlebih dahulu.
            </div>
        @endforelse
    </div>

    @guest
        <div class="mt-10 overflow-hidden rounded-3xl bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Member</div>
            <div class="mt-3 text-xl font-black">Dapatkan stamp otomatis</div>
            <div class="mt-2 text-sm text-white/90">Login/daftar member supaya riwayat transaksi dan reward tercatat.</div>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Daftar</a>
                <a href="{{ route('loyalty') }}" class="inline-flex items-center justify-center rounded-xl bg-white/15 px-5 py-3 text-sm font-extrabold text-white ring-1 ring-white/20 hover:bg-white/20">Detail Program</a>
            </div>
        </div>
    @endguest

    @if ($menuByCategory->isNotEmpty())
        <script>
            (function () {
                const tabs = Array.from(document.querySelectorAll('[data-category-tab]'));
                const sections = Array.from(document.querySelectorAll('[data-category-section]'));
                const searchInput = document.getElementById('menuSearch');
                let activeCategory = @json($firstCategory);

                function setActiveTab() {
                    tabs.forEach((tab) => {
                        const isActive = tab.dataset.categoryTab === activeCategory;
                        tab.className = isActive
                            ? 'menu-tab rounded-2xl bg-brand-500 px-4 py-2.5 text-sm font-extrabold text-white shadow-sm'
                            : 'menu-tab rounded-2xl bg-brand-50 px-4 py-2.5 text-sm font-extrabold text-brand-700';
                    });
                }

                function applyFilter() {
                    const query = (searchInput?.value || '').trim().toLowerCase();

                    sections.forEach((section) => {
                        const isActiveCategory = section.dataset.categorySection === activeCategory;
                        section.classList.toggle('hidden', !isActiveCategory);

                        if (!isActiveCategory) {
                            return;
                        }

                        const cards = Array.from(section.querySelectorAll('.menu-card'));
                        let visibleCount = 0;

                        cards.forEach((card) => {
                            const haystack = card.dataset.menuName || '';
                            const visible = !query || haystack.includes(query);
                            card.classList.toggle('hidden', !visible);
                            if (visible) visibleCount++;
                        });

                        const emptyState = section.querySelector('.menu-empty');
                        if (emptyState) {
                            emptyState.classList.toggle('hidden', visibleCount > 0);
                        }
                    });
                }

                tabs.forEach((tab) => {
                    tab.addEventListener('click', function () {
                        activeCategory = this.dataset.categoryTab;
                        setActiveTab();
                        applyFilter();
                    });
                });

                searchInput?.addEventListener('input', applyFilter);

                setActiveTab();
                applyFilter();
            })();
        </script>
    @endif
@endsection
