@extends('layouts.admin')

@section('title', 'Log Stok — Westland Coffee')

@section('content')
    <x-page.title title="Log Stok" subtitle="Riwayat stok masuk/keluar/adjust." />

    <div class="mt-4">
        <a href="{{ route('admin.stocks.index') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-extrabold text-brand-700 shadow-sm ring-1 ring-brand-200 hover:bg-brand-50">Kembali</a>
    </div>

    <div class="mt-8 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Bahan</th>
                        <th class="px-5 py-4">Tipe</th>
                        <th class="px-5 py-4">Jumlah</th>
                        <th class="px-5 py-4">Catatan</th>
                        <th class="px-5 py-4">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @foreach ($movements as $m)
                        <tr>
                            <td class="px-5 py-4 text-zinc-800">{{ $m->moved_at->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 font-semibold">{{ $m->ingredient->name }}</td>
                            <td class="px-5 py-4">
                                <span class="badge bg-zinc-100 text-zinc-800">{{ $m->type }}</span>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">{{ $m->quantity }} {{ $m->ingredient->unit }}</td>
                            <td class="px-5 py-4 text-zinc-600">{{ $m->note }}</td>
                            <td class="px-5 py-4 text-zinc-600">{{ $m->creator?->name ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4">
            {{ $movements->links() }}
        </div>
    </div>
@endsection
