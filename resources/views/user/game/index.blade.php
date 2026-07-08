@extends('layouts.user')

@section('content')
    <section class="panel overflow-hidden p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.25em] text-emerald-700">Game Room</p>
                <h1 class="mt-5 text-4xl font-bold text-slate-900">Buat room baru atau masuk ke room yang sudah berjalan.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">Tahap ini fokus pada create room, join room, anonymous mode, guest access, dan waiting room dasar.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('user.game.create') }}" class="btn-primary">Buat Room</a>
                <a href="{{ route('game.join') }}" class="btn-secondary">Join dengan Kode</a>
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Room yang Saya Buat</h2>
            <div class="mt-4 space-y-3">
                @forelse ($hostedRooms as $room)
                    <a href="{{ route('game.rooms.show', $room->code) }}" class="block rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold">{{ $room->title }}</div>
                                <div class="mt-1 text-sm text-slate-500">Kode {{ $room->code }} • {{ ucfirst($room->status) }}</div>
                            </div>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $room->card_flow_type }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Belum ada room yang Anda buat.</p>
                @endforelse
            </div>
        </div>

        <div class="panel p-6">
            <h2 class="text-xl font-bold text-slate-900">Room yang Saya Ikuti</h2>
            <div class="mt-4 space-y-3">
                @forelse ($joinedRooms as $room)
                    <a href="{{ route('game.rooms.show', $room->code) }}" class="block rounded-2xl border border-slate-200 p-4 hover:bg-slate-50">
                        <div class="font-semibold">{{ $room->title }}</div>
                        <div class="mt-1 text-sm text-slate-500">Kode {{ $room->code }} • {{ $room->cardSet->title }}</div>
                    </a>
                @empty
                    <p class="text-sm text-slate-500">Anda belum join room lain.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
