@extends('layouts.user')

@section('content')
    <div class="mb-8 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Artikel</h1>
            <p class="mt-2 text-sm text-slate-500">Kumpulan artikel published untuk user.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full border border-sky-100 bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-sky-700">
            <i class="fa-regular fa-newspaper"></i>
            <span>{{ $articles->total() }} artikel tersedia</span>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($articles as $article)
            <a href="{{ route('user.articles.show', $article->slug) }}" class="panel group overflow-hidden border border-slate-200/80 bg-white transition duration-300 hover:-translate-y-1 hover:border-sky-200 hover:shadow-[0_24px_60px_rgba(37,99,235,0.12)]">
                <div class="relative h-44 overflow-hidden bg-slate-100">
                    <img
                        src="{{ $article->thumbnail_url }}"
                        alt="{{ $article->title }}"
                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    >
                    <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-slate-950/20 to-transparent"></div>
                    <div class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-700 shadow-sm backdrop-blur">
                        <i class="fa-regular fa-bookmark"></i>
                        <span>Artikel Edukasi</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $article->created_at->format('d M Y') }}</span>
                    </div>
                    <h2 class="mt-3 line-clamp-2 text-xl font-bold text-slate-900">{{ $article->title }}</h2>
                    <p class="mt-3 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 110) }}</p>
                    <div class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-sky-700">
                        <span>Baca selengkapnya</span>
                        <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                    </div>
                </div>
            </a>
        @empty
            <div class="panel col-span-full px-6 py-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-2xl text-slate-400">
                    <i class="fa-regular fa-newspaper"></i>
                </div>
                <p class="mt-4 text-sm text-slate-500">Belum ada artikel published.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
@endsection
