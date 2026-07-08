@extends('layouts.user')

@section('content')
    <section class="">
        <div class="panel p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h2 class="text-4xl font-bold tracking-tight text-slate-900">Halo, {{ auth()->user()->name }}! <i class="fa-solid fa-leaf text-emerald-500"></i></h2>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-500">Mari jaga ritme belajarmu hari ini. Kamu bisa membaca artikel, menonton video, dan masuk ke sesi refleksi interaktif dari satu dashboard yang ringan.</p>
                </div>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <a href="{{ route('user.articles.index') }}" class="metric-card p-6 transition hover:-translate-y-1">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-700"><i class="fa-regular fa-file-lines"></i></div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-900">Artikel</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Baca artikel seputar self-reflection, kesehatan mental, dan pengembangan diri.</p>
                    <span class="mt-6 inline-flex rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">Jelajahi Artikel →</span>
                </a>
                <a href="{{ route('user.videos.index') }}" class="metric-card p-6 transition hover:-translate-y-1">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-50 text-2xl text-violet-700"><i class="fa-regular fa-circle-play"></i></div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-900">Video</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Tonton video edukasi untuk memahami emosi dan menjaga wellbeing.</p>
                    <span class="mt-6 inline-flex rounded-full bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-700">Jelajahi Video →</span>
                </a>
                <a href="{{ route('user.game.index') }}" class="metric-card p-6 transition hover:-translate-y-1">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-2xl text-emerald-700"><i class="fa-regular fa-comments"></i></div>
                    <h3 class="mt-5 text-2xl font-bold text-slate-900">Game</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-500">Ikuti sesi refleksi kelompok atau individu melalui room interaktif berbasis kartu.</p>
                    <span class="mt-6 inline-flex rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">Mulai Game →</span>
                </a>
            </div>
        </div>


    </section>
@endsection
