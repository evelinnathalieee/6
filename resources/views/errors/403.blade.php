@extends('layouts.public')

@section('title', '403 — Akses Ditolak')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-zinc-200 bg-white p-10 text-center">
        <div class="text-sm font-semibold text-rose-700">403</div>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight">Akses ditolak</h1>
        <p class="mt-3 text-sm text-zinc-600">Halaman ini tidak bisa diakses dengan role akun kamu.</p>
        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('home') }}" class="btn-primary">Ke Landing Page</a>
            <a href="{{ route('login') }}" class="btn-secondary">Login</a>
        </div>
    </div>
@endsection
