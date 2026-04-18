@extends('layouts.admin')

@section('title', 'Promosi — Westland Coffee')

@section('content')
    <x-page.title title="Promo" subtitle="Kelola promo aktif/nonaktif dan periode." />

    <div class="mt-4 flex justify-end">
        <a href="{{ route('admin.promos.create') }}" class="rounded-xl bg-brand-500 px-4 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-brand-600">Tambah Promo</a>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Periode</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach ($promos as $p)
                        @php($now = now())
                        @php($status = 'nonaktif')
                        @php($badgeClass = 'bg-zinc-100 text-zinc-700')
                        @php($statusHint = null)

                        @if ($p->is_enabled)
                            @php($status = 'aktif')
                            @php($badgeClass = 'bg-emerald-50 text-emerald-700')

                            @if ($p->starts_at && $now->lt($p->starts_at))
                                @php($status = 'terjadwal')
                                @php($badgeClass = 'bg-amber-50 text-amber-800')
                                @php($statusHint = 'Mulai '.$p->starts_at->format('d M Y H:i'))
                            @elseif ($p->ends_at && $now->gt($p->ends_at))
                                @php($status = 'berakhir')
                                @php($badgeClass = 'bg-zinc-100 text-zinc-700')
                                @php($statusHint = 'Selesai '.$p->ends_at->format('d M Y H:i'))
                            @endif
                        @endif
                        <tr>
                            <td class="px-5 py-4">
                                <div class="font-semibold">{{ $p->name }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ \Illuminate\Support\Str::limit($p->description, 90) }}</div>
                                <div class="mt-2 text-xs text-zinc-600">
                                    Diskon: <span class="font-semibold">{{ $p->discountLabel() }}</span>
                                    @if ((int) $p->min_subtotal > 0)
                                        • Min Rp {{ number_format((int) $p->min_subtotal, 0, ',', '.') }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">
                                <div class="text-xs text-zinc-500">Mulai</div>
                                <div>{{ $p->starts_at?->format('d M Y') ?? '—' }}</div>
                                <div class="mt-2 text-xs text-zinc-500">Selesai</div>
                                <div>{{ $p->ends_at?->format('d M Y') ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="badge {{ $badgeClass }}">
                                    {{ $status }}
                                </span>
                                @if ($statusHint)
                                    <div class="mt-1 text-xs text-zinc-500">{{ $statusHint }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.promos.edit', $p) }}" class="rounded-lg bg-zinc-100 px-3 py-2 text-xs font-semibold hover:bg-zinc-200">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4">
            {{ $promos->links() }}
        </div>
    </div>
@endsection
