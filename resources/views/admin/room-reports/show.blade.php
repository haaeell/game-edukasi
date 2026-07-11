@extends('layouts.admin')

@section('page-title', 'Laporan Room: '.$room->title)
@section('page-description', 'Detail lengkap peserta, kartu, dan riwayat chat untuk room ini.')

@section('content')
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
        $avatarPalette = [
            'from-blue-500 to-indigo-600',
            'from-violet-500 to-purple-600',
            'from-emerald-500 to-teal-600',
            'from-amber-500 to-orange-600',
            'from-pink-500 to-rose-600',
        ];
    @endphp

    <div class="space-y-6">
        <div class="panel p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-2xl font-bold text-slate-900">{{ $room->title }}</h2>
                        <span class="rounded-full {{ $statusStyle }} px-3 py-1 text-xs font-semibold">{{ $statusLabel }}</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">Kode <span class="font-semibold text-slate-700">{{ $room->code }}</span> • Dibuat {{ $room->created_at->format('d M Y H:i') }}</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    @if ($room->status !== 'finished')
                        <form
                            action="{{ route('admin.room-reports.end', $room) }}"
                            method="POST"
                            class="js-room-action-form"
                            data-swal-title="Hentikan permainan?"
                            data-swal-text="Permainan pada room ini akan langsung diakhiri."
                            data-swal-confirm="Ya, hentikan"
                            data-swal-confirm-color="#0c74cf"
                        >
                            @csrf
                            <button type="submit" class="btn-secondary">
                                <i class="fa-solid fa-stop mr-2"></i>Hentikan Permainan
                            </button>
                        </form>
                    @endif
                    <form
                        action="{{ route('admin.room-reports.destroy', $room) }}"
                        method="POST"
                        class="js-room-action-form"
                        data-swal-title="Hapus room ini?"
                        data-swal-text="Semua data room dan laporan yang terkait akan ikut terhapus."
                        data-swal-confirm="Ya, hapus"
                        data-swal-confirm-color="#dc2626"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                            <i class="fa-regular fa-trash-can mr-2"></i>Hapus Room
                        </button>
                    </form>
                    <a href="{{ route('admin.room-reports.pdf', $room) }}" class="btn-primary">
                        <i class="fa-solid fa-file-pdf mr-2"></i>Unduh PDF
                    </a>
                    <a href="{{ route('admin.room-reports.index') }}" class="btn-secondary">
                        <i class="fa-solid fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Host</div>
                    <div class="mt-2 text-sm font-bold text-slate-900">{{ $room->host->name ?? '-' }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Card Set</div>
                    <div class="mt-2 text-sm font-bold text-slate-900">{{ $room->cardSet->title ?? '-' }}</div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Durasi Sesi</div>
                    <div class="mt-2 text-sm font-bold text-slate-900">
                        @if ($durationSeconds !== null)
                            {{ gmdate('H:i:s', $durationSeconds) }}
                        @else
                            Belum dimulai
                        @endif
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Guest & Host Main</div>
                    <div class="mt-2 text-sm font-bold text-slate-900">{{ $room->allow_guest ? 'Guest diizinkan' : 'Guest tidak diizinkan' }} • {{ $room->host_is_player ? 'Host ikut main' : 'Host memandu' }}</div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="panel p-6 xl:col-span-1">
                <h3 class="text-lg font-bold text-slate-900">Peserta ({{ $room->participants->count() }})</h3>
                <div class="mt-4 space-y-2.5">
                    @forelse ($room->participants as $participant)
                        @php
                            $initial = strtoupper(mb_substr($participant->public_name, 0, 1));
                            $avatarColor = $avatarPalette[ord($initial ?: 'A') % count($avatarPalette)];
                        @endphp
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 px-3 py-2.5">
                            <div class="relative shrink-0">
                                @if ($participant->photo_url)
                                    <img src="{{ $participant->photo_url }}" alt="{{ $participant->public_name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br {{ $avatarColor }} text-sm font-bold text-white ring-2 ring-white">{{ $initial }}</div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-semibold text-slate-900">{{ $participant->public_name }}</div>
                                <div class="mt-0.5 text-xs text-slate-500">
                                    {{ $participant->participant_type === 'registered' ? 'Terdaftar' : 'Tamu' }}
                                    • Gabung {{ $participant->joined_at?->format('d M H:i') }}
                                    @if ($participant->left_at)
                                        • Keluar {{ $participant->left_at->format('d M H:i') }}
                                    @endif
                                </div>
                            </div>
                            @if ($participant->is_host)
                                <span class="shrink-0 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Host</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada peserta yang bergabung.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel p-6 xl:col-span-1">
                <h3 class="text-lg font-bold text-slate-900">Kartu yang Dimainkan ({{ $openedCards->count() }}/{{ $totalActiveCards }})</h3>
                <div class="mt-4 space-y-2.5">
                    @forelse ($openedCards as $card)
                        <div class="rounded-2xl border border-slate-200 p-3">
                            <div class="text-xs font-semibold uppercase tracking-[0.16em] text-violet-600">Urutan {{ $card['order'] }} • {{ $card['title'] }}</div>
                            <p class="mt-1.5 text-sm leading-6 text-slate-600">{{ $card['question'] }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada kartu yang dibuka pada sesi ini.</p>
                    @endforelse
                </div>
            </div>

            <div class="panel p-6 xl:col-span-1">
                <h3 class="text-lg font-bold text-slate-900">Riwayat Chat ({{ $chatMessageCount }})</h3>
                <div class="mt-4 max-h-[32rem] space-y-3 overflow-y-auto pr-1">
                    @forelse ($messages as $message)
                        @if ($message->message_type === 'system')
                            <div class="rounded-2xl bg-amber-50 px-4 py-2.5 text-center text-xs font-semibold text-amber-800">{{ $message->message }}</div>
                        @else
                            <div class="rounded-2xl border border-slate-200 p-3">
                                <div class="flex items-center justify-between gap-2 text-xs font-semibold text-slate-500">
                                    <span>{{ $message->participant->public_name ?? 'Sistem' }}</span>
                                    <span>{{ $message->created_at->format('d M H:i') }}</span>
                                </div>
                                <p class="mt-1.5 text-sm leading-6 text-slate-700">{{ $message->message }}</p>
                                @if ($message->card)
                                    <div class="mt-1.5 text-xs text-slate-400">Pada kartu: {{ $message->card->title }}</div>
                                @endif
                            </div>
                        @endif
                    @empty
                        <p class="text-sm text-slate-500">Belum ada pesan pada sesi ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="panel p-6">
            <h3 class="text-lg font-bold text-slate-900">Kritik & Saran ({{ $feedbacks->count() }})</h3>
            <p class="mt-1 text-sm text-slate-500">Masukan dari peserta yang dikumpulkan saat mereka meninggalkan room setelah sesi diakhiri.</p>
            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($feedbacks as $feedback)
                    <div class="rounded-2xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between gap-2 text-xs font-semibold text-slate-500">
                            <span>{{ $feedback->participant_name }}</span>
                            <span>{{ $feedback->created_at->format('d M H:i') }}</span>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $feedback->message }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 sm:col-span-2 xl:col-span-3">Belum ada kritik atau saran dari peserta pada sesi ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
