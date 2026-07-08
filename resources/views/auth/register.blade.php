@extends('layouts.base')

@section('body')
    <div class="flex min-h-screen items-center justify-center bg-[#eef4fb] px-0 py-0 sm:px-4 sm:py-10">
        <section class="panel min-h-screen w-full max-w-xl overflow-hidden rounded-none border-0 p-6 sm:min-h-0 sm:rounded-[1.5rem] sm:border sm:p-8">
            <div class="flex items-center justify-center gap-3">
                <div class="icon-badge flex h-12 w-12 rounded-2xl border border-blue-100 bg-white text-2xl text-blue-600 shadow-sm">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <div class="text-2xl font-bold tracking-tight text-slate-900">RuangKonseling</div>
            </div>

            <div class="mt-8 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Buat Akun Baru</h1>
                <p class="mt-2 text-sm text-slate-500">Lengkapi data berikut untuk mulai bergabung.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-5 md:grid-cols-2">
                @csrf
                <div>
                    <label class="label" for="name">Nama Lengkap</label>
                    <input id="name" name="name" value="{{ old('name') }}" class="field" placeholder="Masukkan nama lengkap" required>
                    @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="field" placeholder="email@contoh.com" required>
                    @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="phone">Nomor Telepon</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="field" placeholder="08xxxxxxxxxx" required>
                    @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="photo">Foto Profil</label>
                    <input id="photo" name="photo" type="file" class="field">
                    @error('photo')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="label" for="address">Alamat</label>
                    <textarea id="address" name="address" rows="3" class="field" placeholder="Alamat lengkap" required>{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="field" placeholder="Minimal 8 karakter" required>
                    @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="field" placeholder="Ulangi password" required>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="btn-primary w-full">Daftar</button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-blue-600">Masuk di sini</a>
            </p>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600">Kembali ke landing page</a>
            </div>
        </section>
    </div>
@endsection
