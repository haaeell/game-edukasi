@extends('layouts.admin')

@section('page-title', 'Peserta')
@section('page-description', 'Daftar pengguna yang sudah mendaftar beserta biodata dan aktivitasnya.')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Total Peserta', 'value' => $summary['total'], 'icon' => 'fa-solid fa-users', 'tone' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                ['label' => 'Aktif', 'value' => $summary['active'], 'icon' => 'fa-solid fa-circle-check', 'tone' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                ['label' => 'Nonaktif', 'value' => $summary['inactive'], 'icon' => 'fa-solid fa-circle-xmark', 'tone' => 'text-rose-600', 'bg' => 'bg-rose-50'],
                ['label' => 'Baru Bulan Ini', 'value' => $summary['newThisMonth'], 'icon' => 'fa-solid fa-user-plus', 'tone' => 'text-violet-600', 'bg' => 'bg-violet-50'],
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
                    <h2 class="text-2xl font-bold text-slate-900">Daftar Peserta</h2>
                    <p class="mt-2 text-sm text-slate-500">Klik "Lihat" untuk detail biodata lengkap dan aktivitas peserta.</p>
                </div>

                <form action="{{ route('admin.peserta.index') }}" method="GET" class="flex w-full flex-col gap-3 lg:w-auto sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Cari nama, email, atau telepon..." class="field w-full sm:w-64">
                    <select name="status" class="field w-full sm:w-44" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($filters['status'] === 'active')>Aktif</option>
                        <option value="inactive" @selected($filters['status'] === 'inactive')>Nonaktif</option>
                    </select>
                    <button type="submit" class="btn-secondary w-full sm:w-auto">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>Cari
                    </button>
                </form>
            </div>

            <div class="mt-6 space-y-4 lg:hidden">
                @forelse ($peserta as $item)
                    <div class="admin-mobile-card p-5">
                        <div class="flex items-center gap-3">
                            @if ($item->photo_url)
                                <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="h-12 w-12 rounded-full object-cover ring-2 ring-white">
                            @else
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white ring-2 ring-white">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="truncate font-semibold text-slate-900">{{ $item->name }}</div>
                                <div class="truncate text-xs text-slate-400">{{ $item->email }}</div>
                            </div>
                        </div>

                        <div class="mt-4 space-y-3 text-sm">
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Kontak</span>
                                <span class="admin-detail-pair-value">{{ $item->phone ?: '-' }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Room Dibuat</span>
                                <span class="admin-detail-pair-value">{{ $item->hosted_rooms_count }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Room Diikuti</span>
                                <span class="admin-detail-pair-value">{{ $item->room_participants_count }}</span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Status</span>
                                <span class="admin-detail-pair-value">
                                    @if ($item->status === 'active')
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Nonaktif</span>
                                    @endif
                                </span>
                            </div>
                            <div class="admin-detail-pair">
                                <span class="admin-detail-pair-label">Terdaftar</span>
                                <span class="admin-detail-pair-value">{{ $item->created_at->format('d M Y') }}</span>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-2 sm:flex-row">
                            <a href="{{ route('admin.peserta.show', $item) }}" class="btn-secondary w-full">
                                <i class="fa-regular fa-eye mr-2"></i>Lihat
                            </a>
                            <form action="{{ route('admin.peserta.toggle-status', $item) }}" method="POST" class="w-full">
                                @csrf
                                @if ($item->status === 'active')
                                    <button type="submit" class="btn-secondary w-full text-rose-600">Nonaktifkan</button>
                                @else
                                    <button type="submit" class="btn-primary w-full">Aktifkan</button>
                                @endif
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="panel p-10 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"><i class="fa-solid fa-users"></i></div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada peserta</h3>
                        <p class="mt-1 text-sm text-slate-500">Peserta akan muncul di sini setelah ada yang mendaftar.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6 hidden overflow-x-auto lg:block">
                <table class="w-full min-w-[960px] border-collapse text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                            <th class="py-3 pr-4">Peserta</th>
                            <th class="py-3 pr-4">Kontak</th>
                            <th class="py-3 pr-4 text-center">Room Dibuat</th>
                            <th class="py-3 pr-4 text-center">Room Diikuti</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Terdaftar</th>
                            <th class="py-3 pr-0 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($peserta as $item)
                            <tr class="align-middle hover:bg-slate-50/60">
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->photo_url)
                                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="h-10 w-10 rounded-full object-cover ring-2 ring-white">
                                        @else
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white ring-2 ring-white">{{ strtoupper(mb_substr($item->name, 0, 1)) }}</div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="truncate font-semibold text-slate-900">{{ $item->name }}</div>
                                            <div class="truncate text-xs text-slate-400">{{ $item->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 pr-4 text-slate-600">{{ $item->phone ?: '-' }}</td>
                                <td class="py-4 pr-4 text-center text-slate-600">{{ $item->hosted_rooms_count }}</td>
                                <td class="py-4 pr-4 text-center text-slate-600">{{ $item->room_participants_count }}</td>
                                <td class="py-4 pr-4">
                                    @if ($item->status === 'active')
                                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-4 pr-4 text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="py-4 pr-0">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.peserta.show', $item) }}" class="btn-secondary">
                                            <i class="fa-regular fa-eye mr-2"></i>Lihat
                                        </a>
                                        <form action="{{ route('admin.peserta.toggle-status', $item) }}" method="POST">
                                            @csrf
                                            @if ($item->status === 'active')
                                                <button type="submit" class="btn-secondary text-rose-600">Nonaktifkan</button>
                                            @else
                                                <button type="submit" class="btn-primary">Aktifkan</button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"><i class="fa-solid fa-users"></i></div>
                                    <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada peserta</h3>
                                    <p class="mt-1 text-sm text-slate-500">Peserta akan muncul di sini setelah ada yang mendaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($peserta->hasPages())
                <div class="mt-6">
                    {{ $peserta->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
