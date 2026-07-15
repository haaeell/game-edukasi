@extends('layouts.admin')

@section('page-title', $set->title)
@section('page-description', 'Kelola daftar kartu di dalam set ini.')

@section('content')
    <div class="space-y-6">
        <div class="panel p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-sm text-slate-500">{{ $set->status }}</p>
                    <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $set->title }}</h2>
                    <p class="mt-2 max-w-2xl text-sm text-slate-600">{{ $set->description }}</p>
                </div>
                <a href="{{ route('admin.game-cards.create', $set) }}" class="btn-primary">Tambah Kartu</a>
            </div>
        </div>

        <div class="grid gap-4">
            @forelse ($set->cards as $card)
                <div class="panel p-5">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="text-sm font-semibold text-sky-700">Urutan {{ $card->order_number }}</div>
                            <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $card->title }}</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $card->question }}</p>
                            <div class="mt-3 text-xs text-slate-500">Status: {{ $card->status }}</div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.game-cards.edit', $card) }}" class="btn-secondary">Edit</a>
                            <form action="{{ route('admin.game-cards.destroy', [$set, $card]) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="panel p-6 text-sm text-slate-500">Belum ada kartu pada card set ini.</div>
            @endforelse
        </div>
    </div>
@endsection
