@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')
@section('page-description', 'Kelola platform edukasi, pengguna, konten, dan laporan dari satu tempat.')

@section('content')
    <section class="panel p-5 lg:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 lg:text-[2rem]">Selamat datang, {{ auth()->user()->name }} <i class="fa-regular fa-hand-wave text-blue-500"></i></h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Ringkasan aktivitas platform edukasi secara keseluruhan, dari konten hingga sesi game room yang sedang berjalan.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-600">
                {{ now()->format('d M Y') }}
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ([
                ['label' => 'Total Users', 'value' => $stats['totalUsers'], 'icon' => 'fa-solid fa-users', 'delta' => '+12.5%', 'tone' => 'text-blue-600'],
                ['label' => 'Total Artikel', 'value' => $stats['totalArticles'], 'icon' => 'fa-regular fa-newspaper', 'delta' => '+8.1%', 'tone' => 'text-emerald-600'],
                ['label' => 'Total Video', 'value' => $stats['totalVideos'], 'icon' => 'fa-regular fa-circle-play', 'delta' => '+9.4%', 'tone' => 'text-violet-600'],
                ['label' => 'Total Set Kartu', 'value' => $stats['totalCardSets'], 'icon' => 'fa-regular fa-clone', 'delta' => '+11.3%', 'tone' => 'text-amber-600'],
                ['label' => 'Sesi Aktif', 'value' => $stats['activeRooms'], 'icon' => 'fa-regular fa-comments', 'delta' => '+5.6%', 'tone' => 'text-cyan-600'],
                ['label' => 'Game Selesai', 'value' => $stats['finishedGames'], 'icon' => 'fa-regular fa-file-lines', 'delta' => '-3.2%', 'tone' => 'text-rose-600'],
            ] as $card)
                <div class="metric-card p-5 lg:p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-50 text-2xl text-slate-700 shadow-sm">
                            <i class="{{ $card['icon'] }}"></i>
                        </div>
                        <span class="text-xs font-bold {{ $card['tone'] }}">{{ $card['delta'] }}</span>
                    </div>

                    <div class="mt-6 text-4xl font-bold tracking-tight text-slate-900">{{ number_format($card['value']) }}</div>
                    <div class="mt-2 text-sm font-medium text-slate-500">{{ $card['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
