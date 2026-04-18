@extends(auth()->check() && auth()->user()->isMember() ? 'layouts.member' : 'layouts.public')

@section('title', 'Promo — Westland Coffee')

@section('content')
    <x-page.title title="Promo Aktif" subtitle="Promo yang bisa kamu pakai sekarang." />

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        @forelse ($activePromos as $promo)
            <div class="card card-hover p-6">
                <div class="flex items-start justify-between gap-3">
                    <div class="text-lg font-extrabold">{{ $promo->name }}</div>
                    <div class="badge bg-emerald-50 text-emerald-700">aktif</div>
                </div>
                <div class="mt-2 text-sm text-zinc-600">{{ $promo->description }}</div>
                <div class="mt-3 text-sm font-extrabold text-brand-700">
                    Diskon {{ $promo->discountLabel() }}
                    @if ((int) $promo->min_subtotal > 0)
                        <span class="text-xs font-semibold text-zinc-600">• Min Rp {{ number_format((int) $promo->min_subtotal, 0, ',', '.') }}</span>
                    @endif
                </div>
                <div class="mt-4 text-xs text-zinc-500">
                    @if ($promo->starts_at) Mulai: {{ $promo->starts_at->format('d M Y') }} @endif
                    @if ($promo->ends_at) • Selesai: {{ $promo->ends_at->format('d M Y') }} @endif
                </div>
            </div>
        @empty
            <div class="card p-6 text-sm text-zinc-600">
                Saat ini belum ada promo aktif.
            </div>
        @endforelse
    </div>

    @guest
        <div class="mt-10 overflow-hidden rounded-3xl bg-gradient-to-r from-brand-600 via-brand-500 to-brand-600 p-6 text-white shadow-sm ring-1 ring-white/10">
            <div class="chip">Member</div>
            <div class="mt-3 text-xl font-black">Mau stamp otomatis?</div>
            <div class="mt-2 text-sm text-white/90">Daftar member biar riwayat dan reward tercatat.</div>
            <div class="mt-4">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-brand-700 shadow-sm hover:bg-white/95">Daftar</a>
            </div>
        </div>
    @endguest
@endsection
