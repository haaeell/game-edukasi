@extends('layouts.base')

@section('body')
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.18),_transparent_24%),linear-gradient(145deg,_#071a31_0%,_#0f2747_42%,_#eaf1fb_100%)]">
        <div class="mx-auto grid min-h-screen max-w-7xl gap-0 lg:grid-cols-[1.05fr_0.95fr]">
            <section class="flex items-center px-6 py-10 text-white sm:px-8 lg:px-12 xl:px-16">
                <div class="w-full max-w-2xl">
                    <a href="{{ auth()->check() ? route(auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard') : route('home') }}" class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-white/90 backdrop-blur transition hover:bg-white/15">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>

                    <div class="mt-8 inline-flex rounded-full border border-sky-300/20 bg-sky-300/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-sky-100">
                        Join Room
                    </div>

                    <h1 class="mt-6 max-w-2xl text-4xl font-bold leading-tight text-white sm:text-5xl">
                        Masuk ke ruang refleksi dengan kode room yang kamu punya.
                    </h1>
                    <p class="mt-5 max-w-xl text-sm leading-7 text-slate-200 sm:text-base">
                        Masukkan kode room, tentukan ingin tampil dengan nama asli atau anonymous, lalu lanjut ke waiting room untuk memulai sesi bersama.
                    </p>

                    <div class="mt-10 grid gap-4 sm:grid-cols-3">
                        @foreach ([
                            ['icon' => 'fa-solid fa-key', 'title' => 'Masukkan kode', 'text' => 'Gunakan kode 6 karakter dari host room.'],
                            ['icon' => 'fa-regular fa-user', 'title' => 'Pilih identitas', 'text' => 'Bisa pakai nama asli atau alias anonymous.'],
                            ['icon' => 'fa-regular fa-comments', 'title' => 'Masuk ke sesi', 'text' => 'Lanjut ke room dan tunggu sesi dimulai.'],
                        ] as $step)
                            <div class="rounded-[1.75rem] border border-white/10 bg-white/10 p-5 backdrop-blur">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-lg text-sky-100">
                                    <i class="{{ $step['icon'] }}"></i>
                                </div>
                                <div class="mt-4 text-base font-semibold text-white">{{ $step['title'] }}</div>
                                <p class="mt-2 text-sm leading-6 text-slate-200">{{ $step['text'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    @if ($invitation)
                        <div class="mt-8 rounded-[1.75rem] border border-emerald-300/20 bg-emerald-400/10 p-5 backdrop-blur">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 text-emerald-100">
                                    <i class="fa-regular fa-envelope-open"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold uppercase tracking-[0.22em] text-emerald-100">Undangan Aktif</div>
                                    <div class="mt-2 text-xl font-bold text-white">{{ $invitation['room_title'] }}</div>
                                    <p class="mt-2 text-sm leading-6 text-emerald-50">
                                        Kamu mendapat invitation ke room dengan kode
                                        <span class="font-bold tracking-[0.22em]">{{ $invitation['room_code'] }}</span>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            <section class="flex items-center bg-white/80 px-4 py-6 backdrop-blur sm:px-6 lg:px-8 xl:px-10">
                <div class="panel mx-auto w-full max-w-2xl rounded-[2rem] p-6 sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Form Join Room</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                {{ auth()->check() ? 'Anda akan bergabung sebagai user terdaftar.' : 'Anda bisa bergabung sebagai guest jika room mengizinkan.' }}
                            </p>
                        </div>
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600">
                            <i class="fa-solid fa-door-open"></i>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                            <div class="flex items-center gap-2 font-semibold">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>Periksa kembali data join room.</span>
                            </div>
                            <ul class="mt-3 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @auth
                        <div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50/70 p-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-700 shadow-sm">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>
                    @endauth

                    <form action="{{ route('game.join.store') }}" method="POST" class="mt-8 space-y-6">
                        @csrf

                        @if ($invitation)
                            <input type="hidden" name="invitation_token" value="{{ $invitation['token'] }}">
                        @endif

                        <div>
                            <label class="label" for="code">Kode Room</label>
                            <input id="code" name="code" value="{{ old('code', $invitation['room_code'] ?? '') }}" class="field h-14 text-center text-lg font-bold uppercase tracking-[0.35em]" maxlength="6" placeholder="ABC123" required>
                            <p class="mt-2 text-xs text-slate-400">Masukkan 6 karakter kode room yang diberikan host.</p>
                        </div>

                        @guest
                            <div class="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <label class="label" for="guest_name">Nama Tampil</label>
                                    <input id="guest_name" name="guest_name" value="{{ old('guest_name') }}" class="field" placeholder="Nama yang ingin ditampilkan">
                                </div>

                                <div>
                                    <label class="label" for="email">Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email', $invitation['email'] ?? '') }}" class="field" placeholder="email@contoh.com">
                                </div>
                            </div>
                        @endguest

                        <div class="rounded-[1.6rem] border border-slate-200 bg-slate-50/80 p-5">
                            <label for="is_anonymous" class="flex cursor-pointer items-start gap-3">
                                <input id="is_anonymous" name="is_anonymous" type="checkbox" value="1" @checked(old('is_anonymous')) class="mt-1 h-4 w-4 rounded border-slate-300">
                                <span>
                                    <span class="block text-sm font-semibold text-slate-900">Masuk sebagai anonymous</span>
                                    <span class="mt-1 block text-sm leading-6 text-slate-500">Aktifkan jika kamu ingin nama asli disamarkan selama sesi room berlangsung.</span>
                                </span>
                            </label>

                            <div id="anonymous-name-wrapper" class="mt-4">
                                <label class="label" for="anonymous_name">Nama Anonymous</label>
                                <input id="anonymous_name" name="anonymous_name" value="{{ old('anonymous_name') }}" class="field" placeholder="Contoh: Peserta Tenang">
                            </div>
                        </div>

                        <button type="submit" class="btn-primary h-14 w-full text-base">Masuk ke Room</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const codeInput = $('#code');
            const anonymousToggle = $('#is_anonymous');
            const anonymousWrapper = $('#anonymous-name-wrapper');

            function syncAnonymousField() {
                anonymousWrapper.toggleClass('hidden', !anonymousToggle.is(':checked'));
            }

            codeInput.on('input', function() {
                const nextValue = $(this).val().replace(/[^a-zA-Z0-9]/g, '').toUpperCase().slice(0, 6);
                $(this).val(nextValue);
            });

            anonymousToggle.on('change', syncAnonymousField);

            syncAnonymousField();
        });
    </script>
@endpush
