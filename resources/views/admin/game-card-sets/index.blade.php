@extends('layouts.admin')

@section('page-title', 'Game Card Sets')
@section('page-description', 'Kelola kumpulan kartu permainan dengan tampilan yang lebih visual dan modern.')

@section('content')
    <div class="space-y-6">
        <div class="panel p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Daftar Card Set</h2>
                    <p class="mt-2 text-sm text-slate-500">Tiap set ditampilkan seperti tumpukan kartu agar lebih mudah dipindai secara visual.</p>
                </div>
                <a href="{{ route('admin.game-card-sets.create') }}" class="btn-primary">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Tambah Card Set
                </a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 2xl:grid-cols-3">
            @forelse ($sets as $set)
                <div class="metric-card overflow-hidden p-6">
                    <div class="relative">
                        <div class="absolute left-4 top-4 h-48 w-[82%] rounded-[1.8rem] border border-blue-100 bg-gradient-to-br from-[#eaf1ff] to-[#cfdcff] opacity-60"></div>
                        <div class="absolute left-2 top-2 h-48 w-[82%] rounded-[1.8rem] border border-violet-100 bg-gradient-to-br from-[#f3f2ff] to-[#dae0ff] opacity-80"></div>
                        <div class="relative h-48 rounded-[1.8rem] border border-blue-200 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.9),_rgba(255,255,255,0.1)),linear-gradient(135deg,_#2753d7_0%,_#6c8dff_100%)] p-6 text-white shadow-xl shadow-blue-200/60">
                            <div class="flex items-start justify-between gap-4">
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em]">{{ $set->status }}</span>
                                <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold">{{ $set->cards_count }} kartu</span>
                            </div>

                            <div class="mt-10">
                                <div class="text-xs font-semibold uppercase tracking-[0.24em] text-blue-100/80">Card Set</div>
                                <h3 class="mt-3 text-2xl font-bold leading-tight">{{ $set->title }}</h3>
                            </div>

                            <div class="absolute bottom-6 left-6 text-sm text-blue-100/90">
                                <div>{{ $set->creator->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="min-h-[4.5rem] text-sm leading-7 text-slate-500">{{ $set->description ?: 'Belum ada deskripsi untuk card set ini.' }}</p>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <a href="{{ route('admin.game-card-sets.show', $set) }}" class="btn-secondary w-full">
                            <i class="fa-regular fa-eye mr-2"></i>
                            Lihat
                        </a>
                        <a href="{{ route('admin.game-card-sets.edit', $set) }}" class="btn-secondary w-full">
                            <i class="fa-regular fa-pen-to-square mr-2"></i>
                            Edit
                        </a>
                        <form action="{{ route('admin.game-card-sets.destroy', $set) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary w-full text-rose-600">
                                <i class="fa-regular fa-trash-can mr-2"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="panel col-span-full p-10 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-blue-50 text-2xl text-blue-600">
                        <i class="fa-regular fa-clone"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-slate-900">Belum ada card set</h3>
                    <p class="mt-2 text-sm text-slate-500">Mulai dengan membuat card set pertama agar room game bisa digunakan.</p>
                    <a href="{{ route('admin.game-card-sets.create') }}" class="btn-primary mt-6">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah Card Set
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
