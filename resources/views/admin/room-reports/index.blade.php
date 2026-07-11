@extends('layouts.admin')

@section('page-title', 'Laporan Room')
@section('page-description', 'Pantau dan unduh laporan lengkap setiap sesi game room.')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Total Room', 'value' => $summary['total'], 'icon' => 'fa-regular fa-file-lines', 'tone' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                ['label' => 'Menunggu', 'value' => $summary['waiting'], 'icon' => 'fa-regular fa-hourglass-half', 'tone' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                ['label' => 'Sedang Berlangsung', 'value' => $summary['playing'], 'icon' => 'fa-regular fa-comments', 'tone' => 'text-cyan-600', 'bg' => 'bg-cyan-50'],
                ['label' => 'Selesai', 'value' => $summary['finished'], 'icon' => 'fa-solid fa-circle-check', 'tone' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
            ] as $card)
                <div class="metric-card p-5">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $card['bg'] }} text-xl {{ $card['tone'] }}"><i class="{{ $card['icon'] }}"></i></div>
                    <div class="mt-5 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($card['value']) }}</div>
                    <div class="mt-1 text-sm font-medium text-slate-500">{{ $card['label'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="panel p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Daftar Room</h2>
                    <p class="mt-2 text-sm text-slate-500">Klik "Lihat Laporan" untuk detail lengkap peserta dan riwayat chat, atau unduh langsung sebagai PDF.</p>
                </div>

                <form action="{{ route('admin.room-reports.index') }}" method="GET" class="flex w-full flex-col gap-3 lg:w-auto sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari judul atau kode room..." class="field w-full sm:w-64">
                    <select name="status" class="field w-full sm:w-44" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (['waiting' => 'Menunggu', 'playing' => 'Berlangsung', 'finished' => 'Selesai'] as $value => $label)
                            <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-secondary w-full sm:w-auto">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>Cari
                    </button>
                </form>
            </div>

            <div class="mt-6 space-y-4 lg:hidden">
                @forelse ($rooms as $room)
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
                    <div class="admin-mobile-card p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-slate-900">{{ $room->title }}</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-400">{{ $room->code }}</div>
                            </div>
                            <span class="rounded-full {{ $statusStyle }} px-3 py-1 text-xs font-semibold">{{ $statusLabel }}</span>
                        </div>

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Host</span>
                                <span class="admin-detail-pair-value">{{ $room->host->name ?? '-' }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Card Set</span>
                                <span class="admin-detail-pair-value">{{ $room->cardSet->title ?? '-' }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Peserta</span>
                                <span class="admin-detail-pair-value">{{ $room->participants_count }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Pesan</span>
                                <span class="admin-detail-pair-value">{{ $room->messages_count }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Dibuat</span>
                                <span class="admin-detail-pair-value">{{ $room->created_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                            @if ($room->status !== 'finished')
                                <form
                                    action="{{ route('admin.room-reports.end', $room) }}"
                                    method="POST"
                                    class="js-room-action-form w-full"
                                    data-swal-title="Hentikan permainan?"
                                    data-swal-text="Permainan pada room ini akan langsung diakhiri."
                                    data-swal-confirm="Ya, hentikan"
                                    data-swal-confirm-color="#0c74cf"
                                >
                                    @csrf
                                    <button type="submit" class="btn-secondary w-full">
                                        <i class="fa-solid fa-stop mr-2"></i>Hentikan
                                    </button>
                                </form>
                            @endif
                            <form
                                action="{{ route('admin.room-reports.destroy', $room) }}"
                                method="POST"
                                class="js-room-action-form w-full"
                                data-swal-title="Hapus room ini?"
                                data-swal-text="Semua data room dan laporan yang terkait akan ikut terhapus."
                                data-swal-confirm="Ya, hapus"
                                data-swal-confirm-color="#dc2626"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                                    <i class="fa-regular fa-trash-can mr-2"></i>Hapus
                                </button>
                            </form>
                            <a href="{{ route('admin.room-reports.show', $room) }}" class="btn-secondary w-full">
                                <i class="fa-regular fa-eye mr-2"></i>Lihat
                            </a>
                            <a href="{{ route('admin.room-reports.pdf', $room) }}" class="btn-primary w-full">
                                <i class="fa-solid fa-file-pdf mr-2"></i>PDF
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="panel p-10 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"><i class="fa-regular fa-file-lines"></i></div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada room</h3>
                        <p class="mt-1 text-sm text-slate-500">Laporan akan muncul di sini setelah ada room game yang dibuat.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 hidden overflow-x-auto lg:block">
                <table class="w-full min-w-[900px] border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                            <th class="py-3 pr-4">Room</th>
                            <th class="py-3 pr-4">Host</th>
                            <th class="py-3 pr-4">Card Set</th>
                            <th class="py-3 pr-4 text-center">Peserta</th>
                            <th class="py-3 pr-4 text-center">Pesan</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Dibuat</th>
                            <th class="py-3 pr-0 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rooms as $room)
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
                            <tr class="align-middle hover:bg-slate-50/60">
                                <td class="py-4 pr-4">
                                    <div class="font-semibold text-slate-900">{{ $room->title }}</div>
                                    <div class="mt-0.5 text-xs uppercase tracking-[0.16em] text-slate-400">{{ $room->code }}</div>
                                </td>
                                <td class="py-4 pr-4 text-slate-600">{{ $room->host->name ?? '-' }}</td>
                                <td class="py-4 pr-4 text-slate-600">{{ $room->cardSet->title ?? '-' }}</td>
                                <td class="py-4 pr-4 text-center text-slate-600">{{ $room->participants_count }}</td>
                                <td class="py-4 pr-4 text-center text-slate-600">{{ $room->messages_count }}</td>
                                <td class="py-4 pr-4">
                                    <span class="rounded-full {{ $statusStyle }} px-3 py-1 text-xs font-semibold">{{ $statusLabel }}</span>
                                </td>
                                <td class="py-4 pr-4 text-slate-500">{{ $room->created_at->format('d M Y H:i') }}</td>
                                <td class="py-4 pr-0">
                                    <div class="flex items-center justify-end gap-2">
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
                                                    <i class="fa-solid fa-stop mr-2"></i>Hentikan
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
                                                <i class="fa-regular fa-trash-can mr-2"></i>Hapus
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.room-reports.show', $room) }}" class="btn-secondary">
                                            <i class="fa-regular fa-eye mr-2"></i>Lihat
                                        </a>
                                        <a href="{{ route('admin.room-reports.pdf', $room) }}" class="btn-primary">
                                            <i class="fa-solid fa-file-pdf mr-2"></i>PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"><i class="fa-regular fa-file-lines"></i></div>
                                    <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada room</h3>
                                    <p class="mt-1 text-sm text-slate-500">Laporan akan muncul di sini setelah ada room game yang dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($rooms->hasPages())
                <div class="mt-6">
                    {{ $rooms->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
