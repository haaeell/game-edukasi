@extends('layouts.admin')

@section('page-title', 'Videos')
@section('page-description', 'Kelola konten video YouTube untuk user.')

@section('content')
    <div class="panel p-6 lg:p-7">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Daftar Video</h2>
                <p class="mt-1 text-sm text-slate-500">Tampilan video dengan thumbnail, metadata, dan aksi cepat.</p>
            </div>
            <a href="{{ route('admin.videos.create') }}" class="btn-primary">Tambah Video</a>
        </div>

        <div class="mt-6 overflow-hidden rounded-[1.8rem] border border-slate-200 bg-white shadow-[0_18px_45px_rgba(15,23,42,0.05)]">
            <div class="hidden grid-cols-[minmax(0,1.7fr)_130px_170px_130px_150px] gap-4 border-b border-slate-200 bg-slate-50 px-6 py-4 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 lg:grid">
                <div>Video</div>
                <div>Status</div>
                <div>Author</div>
                <div>Tanggal</div>
                <div class="text-right">Aksi</div>
            </div>

            @forelse ($videos as $video)
                <div class="border-b border-slate-100 px-4 py-4 transition hover:bg-slate-50/70 last:border-b-0 sm:px-6">
                    <div class="grid gap-4 lg:grid-cols-[minmax(0,1.7fr)_130px_170px_130px_150px] lg:items-center">
                        <div class="flex min-w-0 items-center gap-4">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="h-20 w-28 shrink-0 rounded-2xl object-cover ring-1 ring-slate-200">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="truncate text-base font-semibold text-slate-900">{{ $video->title }}</div>
                                    <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-amber-700">
                                        <i class="fa-regular fa-circle-play mr-1.5"></i>Video
                                    </span>
                                </div>
                                <div class="mt-2 text-sm text-slate-500">{{ \Illuminate\Support\Str::limit($video->description, 90) }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 lg:hidden">Status</div>
                            <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $video->status === 'published' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ ucfirst($video->status) }}
                            </span>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 lg:hidden">Author</div>
                            <div class="mt-1 text-sm font-medium text-slate-700">{{ $video->creator->name }}</div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 lg:hidden">Tanggal</div>
                            <div class="mt-1 text-sm text-slate-600">{{ $video->created_at->format('d M Y') }}</div>
                        </div>

                        <div class="flex flex-wrap justify-start gap-2 lg:justify-end">
                            <a href="{{ route('admin.videos.edit', $video) }}" class="btn-secondary px-4 py-2">
                                <i class="fa-regular fa-pen-to-square mr-2"></i>Edit
                            </a>
                            <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary px-4 py-2 text-rose-600 hover:bg-rose-50 hover:border-rose-200">
                                    <i class="fa-regular fa-trash-can mr-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-2xl text-slate-400">
                        <i class="fa-regular fa-circle-play"></i>
                    </div>
                    <div class="mt-4 text-lg font-semibold text-slate-900">Belum ada video</div>
                    <p class="mt-2 text-sm text-slate-500">Video yang kamu tambahkan akan tampil di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
