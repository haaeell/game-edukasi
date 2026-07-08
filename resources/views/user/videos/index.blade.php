@extends('layouts.user')

@section('content')
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Video</h1>
            <p class="mt-2 text-sm text-slate-500">Kumpulan video published yang siap ditonton.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-amber-100 bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
            <i class="fa-regular fa-circle-play"></i>
            <span>{{ $videos->total() }} video tersedia</span>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($videos as $video)
            <a href="{{ route('user.videos.show', $video->slug) }}" class="panel group overflow-hidden border border-slate-200/80 bg-white transition duration-300 hover:-translate-y-1 hover:border-amber-200 hover:shadow-[0_24px_60px_rgba(217,119,6,0.14)]">
                <div class="relative aspect-video overflow-hidden bg-slate-100">
                    <img
                        src="{{ $video->thumbnail_url }}"
                        alt="{{ $video->title }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/25 via-transparent to-transparent"></div>
                    <div class="absolute left-4 top-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/90 text-lg text-amber-600 shadow-sm backdrop-blur">
                        <i class="fa-solid fa-play"></i>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $video->created_at->format('d M Y') }}</span>
                    </div>
                    <h2 class="mt-3 line-clamp-2 text-xl font-bold text-slate-900">{{ $video->title }}</h2>
                    <p class="mt-3 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($video->description, 100) }}</p>
                    <div class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-amber-700">
                        <span>Tonton video</span>
                        <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="panel col-span-full px-6 py-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-2xl text-slate-400">
                    <i class="fa-regular fa-circle-play"></i>
                </div>
                <p class="mt-4 text-sm text-slate-500">Belum ada video published.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $videos->links() }}
    </div>
@endsection
