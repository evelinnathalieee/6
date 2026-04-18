@extends('layouts.member')

@section('title', 'Edit Profil — Westland Coffee')

@section('content')
    <x-page.title title="Edit Profil" subtitle="Ubah data akun kamu." />

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)] md:col-span-2">
            <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50">
                        @if ($user->avatarSrc())
                            <img src="{{ $user->avatarSrc() }}" alt="{{ $user->name }}" class="h-full w-full object-cover" />
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="text-sm text-zinc-700">Foto profil</label>
                        <input type="file" name="avatar" accept="image/*" class="input mt-1" />
                        @error('avatar') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm text-zinc-700">Nama</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="input" required />
                    @error('name') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="text-sm text-zinc-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="input" required />
                    @error('email') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="text-sm text-zinc-700">No. HP</label>
                    <input name="phone" value="{{ old('phone', $user->phone) }}" class="input" />
                    @error('phone') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div class="flex gap-3">
                    <button class="btn-primary">Simpan</button>
                    <a href="{{ route('member.profile') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-[0_1px_0_rgba(0,0,0,0.04)]">
            <div class="text-sm font-extrabold">Ubah Password</div>
            <form method="POST" action="{{ route('member.profile.password') }}" class="mt-4 space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm text-zinc-700">Password saat ini</label>
                    <input type="password" name="current_password" class="input" required />
                    @error('current_password') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="text-sm text-zinc-700">Password baru</label>
                    <input type="password" name="password" class="input" required />
                    @error('password') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="text-sm text-zinc-700">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="input" required />
                </div>

                <button class="btn-primary w-full">Update Password</button>
            </form>
        </div>
    </div>
@endsection

