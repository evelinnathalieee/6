@extends('layouts.public')

@section('title', 'Daftar — Westland Coffee')

@section('content')
    <div class="mx-auto max-w-lg rounded-3xl border border-zinc-200 bg-white p-8">
        <h1 class="text-2xl font-semibold tracking-tight">Daftar</h1>
        <p class="mt-2 text-sm text-zinc-600">Buat akun untuk fitur member (stamp, reward, riwayat).</p>

        <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="text-sm text-zinc-700">Nama</label>
                <input name="name" value="{{ old('name') }}" class="input" required />
                @error('name') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input" required />
                @error('email') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">No. HP (opsional)</label>
                <input name="phone" value="{{ old('phone') }}" class="input" />
                @error('phone') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm text-zinc-700">Password</label>
                    <input type="password" name="password" class="input" required />
                    @error('password') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="text-sm text-zinc-700">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="input" required />
                </div>
            </div>

            <button class="w-full rounded-xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-600">
                Daftar
            </button>

            <div class="text-center text-sm text-zinc-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-brand-600 hover:text-brand-700">Login</a>
            </div>
        </form>
    </div>
@endsection
