@extends('layouts.user')

@push('styles')
    <style>
        @keyframes float-card-a {
            0%, 100% { transform: translateY(0) rotate(-8deg); }
            50% { transform: translateY(-14px) rotate(-4deg); }
        }

        @keyframes float-card-b {
            0%, 100% { transform: translateY(0) rotate(6deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        @keyframes float-card-c {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .float-card-a { animation: float-card-a 5.5s ease-in-out infinite; }
        .float-card-b { animation: float-card-b 6.5s ease-in-out infinite; }
        .float-card-c { animation: float-card-c 5s ease-in-out infinite; }

        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 0.6; }
            80% { transform: scale(1.6); opacity: 0; }
            100% { transform: scale(1.6); opacity: 0; }
        }

        .pulse-ring::before,
        .pulse-ring::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 9999px;
            border: 2px solid rgba(59, 130, 246, 0.35);
            animation: pulse-ring 2.8s ease-out infinite;
        }

        .pulse-ring::after {
            animation-delay: 1.4s;
        }

        .step-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .step-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 45px rgba(15, 23, 42, 0.1);
        }
    </style>
@endpush

@section('content')
    <section class="panel overflow-hidden p-8">
        <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Game Room</p>
                <h1 class="mt-5 text-4xl font-bold text-slate-900">Buat room baru atau masuk ke room yang sudah berjalan.</h1>
                <p class="mt-3 text-sm leading-7 text-slate-600">Tahap ini fokus pada create room, join room, anonymous mode, guest access, dan waiting room dasar.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('user.game.create') }}" class="btn-primary">Buat Room</a>
                    <a href="{{ route('game.join') }}" class="btn-secondary">Join dengan Kode</a>
                </div>
            </div>

            <div class="relative mx-auto hidden h-48 w-64 shrink-0 sm:block">
                <div class="pulse-ring absolute left-1/2 top-1/2 h-16 w-16 -translate-x-1/2 -translate-y-1/2"></div>
                <div class="float-card-a absolute left-2 top-6 flex h-28 w-20 items-center justify-center rounded-2xl border border-blue-200/80 bg-gradient-to-b from-[#dbe7ff] to-[#a9c3ff] text-2xl text-white shadow-lg">
                    <i class="fa-regular fa-heart"></i>
                </div>
                <div class="float-card-c absolute left-1/2 top-2 flex h-28 w-20 -translate-x-1/2 items-center justify-center rounded-2xl border border-violet-200/80 bg-gradient-to-b from-white to-[#eef0ff] text-2xl text-violet-600 shadow-xl">
                    <i class="fa-solid fa-shuffle"></i>
                </div>
                <div class="float-card-b absolute right-2 top-8 flex h-28 w-20 items-center justify-center rounded-2xl border border-emerald-200/80 bg-gradient-to-b from-[#d7fbe8] to-[#a6f0c6] text-2xl text-emerald-700 shadow-lg">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-5 sm:grid-cols-3">
        @foreach ([
            ['icon' => 'fa-solid fa-layer-group', 'accent' => 'bg-blue-50 text-blue-700', 'title' => '1. Buat atau Join Room', 'desc' => 'Pilih card set favoritmu lalu buat room, atau masuk pakai kode dari teman.'],
            ['icon' => 'fa-solid fa-user-secret', 'accent' => 'bg-violet-50 text-violet-700', 'title' => '2. Atur Mode & Peserta', 'desc' => 'Aktifkan mode anonymous, izinkan guest join, dan undang teman lewat email.'],
            ['icon' => 'fa-solid fa-comments', 'accent' => 'bg-emerald-50 text-emerald-700', 'title' => '3. Mulai Ngobrol', 'desc' => 'Host membuka kartu satu per satu, semua peserta bisa saling merespons lewat chat.'],
        ] as $step)
            <div class="step-card panel p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $step['accent'] }} text-xl"><i class="{{ $step['icon'] }}"></i></div>
                <h3 class="mt-4 text-base font-bold text-slate-900">{{ $step['title'] }}</h3>
                <p class="mt-2 text-sm leading-6 text-slate-500">{{ $step['desc'] }}</p>
            </div>
        @endforeach
    </section>
@endsection
