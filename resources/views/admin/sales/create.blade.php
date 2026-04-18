@extends('layouts.admin')

@section('title', 'Catat Transaksi — Admin Westland Coffee')

@section('content')
    <x-page.title title="Catat Transaksi" subtitle="Cocok untuk kasir: catat order dine in / take away." />

    @error('items')
        <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('admin.sales.store') }}" class="mt-8 rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
        @csrf

        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2">
                <label class="text-sm text-zinc-700">Email member (opsional)</label>
                <input name="member_email" value="{{ old('member_email', $prefillMemberEmail ?? '') }}" class="input" placeholder="contoh: member@email.com" />
                <div class="mt-1 text-xs text-zinc-500">Jika diisi & cocok dengan akun member, stamp bertambah otomatis. Reward juga bisa dipakai tanpa klaim.</div>
                @error('member_email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror

                <label class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-zinc-800">
                    <input type="checkbox" name="use_reward" value="1" {{ old('use_reward') ? 'checked' : '' }} class="h-5 w-5 rounded border-zinc-300 text-brand-600 focus:ring-brand-500">
                    Gunakan 1 reward (jika tersedia)
                </label>
                @error('use_reward') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Tanggal/Waktu (opsional)</label>
                <input type="datetime-local" name="purchased_at" value="{{ old('purchased_at') }}" class="input" />
                @error('purchased_at') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-sm text-zinc-700">Tipe pesanan</label>
                <select name="order_type" class="input" required>
                    <option value="dine_in" {{ old('order_type') === 'dine_in' ? 'selected' : '' }}>Dine in</option>
                    <option value="take_away" {{ old('order_type') === 'take_away' ? 'selected' : '' }}>Take away</option>
                </select>
                @error('order_type') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm text-zinc-700">Metode bayar</label>
                <select name="payment_method" class="input" required>
                    <option value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
                @error('payment_method') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="text-sm text-zinc-700">Promo (opsional)</label>
                <select name="promo_id" class="input">
                    <option value="">— tanpa promo —</option>
                    @foreach ($promos ?? [] as $p)
                        <option value="{{ $p->id }}" {{ (string) old('promo_id') === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->discountLabel() }}{{ (int) $p->min_subtotal > 0 ? ', min Rp '.number_format((int) $p->min_subtotal, 0, ',', '.') : '' }})
                        </option>
                    @endforeach
                </select>
                @error('promo_id') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
            </div>
            <div class="rounded-2xl border border-brand-200 bg-brand-50 p-4 text-sm text-zinc-700 md:col-span-1">
                Nomor pemesanan dibuat otomatis saat transaksi disimpan.
            </div>
        </div>

        <div class="mt-6">
            <label class="text-sm text-zinc-700">Catatan</label>
            <textarea name="note" rows="2" class="input" required>{{ old('note') }}</textarea>
            @error('note') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>

        <div class="mt-8 overflow-hidden rounded-2xl border border-zinc-200">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-zinc-200 bg-zinc-50 text-xs uppercase tracking-wide text-zinc-600">
                    <tr>
                        <th class="px-5 py-4">Menu</th>
                        <th class="px-5 py-4">Qty</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td class="px-5 py-3">
                                <select name="items[{{ $i }}][menu_item_id]" class="input">
                                    <option value="">— pilih menu —</option>
                                    @foreach ($menuItems as $m)
                                        <option value="{{ $m->id }}" {{ (string) old("items.$i.menu_item_id") === (string) $m->id ? 'selected' : '' }}>
                                            {{ $m->name }} (Rp {{ number_format($m->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error("items.$i.menu_item_id") <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                            </td>
                            <td class="px-5 py-3">
                                <input name="items[{{ $i }}][quantity]" value="{{ old("items.$i.quantity") }}" class="input" placeholder="1" />
                                @error("items.$i.quantity") <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex gap-3">
            <button class="btn-primary">Simpan Transaksi</button>
            <a href="{{ route('admin.sales.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
@endsection
