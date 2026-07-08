@extends('layouts.base')

@push('styles')
    <style>
        #card-stage {
            position: relative;
        }

        #card-stage.is-shuffling .shuffle-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(1) {
            animation: shuffle-card-left 0.95s ease-in-out;
        }

        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(2) {
            animation: shuffle-card-center 0.95s ease-in-out;
        }

        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(3) {
            animation: shuffle-card-right 0.95s ease-in-out;
        }

        #card-stage.is-shuffling #active-card-panel {
            opacity: 0.55;
            transform: scale(0.98);
        }

        #active-card-panel {
            transition: opacity 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        @keyframes active-card-reveal {
            0% { opacity: 0.15; transform: perspective(1400px) rotateY(-24deg) rotateX(8deg) scale(0.92); }
            55% { opacity: 0.88; transform: perspective(1400px) rotateY(12deg) rotateX(-3deg) scale(1.02); }
            100% { opacity: 1; transform: perspective(1400px) rotateY(0deg) rotateX(0deg) scale(1); }
        }

        @keyframes shuffle-card-left {
            0% { transform: translateX(-40px) rotate(-16deg); opacity: 0; }
            20% { opacity: 1; }
            50% { transform: translateX(76px) rotate(8deg); }
            100% { transform: translateX(-12px) rotate(-10deg); opacity: 0; }
        }

        @keyframes shuffle-card-center {
            0% { transform: translateY(24px) rotate(0deg) scale(0.96); opacity: 0; }
            20% { opacity: 1; }
            50% { transform: translateY(-20px) rotate(-2deg) scale(1.02); }
            100% { transform: translateY(12px) rotate(2deg) scale(0.98); opacity: 0; }
        }

        @keyframes shuffle-card-right {
            0% { transform: translateX(40px) rotate(16deg); opacity: 0; }
            20% { opacity: 1; }
            50% { transform: translateX(-76px) rotate(-8deg); }
            100% { transform: translateX(12px) rotate(10deg); opacity: 0; }
        }

        .shuffle-overlay {
            position: absolute;
            inset: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            opacity: 0;
            pointer-events: none;
        }

        .shuffle-overlay-card {
            position: absolute;
            width: min(280px, 28vw);
            height: min(390px, 54vw);
            border-radius: 2rem;
            border: 1px solid rgba(147, 197, 253, 0.9);
            background:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.84), rgba(255, 255, 255, 0.08)),
                linear-gradient(180deg, #b5cdfd 0%, #7aa3f5 100%);
            box-shadow: 0 24px 60px rgba(96, 138, 240, 0.24);
        }

        .room-grid {
            display: grid;
            gap: 1.5rem;
        }

        .room-shell.is-focused .room-sidebar {
            display: none;
        }

        .room-shell.is-focused .room-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .floating-chat-trigger {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 60;
        }

        .floating-chat-panel {
            position: fixed;
            right: 1rem;
            bottom: 5.5rem;
            z-index: 60;
            width: min(380px, calc(100vw - 1.5rem));
            transform: translateY(18px);
            opacity: 0;
            pointer-events: none;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }

        .floating-chat-panel.is-open {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        @media (min-width: 1024px) {
            .room-grid {
                grid-template-columns: 320px minmax(0, 1fr);
            }
        }

        @media (min-width: 1536px) {
            .room-grid {
                grid-template-columns: 300px minmax(0, 1fr);
            }
        }

        @media (max-width: 1023px) {
            .room-grid {
                gap: 1rem;
            }

            #card-stage {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            #card-stage [data-deck-card="left"],
            #card-stage [data-deck-card="right"] {
                display: none !important;
            }

            #active-card-panel {
                border-radius: 1.75rem !important;
                padding: 1.25rem !important;
            }

            .js-room-action {
                width: 100%;
            }
        }

        @media (max-width: 767px) {
            .shuffle-overlay-card {
                width: 180px;
                height: 250px;
            }

            #active-card-title {
                font-size: 1.75rem !important;
                line-height: 2rem !important;
            }

            #active-card-question {
                font-size: 1rem !important;
                line-height: 1.9rem !important;
            }

            .floating-chat-trigger,
            .floating-chat-panel {
                right: 0.75rem;
            }

            .floating-chat-trigger {
                bottom: 0.75rem;
            }

            .floating-chat-panel {
                bottom: 5rem;
                width: calc(100vw - 1.5rem);
            }
        }
    </style>
@endpush

@section('body')
    <div class="room-shell min-h-screen bg-[#eef4fb]">
        <div class="mx-auto max-w-[1720px] p-3 lg:p-5">
            <div class="glass-panel rounded-[2.2rem] p-4 sm:p-5 lg:p-6">
                <div class="panel overflow-hidden p-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div>
                            <div class="text-sm font-semibold uppercase tracking-[0.25em] text-blue-700">Ruang Refleksi</div>
                            <h1 class="mt-3 text-3xl font-bold text-slate-900">{{ $room->title }}</h1>
                            <p class="mt-2 text-sm text-slate-500">Kode {{ $room->code }} • {{ $room->cardSet->title }} • {{ ucfirst($room->card_flow_type) }}</p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            @if ($participant->is_host && $room->status === 'waiting')
                                <button type="button" class="btn-primary js-room-action" data-url="{{ route('game.rooms.start', $room->code) }}">Start Game</button>
                            @endif
                            <button type="button" id="room-focus-toggle" class="btn-secondary">
                                <i class="fa-solid fa-expand mr-2"></i>Fokus Kartu
                            </button>

                            @auth
                                <a href="{{ route('user.game.index') }}" class="btn-secondary">Kembali ke Game</a>
                            @else
                                <a href="{{ route('game.join') }}" class="btn-secondary">Join Room Lain</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="room-grid mt-6">
                    <div class="room-sidebar space-y-6">
                        <div class="panel p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-xl font-bold text-blue-700">Ruang Konseling</h2>
                                    <p class="mt-1 text-xs text-slate-500">Status peserta dan kontrol sesi</p>
                                </div>
                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ ucfirst($room->status) }}</span>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Nama Room</div>
                                    <div class="mt-2 text-base font-bold text-slate-900">{{ $room->title }}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Kode Room</div>
                                    <div class="mt-2 text-2xl font-bold tracking-[0.2em] text-blue-600">{{ $room->code }}</div>
                                </div>
                            </div>

                            <div class="mt-5">
                                <div class="text-sm font-semibold text-slate-700">Peserta ({{ $room->participants->count() }})</div>
                                <div id="participants-box" class="mt-4 space-y-3">
                                    @foreach ($room->participants as $item)
                                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-3 py-3">
                                            <div>
                                                <div class="text-sm font-semibold text-slate-900">{{ $item->public_name }}</div>
                                                <div class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">{{ $item->participant_type }} • {{ $item->status }}</div>
                                            </div>
                                            @if ($item->is_host)
                                                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Host</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if ($participant->is_host)
                                <div class="mt-5 border-t border-slate-200 pt-5">
                                    <h3 class="text-sm font-semibold text-slate-700">Undang Teman via Email</h3>
                                    <form action="{{ route('game.rooms.invite', $room->code) }}" method="POST" class="mt-4 space-y-3">
                                        @csrf
                                        <input type="email" name="email" class="field" placeholder="email@contoh.com" required>
                                        <button type="submit" class="btn-primary w-full">Undang</button>
                                    </form>
                                </div>
                            @endif
                        </div>


                    </div>

                    <div class="space-y-6">
                        <div class="panel overflow-hidden p-4 sm:p-6 lg:p-7">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h2 id="room-status-title" class="text-2xl font-bold text-slate-900">
                                        {{ $room->status === 'playing' ? 'Kartu Aktif' : ($room->status === 'finished' ? 'Sesi Selesai' : 'Waiting Room') }}
                                    </h2>
                                    <p id="room-status-text" class="mt-1 text-sm text-slate-500">
                                        @if ($room->status === 'playing' && $room->currentCard)
                                            Kartu {{ $room->current_card_order }} sedang aktif.
                                        @elseif ($room->status === 'finished')
                                            Sesi sudah diakhiri. Room tetap bisa dibuka untuk melihat kartu terakhir dan riwayat chat.
                                        @else
                                            Menunggu host memulai permainan.
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span id="target-badge" class="{{ $room->currentTargetParticipant ? '' : 'hidden ' }}rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                                        Untuk {{ $room->currentTargetParticipant?->public_name }}
                                    </span>
                                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-600">{{ $participant->public_name }}</span>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-wrap items-center justify-center gap-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 sm:gap-4">
                                <span class="rounded-full bg-blue-50 px-4 py-2 text-blue-700">Kartu {{ $room->current_card_order ?? 1 }} dari {{ $room->cardSet->cards->count() }}</span>
                                <span class="rounded-full bg-violet-50 px-4 py-2 text-violet-700">{{ $room->cardSet->title }}</span>
                                <span id="cards-remaining-badge" class="rounded-full bg-emerald-50 px-4 py-2 text-emerald-700">Sisa {{ max($room->cardSet->cards->where('status', 'active')->count() - count($room->opened_card_ids ?? []), 0) }} kartu</span>
                            </div>

                            <div id="card-stage" class="mt-8 grid items-center gap-4 lg:grid-cols-[0.5fr_1.7fr_0.5fr] 2xl:grid-cols-[0.6fr_1.55fr_0.6fr]">
                                <div class="shuffle-overlay">
                                    <div class="shuffle-overlay-card"></div>
                                    <div class="shuffle-overlay-card"></div>
                                    <div class="shuffle-overlay-card"></div>
                                </div>

                                <div class="relative hidden xl:block" data-deck-card="left">
                                    <div class="absolute inset-6 rounded-[2.2rem] border border-blue-100 bg-[linear-gradient(180deg,_#d9e5ff_0%,_#abc4ff_100%)] opacity-70"></div>
                                    <div class="relative flex h-[23rem] items-center justify-center rounded-[2.6rem] border border-blue-200/80 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.85),_rgba(255,255,255,0.08)),linear-gradient(180deg,_#b6cdfd_0%,_#82a8f8_100%)] shadow-[0_28px_50px_rgba(96,138,240,0.28)] rotate-[-8deg]">
                                        <div class="text-center text-white/90">
                                            <div class="text-4xl"><i class="fa-regular fa-heart"></i></div>
                                            <div class="mt-4 text-base font-semibold uppercase tracking-[0.28em]">Reflection</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="active-card-panel" class="{{ $room->status === 'playing' && $room->currentCard ? 'rounded-[2.6rem] border border-violet-200/80 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.96),_rgba(249,250,255,0.92)),linear-gradient(180deg,_#ffffff_0%,_#f7f8ff_100%)] p-5 text-slate-900 shadow-[0_28px_80px_rgba(129,140,248,0.22)] sm:p-6 lg:p-8 xl:p-10' : 'rounded-[2.6rem] border border-dashed border-slate-300 bg-slate-50 p-5 text-slate-900 sm:p-6 lg:p-8 xl:p-10' }}">
                                    @if ($room->status === 'playing' && $room->currentCard)
                                        <div id="active-card-order" class="mx-auto w-max rounded-full bg-violet-50 px-5 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-violet-700"></div>
                                        <div class="mt-8 text-center">
                                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-[1.4rem] bg-gradient-to-br from-violet-50 to-indigo-50 text-2xl text-violet-600 shadow-sm sm:h-16 sm:w-16">
                                                <i class="fa-regular fa-heart"></i>
                                            </div>
                                            <h3 id="active-card-title" class="mt-6 text-3xl font-bold tracking-tight sm:text-4xl">{{ $room->currentCard->title }}</h3>
                                        </div>
                                        <div class="mx-auto mt-8 max-w-3xl rounded-[2rem] border border-slate-100 bg-white/85 px-5 py-6 shadow-inner sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                                            <p id="active-card-question" class="text-center text-lg leading-9 text-slate-600 sm:text-xl sm:leading-[2.35rem] lg:text-[1.9rem] lg:leading-[3.2rem]">{{ $room->currentCard->question }}</p>
                                        </div>
                                        <div class="mt-8 flex justify-center">
                                            <div class="rounded-full bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-600">
                                                <i class="fa-regular fa-heart mr-2"></i>Bagikan dengan tenang
                                            </div>
                                        </div>
                                    @else
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-[1.6rem] bg-slate-100 text-2xl text-slate-500">
                                            <i class="fa-regular fa-hourglass-half"></i>
                                        </div>
                                        <h3 id="active-card-title" class="mt-6 text-center text-3xl font-bold text-slate-900">{{ $room->status === 'finished' ? 'Sesi telah selesai' : 'Ruang tunggu aktif' }}</h3>
                                        <p id="active-card-question" class="mx-auto mt-5 max-w-2xl text-center text-base leading-8 text-slate-600">
                                            {{ $room->status === 'finished' ? 'Room ini sudah diakhiri, tetapi kamu masih bisa membuka kembali halaman ini untuk melihat kartu terakhir, peserta, dan riwayat percakapan.' : 'Peserta bisa bergabung menggunakan kode room ini. Host dapat menyalakan mode anonymous dan mulai game saat semua siap.' }}
                                        </p>
                                        <div id="active-card-order" class="hidden"></div>
                                    @endif
                                </div>

                                <div class="relative hidden xl:block" data-deck-card="right">
                                    <div class="absolute inset-6 rounded-[2.2rem] border border-blue-100 bg-[linear-gradient(180deg,_#d9e5ff_0%,_#abc4ff_100%)] opacity-70"></div>
                                    <div class="relative flex h-[23rem] items-center justify-center rounded-[2.6rem] border border-blue-200/80 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.85),_rgba(255,255,255,0.08)),linear-gradient(180deg,_#b6cdfd_0%,_#82a8f8_100%)] shadow-[0_28px_50px_rgba(96,138,240,0.28)] rotate-[8deg]">
                                        <div class="text-center text-white/90">
                                            <div class="text-4xl"><i class="fa-regular fa-heart"></i></div>
                                            <div class="mt-4 text-base font-semibold uppercase tracking-[0.28em]">Deep Talk</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($participant->is_host)
                                <div class="mt-8 grid gap-3 sm:flex sm:flex-wrap sm:items-center sm:justify-center">
                                    @if ($room->status === 'waiting')
                                        <button type="button" class="btn-primary js-room-action" data-url="{{ route('game.rooms.start', $room->code) }}">Mulai Sesi</button>
                                    @endif
                                    @if ($room->status === 'playing' && max($room->cardSet->cards->where('status', 'active')->count() - count($room->opened_card_ids ?? []), 0) > 0)
                                        <button type="button" id="shuffle-card-button" class="btn-primary js-room-action" data-url="{{ route('game.rooms.shuffle', $room->code) }}">
                                            <i class="fa-solid fa-shuffle mr-2"></i>Acak Kartu
                                        </button>
                                    @endif
                                    @if ($room->status === 'playing' && max($room->cardSet->cards->where('status', 'active')->count() - count($room->opened_card_ids ?? []), 0) === 0)
                                        <button type="button" id="reset-deck-button" class="btn-secondary js-room-action" data-url="{{ route('game.rooms.reset-deck', $room->code) }}">
                                            <i class="fa-solid fa-rotate-left mr-2"></i>Reset Kartu
                                        </button>
                                    @endif
                                    <button type="button" class="btn-danger js-room-action" data-url="{{ route('game.rooms.end', $room->code) }}">Akhiri Sesi</button>
                                </div>
                            @endif

                            <p class="mt-6 text-center text-sm text-slate-500">Sesi ini bersifat aman dan rahasia. Hargai setiap cerita yang dibagikan di ruang ini.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <button type="button" id="floating-chat-trigger" class="floating-chat-trigger inline-flex items-center gap-3 rounded-full bg-blue-600 px-5 py-4 text-sm font-semibold text-white shadow-[0_18px_45px_rgba(37,99,235,0.35)] transition hover:bg-blue-700">
            <i class="fa-regular fa-comments text-base"></i>
            <span>Chat</span>
            <span id="chat-notification-badge" class="hidden min-w-[1.5rem] rounded-full bg-white px-2 py-1 text-center text-xs font-bold text-blue-700">0</span>
        </button>

        <div id="floating-chat-panel" class="floating-chat-panel">
            <div class="panel overflow-hidden border border-slate-200 p-0 shadow-[0_24px_70px_rgba(15,23,42,0.16)]">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Chat Group</h2>
                        <p class="mt-1 text-xs text-slate-500">Jawaban peserta akan tampil realtime.</p>
                    </div>
                    <button type="button" id="floating-chat-close" class="flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 transition hover:bg-slate-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div id="chat-box" class="h-[22rem] space-y-3 overflow-y-auto bg-slate-50 p-4"></div>
                <div class="border-t border-slate-200 bg-white px-4 py-4">
                    <form id="chat-form" class="flex flex-col gap-3 sm:flex-row">
                        <input id="chat-message" name="message" class="field" placeholder="Tulis jawaban atau chat..." autocomplete="off" @disabled($room->status === 'finished')>
                        <button type="submit" class="btn-primary shrink-0 sm:min-w-[110px]" @disabled($room->status === 'finished')>Kirim</button>
                    </form>
                    @if ($room->status === 'finished')
                        <p class="mt-3 text-xs text-slate-400">Room sudah selesai, jadi chat baru tidak bisa dikirim.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const roomCode = @json($room->code);
        const statusUrl = @json(route('game.rooms.status', $room->code));
        const participantsUrl = @json(route('game.rooms.participants', $room->code));
        const messagesUrl = @json(route('game.rooms.messages', $room->code));
        const sendMessageUrl = @json(route('game.rooms.messages.store', $room->code));
        const resetDeckUrl = @json(route('game.rooms.reset-deck', $room->code));
        const homeUrl = @json(route('home'));
        let isAnimatingShuffle = false;
        let latestTargetParticipantId = @json($room->current_target_participant_id);
        let unreadChatCount = 0;
        let lastMessageId = null;

        function escapeHtml(text) {
            return $('<div>').text(text ?? '').html();
        }

        function renderStatus(data) {
            const playing = data.status === 'playing' && data.current_card;
            const finished = data.status === 'finished';
            $('#room-status-title').text(playing ? 'Kartu Aktif' : (finished ? 'Sesi Selesai' : 'Waiting Room'));
            $('#room-status-text').text(
                playing
                    ? `Kartu ${data.current_card_order} sedang aktif.`
                    : (finished
                        ? 'Sesi sudah diakhiri. Room tetap bisa dibuka untuk melihat kartu terakhir dan riwayat chat.'
                        : 'Menunggu host memulai permainan.')
            );

            const panel = $('#active-card-panel');
            const orderEl = $('#active-card-order');
            const titleEl = $('#active-card-title');
            const questionEl = $('#active-card-question');
            const targetBadge = $('#target-badge');
            const cardsRemainingBadge = $('#cards-remaining-badge');
            const target = data.target_participant || null;

            if (playing) {
                panel.removeClass('border-dashed border-slate-300 bg-slate-50').addClass('border-violet-200/80 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.96),_rgba(249,250,255,0.92)),linear-gradient(180deg,_#ffffff_0%,_#f7f8ff_100%)] shadow-[0_28px_80px_rgba(129,140,248,0.22)]');
                titleEl.text(data.current_card.title).removeClass('text-slate-900').addClass('text-slate-900');
                questionEl.text(data.current_card.question).removeClass('text-slate-600').addClass('text-slate-600');
            } else if (finished) {
                panel.removeClass('border-violet-200/80 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.96),_rgba(249,250,255,0.92)),linear-gradient(180deg,_#ffffff_0%,_#f7f8ff_100%)] border-dashed border-slate-300 bg-slate-50').addClass('border-emerald-200 bg-[linear-gradient(180deg,_rgba(255,255,255,0.96),_rgba(240,253,250,0.96))] shadow-[0_24px_70px_rgba(16,185,129,0.14)]');
                orderEl.addClass('hidden').text('');
                titleEl.text('Sesi telah selesai').removeClass('text-slate-900').addClass('text-slate-900');
                questionEl.text('Room ini sudah diakhiri, tetapi kamu masih bisa membuka kembali halaman ini untuk melihat kartu terakhir, peserta, dan riwayat percakapan.').removeClass('text-slate-200').addClass('text-slate-600');
            } else {
                panel.removeClass('border-violet-200/80 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.96),_rgba(249,250,255,0.92)),linear-gradient(180deg,_#ffffff_0%,_#f7f8ff_100%)] shadow-[0_28px_80px_rgba(129,140,248,0.22)] border-emerald-200 bg-[linear-gradient(180deg,_rgba(255,255,255,0.96),_rgba(240,253,250,0.96))] shadow-[0_24px_70px_rgba(16,185,129,0.14)]').addClass('border-dashed border-slate-300 bg-slate-50');
                orderEl.addClass('hidden').text('');
                titleEl.text('Ruang tunggu aktif').removeClass('text-white').addClass('text-slate-900');
                questionEl.text('Peserta bisa bergabung menggunakan kode room ini. Host dapat menyalakan mode anonymous dan mulai game saat semua siap.').removeClass('text-slate-200').addClass('text-slate-600');
            }

            if (target) {
                targetBadge.removeClass('hidden').text(`Untuk ${target.name}`);
            } else {
                targetBadge.addClass('hidden').text('');
            }

            cardsRemainingBadge.text(`Sisa ${data.cards_remaining} kartu`);

            const actionBar = $('.js-room-action').parent();
            const shuffleButton = $('#shuffle-card-button');
            const resetDeckButton = $('#reset-deck-button');

            if (actionBar.length) {
                if (data.status === 'playing' && !data.cards_exhausted) {
                    if (!shuffleButton.length) {
                        actionBar.prepend(`
                            <button type="button" id="shuffle-card-button" class="btn-primary js-room-action" data-url="${@json(route('game.rooms.shuffle', $room->code))}">
                                <i class="fa-solid fa-shuffle mr-2"></i>Acak Kartu
                            </button>
                        `);
                    }
                    resetDeckButton.remove();
                } else if (data.status === 'playing' && data.cards_exhausted) {
                    shuffleButton.remove();
                    if (!resetDeckButton.length) {
                        actionBar.prepend(`
                            <button type="button" id="reset-deck-button" class="btn-secondary js-room-action" data-url="${resetDeckUrl}">
                                <i class="fa-solid fa-rotate-left mr-2"></i>Reset Kartu
                            </button>
                        `);
                    }
                    titleEl.text('Semua kartu sudah terbuka');
                    questionEl.text('Semua kartu pada deck ini sudah dibuka. Kamu bisa reset kartu untuk memulai putaran baru, atau akhiri sesi jika diskusi sudah selesai.');
                    orderEl.addClass('hidden').text('');
                } else {
                    shuffleButton.remove();
                    resetDeckButton.remove();
                }
            }

            $('#chat-message, #chat-form button[type="submit"]').prop('disabled', finished);

            if (target && target.id !== latestTargetParticipantId) {
                latestTargetParticipantId = target.id;

                if (target.is_me) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Giliran Kamu Menjawab',
                        text: `Kartu ini ditujukan untuk ${target.name}. Silakan jawab kartunya sekarang.`,
                        confirmButtonColor: '#2563eb'
                    });
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Kartu Baru Dibuka',
                        text: `Kartu ini ditujukan untuk ${target.name}.`,
                        timer: 2200,
                        showConfirmButton: false
                    });
                }
            }
        }

        function triggerShuffleAnimation() {
            const stage = $('#card-stage');
            const panel = $('#active-card-panel');

            isAnimatingShuffle = true;
            stage.addClass('is-shuffling');

            window.setTimeout(function() {
                stage.removeClass('is-shuffling');
                panel.addClass('is-revealing');

                window.setTimeout(function() {
                    panel.removeClass('is-revealing');
                    isAnimatingShuffle = false;
                }, 620);
            }, 720);
        }

        function renderParticipants(data) {
            const html = data.participants.map((participant) => `
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-4">
                    <div>
                        <div class="font-semibold text-slate-900">${escapeHtml(participant.name)}</div>
                        <div class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">${escapeHtml(participant.type)} • ${escapeHtml(participant.status)}</div>
                    </div>
                    ${participant.is_host ? '<span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Host</span>' : ''}
                </div>
            `).join('');

            $('#participants-box').html(html || '<p class="text-sm text-slate-500">Belum ada peserta.</p>');
        }

        function renderMessages(data) {
            const html = data.messages.map((message) => `
                <div class="flex ${message.is_mine ? 'justify-end' : 'justify-start'}">
                    <div class="${message.type === 'system' ? 'w-full rounded-2xl bg-amber-50 px-4 py-3 text-center text-sm text-amber-800' : `max-w-[85%] rounded-3xl px-4 py-3 ${message.is_mine ? 'bg-blue-600 text-white' : 'bg-white text-slate-800 border border-slate-200'}`}">
                        ${message.type === 'system' ? `
                            <div class="font-semibold">Sistem</div>
                            <div class="mt-1">${escapeHtml(message.message)}</div>
                        ` : `
                            <div class="text-xs font-semibold uppercase tracking-[0.15em] ${message.is_mine ? 'text-blue-100' : 'text-slate-500'}">${escapeHtml(message.name)} • ${escapeHtml(message.created_at)}</div>
                            <div class="mt-2 text-sm leading-6">${escapeHtml(message.message)}</div>
                        `}
                    </div>
                </div>
            `).join('');

            $('#chat-box').html(html || '<p class="text-sm text-slate-500">Belum ada pesan.</p>');
            const box = document.getElementById('chat-box');
            box.scrollTop = box.scrollHeight;

            const newestMessageId = data.messages.length ? data.messages[data.messages.length - 1].id : null;

            if (newestMessageId && lastMessageId !== null && newestMessageId !== lastMessageId && !$('#floating-chat-panel').hasClass('is-open')) {
                unreadChatCount += 1;
                syncChatBadge();
            }

            lastMessageId = newestMessageId;
        }

        function syncChatBadge() {
            const badge = $('#chat-notification-badge');

            if (unreadChatCount > 0) {
                badge.removeClass('hidden').text(unreadChatCount > 99 ? '99+' : unreadChatCount);
            } else {
                badge.addClass('hidden').text('0');
            }
        }

        function openFloatingChat() {
            $('#floating-chat-panel').addClass('is-open');
            unreadChatCount = 0;
            syncChatBadge();
        }

        function closeFloatingChat() {
            $('#floating-chat-panel').removeClass('is-open');
        }

        function leaveRoomToHome(message = 'Sesi telah selesai. Kamu dikembalikan ke halaman utama.') {
            Swal.fire({
                icon: 'info',
                title: 'Sesi Berakhir',
                text: message,
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.href = homeUrl;
            });
        }

        function pollRoom() {
            $.get(statusUrl)
                .done(renderStatus)
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        leaveRoomToHome();
                    }
                });

            $.get(participantsUrl)
                .done(renderParticipants)
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        leaveRoomToHome();
                    }
                });

            $.get(messagesUrl)
                .done(renderMessages)
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        leaveRoomToHome();
                    }
                });
        }

        $('#chat-form').on('submit', function(event) {
            event.preventDefault();
            const message = $('#chat-message').val().trim();

            if (!message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pesan kosong',
                    text: 'Silakan isi chat terlebih dahulu.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            $.ajax({
                url: sendMessageUrl,
                type: 'POST',
                data: {
                    _token: window.csrfToken,
                    message,
                }
            }).done(function() {
                $('#chat-message').val('');
                pollRoom();
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal mengirim',
                    text: xhr.responseJSON?.message || 'Pesan tidak dapat dikirim.',
                    confirmButtonColor: '#2563eb'
                });
            });
        });

        $(document).on('click', '.js-room-action', function(event) {
            event.preventDefault();

            const button = $(this);
            const url = button.data('url');
            const isShuffle = button.attr('id') === 'shuffle-card-button';
            const originalHtml = button.html();

            if (!url || button.prop('disabled')) {
                return;
            }

            if (isShuffle && isAnimatingShuffle) {
                return;
            }

            if (isShuffle) {
                triggerShuffleAnimation();
            }

            button.prop('disabled', true).addClass('opacity-70').html(isShuffle ? '<i class="fa-solid fa-shuffle mr-2"></i>Mengacak...' : 'Memproses...');

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: window.csrfToken,
                }
            }).done(function(response) {
                if (response.redirect_url) {
                    leaveRoomToHome('Sesi telah diakhiri. Semua peserta keluar dari room.');
                    return;
                }

                if (response.status) {
                    renderStatus(response.status);
                    pollRoom();
                } else {
                    window.location.reload();
                }
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Aksi gagal',
                    text: xhr.responseJSON?.message || 'Tindakan tidak dapat diproses sekarang.',
                    confirmButtonColor: '#2563eb'
                });
            }).always(function() {
                button.prop('disabled', false).removeClass('opacity-70').html(originalHtml);
            });
        });

        $('#floating-chat-trigger').on('click', openFloatingChat);
        $('#floating-chat-close').on('click', closeFloatingChat);

        $('#room-focus-toggle').on('click', function() {
            const shell = $('.room-shell');
            shell.toggleClass('is-focused');
            $(this).html(shell.hasClass('is-focused')
                ? '<i class="fa-solid fa-compress mr-2"></i>Tampilkan Info'
                : '<i class="fa-solid fa-expand mr-2"></i>Fokus Kartu');
        });

        pollRoom();
        setInterval(pollRoom, 4000);
    </script>
@endpush
