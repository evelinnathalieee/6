@extends('layouts.admin')

@section('title', 'Menu — Admin Westland Coffee')

@section('content')
    <x-page.title title="Menu" subtitle="Kelola menu kopi & non-kopi untuk ditampilkan ke pengunjung." />

    <div class="mt-4 flex justify-end">
        <a href="{{ route('admin.menu.create') }}" class="rounded-xl bg-brand-500 px-4 py-3 text-sm font-extrabold text-white shadow-sm hover:bg-brand-600">Tambah Menu</a>
    </div>

    <form class="mt-6 flex flex-col gap-3 md:flex-row md:items-center" method="GET" action="{{ route('admin.menu.index') }}">
        <input name="q" value="{{ $q }}" placeholder="Cari nama menu..." class="input md:w-80" />
        <select name="category" class="input md:w-56">
            <option value="">Semua kategori</option>
            <option value="kopi" {{ $category === 'kopi' ? 'selected' : '' }}>Kopi</option>
            <option value="non_kopi" {{ $category === 'non_kopi' ? 'selected' : '' }}>Non-kopi</option>
        </select>
        <button class="rounded-xl bg-zinc-100 px-4 py-3 text-sm font-semibold hover:bg-zinc-200">Filter</button>
        <a href="{{ route('admin.menu.index') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-semibold text-zinc-700 hover:bg-zinc-50">Reset</a>
    </form>

    <div class="mt-6 overflow-hidden rounded-3xl border border-zinc-200 bg-white shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Nama</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4">Harga</th>
                        <th class="px-5 py-4">Unggulan</th>
                        <th class="px-5 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse ($menu as $m)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="font-semibold">{{ $m->name }}</div>
                                <div class="mt-1 text-xs text-zinc-500">{{ $m->description }}</div>
                            </td>
                            <td class="px-5 py-4 text-zinc-800">{{ $m->category === 'kopi' ? 'Kopi' : 'Non-kopi' }}</td>
                            <td class="px-5 py-4 text-zinc-800">Rp {{ number_format($m->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                @if ($m->is_featured)
                                    <span class="badge bg-brand-50 text-brand-700">ya</span>
                                @else
                                    <span class="badge bg-zinc-100 text-zinc-700">tidak</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.menu.edit', $m) }}" class="rounded-lg bg-zinc-100 px-3 py-2 text-xs font-semibold hover:bg-zinc-200">Edit</a>
                                    <form method="POST" action="{{ route('admin.menu.destroy', $m) }}" onsubmit="return confirm('Hapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-zinc-600">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-zinc-200 px-5 py-4">
            {{ $menu->links() }}
        </div>
    </div>
@endsection
