@extends('layouts.base')

@section('body')
    <div class="flex min-h-screen items-center justify-center bg-[#eef4fb] px-0 py-0 sm:px-4 sm:py-10">
        <section class="panel min-h-screen w-full max-w-md rounded-none border-0 p-6 sm:min-h-0 sm:rounded-[1.5rem] sm:border sm:p-8">
            <div class="flex items-center justify-center gap-3">
                <div class="icon-badge flex h-12 w-12 rounded-2xl border border-blue-100 bg-white text-2xl text-blue-600 shadow-sm">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <div class="text-2xl font-bold tracking-tight text-slate-900">RuangKonseling</div>
            </div>

            <div class="mt-8 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Selamat Datang Kembali</h1>
                <p class="mt-2 text-sm text-slate-500">Masuk untuk melanjutkan perjalananmu.</p>
            </div>

            <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="field" placeholder="email@contoh.com" required>
                    @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="field" placeholder="Masukkan password" required>
                    @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between gap-4 text-sm text-slate-500">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-slate-300">
                        Ingat saya
                    </label>
                    <a href="{{ route('login') }}" class="font-semibold text-blue-600">Lupa password?</a>
                </div>

                <button type="submit" class="btn-primary w-full">Masuk</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-blue-600">Daftar sekarang</a>
            </p>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600">Kembali ke landing page</a>
            </div>
        </section>
    </div>
@endsection
