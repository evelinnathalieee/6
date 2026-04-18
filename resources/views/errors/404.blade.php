@extends('layouts.public')

@section('title', '404 — Halaman Tidak Ditemukan')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-zinc-200 bg-white p-10 text-center">
        <div class="text-sm font-semibold text-zinc-600">404</div>
        <h1 class="mt-2 text-2xl font-semibold tracking-tight">Halaman tidak ditemukan</h1>
        <p class="mt-3 text-sm text-zinc-600">Cek lagi URL-nya, atau kembali ke landing page.</p>
        <div class="mt-6 flex justify-center gap-3">
            <a href="{{ route('home') }}" class="btn-primary">Ke Landing Page</a>
            <a href="{{ route('menu') }}" class="btn-secondary">Lihat Menu</a>
        </div>
    </div>
@endsection
