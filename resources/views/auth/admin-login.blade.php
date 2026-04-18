@extends('layouts.public')

@section('title', 'Login — Westland Coffee')

@section('content')
    <div class="mx-auto max-w-lg rounded-3xl border border-zinc-200 bg-white p-8">
        <h1 class="text-2xl font-semibold tracking-tight">Login</h1>
        <p class="mt-2 text-sm text-zinc-600">Masuk untuk fitur admin/member sesuai role akun.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="text-sm text-zinc-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input" required />
                @error('email') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <div>
                <label class="text-sm text-zinc-700">Password</label>
                <input type="password" name="password" class="input" required />
                @error('password') <div class="mt-1 text-xs text-rose-300">{{ $message }}</div> @enderror
            </div>

            <button class="w-full rounded-xl bg-brand-500 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-600">
                Login Admin
            </button>

            <div class="text-center text-sm text-zinc-600">
                <a href="{{ route('home') }}" class="font-semibold text-brand-600 hover:text-brand-700">Kembali ke landing page</a>
            </div>
        </form>
    </div>
@endsection
