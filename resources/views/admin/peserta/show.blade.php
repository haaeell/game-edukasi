@extends('layouts.admin')

@section('page-title', 'Detail Peserta: '.$peserta->name)
@section('page-description', 'Biodata lengkap dan aktivitas peserta di platform.')

@section('content')
    <div class="space-y-6">
        <div class="panel p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
                    @if ($peserta->photo_url)
                        <img src="{{ $peserta->photo_url }}" alt="{{ $peserta->name }}" class="h-20 w-20 rounded-2xl object-cover shadow-sm ring-2 ring-white">
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-2xl font-bold text-white shadow-sm">{{ strtoupper(mb_substr($peserta->name, 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="flex flex-wrap items-center justify-center gap-3 sm:justify-start">
                            <h2 class="text-2xl font-bold text-slate-900">{{ $peserta->name }}</h2>
                            @if ($peserta->status === 'active')
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                            @else
                                <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Nonaktif</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-slate-500">Terdaftar sejak {{ $peserta->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center gap-3">
                    <form action="{{ route('admin.peserta.toggle-status', $peserta) }}" method="POST">
                        @csrf
                        @if ($peserta->status === 'active')
                            <button type="submit" class="btn-secondary text-rose-600">
                                <i class="fa-solid fa-user-slash mr-2"></i>Nonaktifkan Akun
                            </button>
                        @else
                            <button type="submit" class="btn-primary">
                                <i class="fa-solid fa-user-check mr-2"></i>Aktifkan Akun
                            </button>
                        @endif
                    </form>
                    <a href="{{ route('admin.peserta.index') }}" class="btn-secondary">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Email</div>
                    <div class="mt-2 break-words text-sm font-bold text-slate-900">{{ $peserta->email }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Nomor Telepon</div>
                    <div class="mt-2 text-sm font-bold text-slate-900">{{ $peserta->phone ?: '-' }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4 sm:col-span-2">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Alamat</div>
                    <div class="mt-2 text-sm font-bold text-slate-900">{{ $peserta->address ?: '-' }}</div>
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-lg text-blue-600"><i class="fa-solid fa-crown"></i></div>
                    <div class="mt-3 text-2xl font-bold text-slate-900">{{ $stats['hostedRooms'] }}</div>
                    <div class="mt-1 text-sm text-slate-500">Room yang dibuat (host)</div>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-50 text-lg text-violet-600"><i class="fa-solid fa-people-group"></i></div>
                    <div class="mt-3 text-2xl font-bold text-slate-900">{{ $stats['roomParticipants'] }}</div>
                    <div class="mt-1 text-sm text-slate-500">Room yang diikuti</div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="panel p-6">
                <h3 class="text-lg font-bold text-slate-900">Room Terakhir Dibuat</h3>
                <div class="mt-4 space-y-2.5">
                    @forelse ($peserta->hostedRooms as $room)
                        @php
                            $statusStyle = match ($room->status) {
                                'playing' => 'bg-cyan-50 text-cyan-700',
                                'finished' => 'bg-emerald-50 text-emerald-700',
                                default => 'bg-amber-50 text-amber-700',
                            };
                            $statusLabel = match ($room->status) {
                                'playing' => 'Berlangsung',
                                'finished' => 'Selesai',
                                default => 'Menunggu',
                            };
                        @endphp
                        <a href="{{ route('admin.room-reports.show', $room) }}" class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 p-3 transition hover:border-blue-200 hover:bg-blue-50/40">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-slate-900">{{ $room->title }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">{{ $room->code }} • {{ $room->cardSet->title ?? '-' }}</div>
                            </div>
                            <span class="shrink-0 rounded-full {{ $statusStyle }} px-3 py-1 text-xs font-semibold">{{ $statusLabel }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Peserta ini belum pernah membuat room.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel p-6">
                <h3 class="text-lg font-bold text-slate-900">Room Terakhir Diikuti</h3>
                <div class="mt-4 space-y-2.5">
                    @forelse ($peserta->roomParticipants as $participation)
                        <a href="{{ route('admin.room-reports.show', $participation->room) }}" class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 p-3 transition hover:border-blue-200 hover:bg-blue-50/40">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-slate-900">{{ $participation->room->title ?? '-' }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">Gabung {{ $participation->joined_at?->format('d M Y H:i') }} {{ $participation->is_host ? '• Sebagai Host' : '' }}</div>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500">Peserta ini belum pernah mengikuti room.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
