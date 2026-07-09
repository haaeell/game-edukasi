@extends('layouts.admin')

@section('page-title', 'Pengaturan Akun')
@section('page-description', 'Perbarui email login admin dan ubah password akun dengan aman.')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="panel overflow-hidden">
            <div class="grid gap-0 lg:grid-cols-[0.9fr_1.3fr]">
                <div class="border-b border-slate-200 bg-[linear-gradient(180deg,_rgba(17,142,233,0.08),_rgba(79,200,62,0.08))] p-8 lg:border-b-0 lg:border-r">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-2xl text-sky-600 shadow-sm">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h2 class="mt-6 text-2xl font-bold text-slate-900">Akun Admin</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        Gunakan email yang aktif dan password yang kuat. Password saat ini diperlukan untuk menyimpan perubahan.
                    </p>

                    <div class="mt-6 rounded-2xl border border-white/70 bg-white/80 p-5">
                        <div class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Info Login</div>
                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                <div>Role: {{ ucfirst($user->role) }}</div>
                            </div>
                            <div>
                                <div class="text-slate-400">Email aktif</div>
                                <div class="font-medium text-slate-900">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.settings.account.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="label" for="email">Email Admin</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="field" required>
                            @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="label" for="current_password">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" class="field" required>
                            <p class="mt-2 text-xs text-slate-500">Wajib diisi untuk verifikasi sebelum perubahan disimpan.</p>
                            @error('current_password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-5">
                            <div class="mb-4">
                                <h3 class="text-sm font-semibold text-slate-900">Ubah Password</h3>
                                <p class="mt-1 text-xs text-slate-500">Kosongkan jika Anda hanya ingin mengganti email.</p>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <div>
                                    <label class="label" for="password">Password Baru</label>
                                    <input id="password" name="password" type="password" class="field">
                                    @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="label" for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="field">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <button type="submit" class="btn-primary w-full sm:w-auto">
                                <i class="fa-solid fa-floppy-disk mr-2"></i>Simpan Pengaturan
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn-secondary w-full sm:w-auto">Kembali ke Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
