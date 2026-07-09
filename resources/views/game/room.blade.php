@extends('layouts.base')

@push('styles')
    <style>
        #card-stage {
            position: relative;
            perspective: 1600px;
        }

        #card-stage::before {
            content: "";
            position: absolute;
            inset: -12% 3%;
            z-index: 0;
            pointer-events: none;
            background: radial-gradient(58% 58% at 50% 42%, rgba(129, 140, 248, 0.18), transparent 72%);
            filter: blur(22px);
        }

        #card-stage.is-shuffling .shuffle-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(1) { animation: riffle-1 0.72s cubic-bezier(0.45, 0, 0.25, 1); }
        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(2) { animation: riffle-2 0.72s cubic-bezier(0.45, 0, 0.25, 1); }
        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(3) { animation: riffle-3 0.72s cubic-bezier(0.45, 0, 0.25, 1); }
        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(4) { animation: riffle-4 0.72s cubic-bezier(0.45, 0, 0.25, 1); }
        #card-stage.is-shuffling .shuffle-overlay-card:nth-child(5) { animation: riffle-5 0.72s cubic-bezier(0.45, 0, 0.25, 1); }

        #card-stage.is-shuffling #active-card-panel {
            opacity: 0.3;
            transform: scale(0.95);
            filter: blur(1.5px);
        }

        /* ===== Active card (center) ===== */
        #active-card-panel {
            --card-accent: 129, 140, 248;
            --card-accent-2: 99, 102, 241;
            position: relative;
            z-index: 1;
            overflow: hidden;
            border-radius: 2.6rem;
            border: 1px solid rgba(var(--card-accent), 0.3);
            background:
                radial-gradient(130% 100% at 50% -12%, rgba(var(--card-accent), 0.14), transparent 58%),
                linear-gradient(180deg, #ffffff 0%, #f7f8ff 100%);
            box-shadow:
                0 34px 80px -34px rgba(var(--card-accent), 0.55),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            transform-style: preserve-3d;
            transition: opacity 0.35s ease, transform 0.35s ease, box-shadow 0.4s ease,
                        filter 0.35s ease, border-color 0.45s ease, background 0.45s ease;
        }

        #active-card-panel.state-finished {
            --card-accent: 16, 185, 129;
            --card-accent-2: 5, 150, 105;
        }

        #active-card-panel.state-waiting {
            --card-accent: 148, 163, 184;
            --card-accent-2: 100, 116, 139;
        }

        #active-card-panel:hover {
            box-shadow:
                0 42px 92px -30px rgba(var(--card-accent), 0.65),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }

        #active-card-panel.is-revealing {
            animation: active-card-reveal 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .card-aura {
            position: absolute;
            z-index: 0;
            top: -32%;
            left: 50%;
            width: 115%;
            height: 120%;
            transform: translateX(-50%);
            background: radial-gradient(closest-side, rgba(var(--card-accent), 0.4), transparent 72%);
            filter: blur(46px);
            opacity: 0.85;
            pointer-events: none;
            animation: card-aura-float 8s ease-in-out infinite;
        }

        @keyframes card-aura-float {
            0%, 100% { transform: translateX(-50%) translateY(0) scale(1); }
            50%      { transform: translateX(-50%) translateY(22px) scale(1.08); }
        }

        .card-accent-bar {
            position: absolute;
            top: 0;
            left: 16%;
            right: 16%;
            height: 4px;
            z-index: 2;
            border-radius: 999px;
            background: linear-gradient(90deg, transparent, rgba(var(--card-accent), 0.95), rgba(var(--card-accent-2), 0.95), transparent);
        }

        .card-watermark {
            position: absolute;
            right: -1.6rem;
            bottom: -2.6rem;
            z-index: 0;
            font-size: 11rem;
            line-height: 1;
            color: rgba(var(--card-accent), 0.07);
            pointer-events: none;
            transform: rotate(-8deg);
        }

        .card-shine {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            background: linear-gradient(115deg, transparent 32%, rgba(255, 255, 255, 0.5) 48%, transparent 60%);
            transform: translateX(-130%);
            transition: transform 1s ease;
        }

        #active-card-panel:hover .card-shine {
            transform: translateX(130%);
        }

        #card-content {
            position: relative;
            z-index: 3;
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
        }

        #active-card-icon {
            position: relative;
            color: rgb(var(--card-accent-2));
            background:
                radial-gradient(circle at 30% 25%, rgba(255, 255, 255, 0.9), transparent 60%),
                linear-gradient(145deg, rgba(var(--card-accent), 0.16), rgba(var(--card-accent-2), 0.2));
            box-shadow: 0 12px 26px -8px rgba(var(--card-accent), 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        #active-card-panel.state-playing #active-card-icon {
            animation: card-icon-pulse 2.6s ease-in-out infinite;
        }

        @keyframes card-icon-pulse {
            0%, 100% { box-shadow: 0 12px 26px -8px rgba(var(--card-accent), 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.85), 0 0 0 0 rgba(var(--card-accent), 0.4); }
            50%      { box-shadow: 0 12px 26px -8px rgba(var(--card-accent), 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.85), 0 0 0 15px rgba(var(--card-accent), 0); }
        }

        .card-order-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            color: rgb(var(--card-accent-2));
            background: rgba(var(--card-accent), 0.13);
        }

        .card-order-pill .dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 999px;
            background: rgb(var(--card-accent-2));
            animation: card-dot-pulse 1.8s ease-in-out infinite;
        }

        @keyframes card-dot-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(var(--card-accent), 0.55); }
            50%      { box-shadow: 0 0 0 6px rgba(var(--card-accent), 0); }
        }

        .card-question-box {
            position: relative;
            border: 1px solid rgba(var(--card-accent), 0.18);
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(6px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 18px 40px -30px rgba(var(--card-accent), 0.6);
        }

        .card-footer-chip {
            color: rgb(var(--card-accent-2));
            background: rgba(var(--card-accent), 0.13);
        }

        #active-card-panel:not(.state-playing) .card-order-pill,
        #active-card-panel:not(.state-playing) #active-card-footer {
            display: none;
        }

        @media (prefers-reduced-motion: reduce) {
            .card-aura,
            #active-card-panel.state-playing #active-card-icon,
            .card-order-pill .dot {
                animation: none;
            }
        }

        @keyframes active-card-reveal {
            0%   { opacity: 0; transform: perspective(1400px) rotateY(-34deg) rotateX(10deg) translateY(16px) scale(0.9); }
            55%  { opacity: 1; transform: perspective(1400px) rotateY(9deg) rotateX(-3deg) scale(1.02); }
            100% { opacity: 1; transform: perspective(1400px) rotateY(0deg) rotateX(0deg) scale(1); }
        }

        /* ===== Riffle shuffle overlay ===== */
        .shuffle-overlay {
            position: absolute;
            inset: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transform-style: preserve-3d;
            transition: opacity 0.2s ease;
        }

        .shuffle-overlay-card {
            position: absolute;
            width: min(230px, 25vw);
            height: min(322px, 46vw);
            border-radius: 1.7rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background:
                repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.13) 0 7px, transparent 7px 15px),
                repeating-linear-gradient(-45deg, rgba(255, 255, 255, 0.10) 0 7px, transparent 7px 15px),
                radial-gradient(120% 90% at 50% 0%, rgba(255, 255, 255, 0.4), transparent 55%),
                linear-gradient(165deg, #7ea6f7 0%, #5273da 55%, #3a55bd 100%);
            box-shadow:
                0 30px 55px -16px rgba(52, 82, 190, 0.62),
                inset 0 0 0 6px rgba(255, 255, 255, 0.12),
                inset 0 0 0 7px rgba(255, 255, 255, 0.06);
            backface-visibility: hidden;
        }

        .shuffle-overlay-card::after {
            content: "\f004";
            font-family: "Font Awesome 6 Free";
            font-weight: 400;
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            color: rgba(255, 255, 255, 0.75);
            text-shadow: 0 2px 6px rgba(30, 58, 138, 0.35);
        }

        @keyframes riffle-1 {
            0%   { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
            18%  { opacity: 1; }
            50%  { transform: translate3d(-155px, -26px, 70px) rotate(-24deg); }
            100% { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
        }

        @keyframes riffle-2 {
            0%   { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
            18%  { opacity: 1; }
            50%  { transform: translate3d(155px, -26px, 70px) rotate(24deg); }
            100% { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
        }

        @keyframes riffle-3 {
            0%   { transform: translate3d(0, 0, 0) rotate(0deg) scale(1); opacity: 0; }
            18%  { opacity: 1; }
            50%  { transform: translate3d(0, -66px, 100px) rotate(0deg) scale(1.06); }
            100% { transform: translate3d(0, 0, 0) rotate(0deg) scale(1); opacity: 0; }
        }

        @keyframes riffle-4 {
            0%   { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
            18%  { opacity: 1; }
            50%  { transform: translate3d(-92px, 26px, 45px) rotate(-13deg); }
            100% { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
        }

        @keyframes riffle-5 {
            0%   { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
            18%  { opacity: 1; }
            50%  { transform: translate3d(92px, 26px, 45px) rotate(13deg); }
            100% { transform: translate3d(0, 0, 0) rotate(0deg); opacity: 0; }
        }

        /* ===== Realistic deck cards (sides) ===== */
        .deck-stack {
            position: relative;
            height: 24rem;
            width: 100%;
        }

        .deck-layer {
            position: absolute;
            inset: 0;
            border-radius: 1.9rem;
        }

        .deck-layer.back-2 {
            transform: translate(15px, 17px) rotate(6deg) scale(0.965);
            opacity: 0.5;
        }

        .deck-layer.back-1 {
            transform: translate(8px, 9px) rotate(3deg) scale(0.985);
            opacity: 0.78;
        }

        .play-card {
            position: absolute;
            inset: 0;
            border-radius: 1.9rem;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.6);
            background:
                radial-gradient(120% 80% at 30% 0%, rgba(255, 255, 255, 0.92), transparent 55%),
                linear-gradient(165deg, #d3e0ff 0%, #a2bcf8 48%, #6f92ee 100%);
            box-shadow:
                0 34px 60px -24px rgba(60, 96, 210, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .play-card::after {
            content: "";
            position: absolute;
            top: -40%;
            left: -35%;
            width: 55%;
            height: 190%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: rotate(20deg);
            pointer-events: none;
        }

        .card-frame {
            position: absolute;
            inset: 0.85rem;
            border-radius: 1.35rem;
            border: 1.5px solid rgba(255, 255, 255, 0.55);
            box-shadow: inset 0 0 0 1px rgba(96, 138, 240, 0.22);
            pointer-events: none;
        }

        .card-index {
            position: absolute;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(40, 70, 160, 0.45);
        }

        .card-index.tl { top: 1.3rem; left: 1.3rem; }
        .card-index.br { bottom: 1.3rem; right: 1.3rem; transform: rotate(180deg); }

        @keyframes deck-float-left {
            0%, 100% { transform: rotate(-9deg) translateY(0); }
            50%      { transform: rotate(-9deg) translateY(-12px); }
        }

        @keyframes deck-float-right {
            0%, 100% { transform: rotate(9deg) translateY(0); }
            50%      { transform: rotate(9deg) translateY(-12px); }
        }

        [data-deck-card="left"] .deck-stack { animation: deck-float-left 6.2s ease-in-out infinite; }
        [data-deck-card="right"] .deck-stack { animation: deck-float-right 7s ease-in-out infinite; }

        @media (prefers-reduced-motion: reduce) {
            [data-deck-card="left"] .deck-stack,
            [data-deck-card="right"] .deck-stack {
                animation: none;
            }
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
            max-height: calc(100vh - 7rem);
            max-height: calc(100dvh - 7rem);
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

        .floating-chat-panel .panel {
            display: flex;
            flex-direction: column;
            max-height: inherit;
        }

        #chat-box {
            flex: 1 1 auto;
            min-height: 0;
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
                max-height: calc(100vh - 6.5rem);
                max-height: calc(100dvh - 6.5rem);
            }
        }

        @media (max-width: 639px) {
            .room-shell .panel.p-6 {
                padding: 1.25rem !important;
            }

            #room-status-title {
                font-size: 1.35rem !important;
            }

            .floating-chat-trigger span:not(#chat-notification-badge) {
                display: none;
            }

            .floating-chat-trigger {
                padding: 0.9rem;
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
                            <h1 class="mt-3 text-2xl font-bold text-slate-900 sm:text-3xl">{{ $room->title }}</h1>
                            <p class="mt-2 text-sm text-slate-500">Kode {{ $room->code }} • {{ $room->cardSet->title }}</p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:gap-3">
                            @if ($participant->is_host && $room->status === 'waiting')
                                <button type="button" class="btn-primary js-room-action js-start-session-button w-full sm:w-auto" data-url="{{ route('game.rooms.start', $room->code) }}">Start Game</button>
                            @endif
                            <button type="button" id="room-focus-toggle" class="btn-secondary w-full sm:w-auto">
                                <i class="fa-solid fa-expand mr-2"></i>Fokus Kartu
                            </button>

                            @auth
                                <a href="{{ route('user.game.index') }}" class="btn-secondary w-full text-center sm:w-auto">Kembali ke Game</a>
                            @else
                                <a href="{{ route('game.join') }}" class="btn-secondary w-full text-center sm:w-auto">Join Room Lain</a>
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
                                <div id="participants-box" class="mt-4 space-y-2.5">
                                    @php
                                        $avatarPalette = [
                                            'from-blue-500 to-indigo-600',
                                            'from-violet-500 to-purple-600',
                                            'from-emerald-500 to-teal-600',
                                            'from-amber-500 to-orange-600',
                                            'from-pink-500 to-rose-600',
                                        ];
                                    @endphp
                                    @foreach ($room->participants as $item)
                                        @php
                                            $initial = strtoupper(mb_substr($item->public_name, 0, 1));
                                            $avatarColor = $avatarPalette[ord($initial ?: 'A') % count($avatarPalette)];
                                        @endphp
                                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 transition hover:border-blue-200 hover:shadow-sm">
                                            <div class="relative shrink-0">
                                                @if ($item->photo_url)
                                                    <img src="{{ $item->photo_url }}" alt="{{ $item->public_name }}" class="h-11 w-11 rounded-full object-cover shadow-sm ring-2 ring-white">
                                                @else
                                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br {{ $avatarColor }} text-sm font-bold text-white shadow-sm ring-2 ring-white">{{ $initial }}</div>
                                                @endif
                                                @if ($item->status === 'active')
                                                    <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="truncate text-sm font-semibold text-slate-900">{{ $item->public_name }}</div>
                                                <div class="mt-0.5 text-xs text-slate-500">{{ $item->participant_type === 'registered' ? 'Terdaftar' : 'Tamu' }}</div>
                                            </div>
                                            @if ($item->is_host)
                                                <span class="shrink-0 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Host</span>
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
                                    <div class="shuffle-overlay-card"></div>
                                    <div class="shuffle-overlay-card"></div>
                                </div>

                                <div class="relative hidden xl:block" data-deck-card="left">
                                    <div class="deck-stack">
                                        <div class="deck-layer play-card back-2"></div>
                                        <div class="deck-layer play-card back-1"></div>
                                        <div class="play-card">
                                            <div class="card-frame"></div>
                                            <span class="card-index tl"><i class="fa-solid fa-heart text-sm"></i><span class="mt-1 text-[0.6rem] tracking-[0.15em]">RF</span></span>
                                            <span class="card-index br"><i class="fa-solid fa-heart text-sm"></i><span class="mt-1 text-[0.6rem] tracking-[0.15em]">RF</span></span>
                                            <div class="relative flex h-full flex-col items-center justify-center text-white">
                                                <div class="flex h-16 w-16 items-center justify-center rounded-[1.3rem] border border-white/40 bg-white/20 text-3xl shadow-inner backdrop-blur-sm"><i class="fa-regular fa-heart"></i></div>
                                                <div class="mt-4 text-sm font-semibold uppercase tracking-[0.32em]">Reflection</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $isPlaying = $room->status === 'playing' && $room->currentCard;
                                    $cardState = $isPlaying ? 'state-playing' : ($room->status === 'finished' ? 'state-finished' : 'state-waiting');
                                    $cardIcon = $isPlaying ? 'fa-regular fa-heart' : ($room->status === 'finished' ? 'fa-solid fa-circle-check' : 'fa-regular fa-hourglass-half');
                                    $cardTitle = $isPlaying ? $room->currentCard->title : ($room->status === 'finished' ? 'Sesi telah selesai' : 'Ruang tunggu aktif');
                                    $cardQuestion = $isPlaying
                                        ? $room->currentCard->question
                                        : ($room->status === 'finished'
                                            ? 'Room ini sudah diakhiri, tetapi kamu masih bisa membuka kembali halaman ini untuk melihat kartu terakhir, peserta, dan riwayat percakapan.'
                                            : 'Peserta bisa bergabung menggunakan kode room ini. Host dapat menyalakan mode anonymous dan mulai game saat semua siap.');
                                @endphp

                                <div id="active-card-panel" class="{{ $cardState }} p-6 text-slate-900 sm:p-8 lg:p-10 xl:p-12">
                                    <div class="card-aura"></div>
                                    <div class="card-accent-bar"></div>
                                    <div class="card-watermark"><i class="fa-solid fa-heart"></i></div>
                                    <div class="card-shine"></div>

                                    <div id="card-content">
                                        <div class="text-center">
                                            <span class="card-order-pill mx-auto w-max rounded-full px-4 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.24em]">
                                                <span class="dot"></span> Sedang berlangsung
                                            </span>
                                        </div>
                                        <div class="mt-6 text-center">
                                            <div id="active-card-icon" class="mx-auto flex h-16 w-16 items-center justify-center rounded-[1.5rem] text-2xl sm:h-20 sm:w-20 sm:text-3xl">
                                                <i class="{{ $cardIcon }}"></i>
                                            </div>
                                            <h3 id="active-card-title" class="mt-6 text-3xl font-bold tracking-tight sm:text-4xl">{{ $cardTitle }}</h3>
                                        </div>
                                        <div class="card-question-box mx-auto mt-8 max-w-3xl rounded-[2rem] px-5 py-6 sm:px-8 sm:py-8 lg:py-10">
                                            <p id="active-card-question" class="text-center text-lg leading-9 text-slate-600 sm:text-xl sm:leading-[2.35rem] lg:text-[1.85rem] lg:leading-[3.1rem]">{{ $cardQuestion }}</p>
                                        </div>
                                        <div id="active-card-footer" class="mt-8 flex justify-center">
                                            <div class="card-footer-chip inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold">
                                                <i class="fa-regular fa-heart mr-2"></i>Bagikan dengan tenang
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative hidden xl:block" data-deck-card="right">
                                    <div class="deck-stack">
                                        <div class="deck-layer play-card back-2"></div>
                                        <div class="deck-layer play-card back-1"></div>
                                        <div class="play-card">
                                            <div class="card-frame"></div>
                                            <span class="card-index tl"><i class="fa-solid fa-comments text-sm"></i><span class="mt-1 text-[0.6rem] tracking-[0.15em]">DT</span></span>
                                            <span class="card-index br"><i class="fa-solid fa-comments text-sm"></i><span class="mt-1 text-[0.6rem] tracking-[0.15em]">DT</span></span>
                                            <div class="relative flex h-full flex-col items-center justify-center text-white">
                                                <div class="flex h-16 w-16 items-center justify-center rounded-[1.3rem] border border-white/40 bg-white/20 text-3xl shadow-inner backdrop-blur-sm"><i class="fa-solid fa-comments"></i></div>
                                                <div class="mt-4 text-sm font-semibold uppercase tracking-[0.32em]">Deep Talk</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($participant->is_host)
                                <div class="mt-8 grid gap-3 sm:flex sm:flex-wrap sm:items-center sm:justify-center">
                                    @if ($room->status === 'waiting')
                                        <button type="button" class="btn-primary js-room-action js-start-session-button" data-url="{{ route('game.rooms.start', $room->code) }}">Mulai Sesi</button>
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
                <div id="chat-box" class="max-h-[22rem] space-y-3 overflow-y-auto bg-slate-50 p-4"></div>
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
        const feedbackUrl = @json(route('game.rooms.feedback', $room->code));
        const homeUrl = @json(route('home'));
        let isAnimatingShuffle = false;
        let latestTargetParticipantId = @json($room->current_target_participant_id);
        let unreadChatCount = 0;
        let lastMessageId = null;
        let sessionEndHandled = false;

        function escapeHtml(text) {
            return $('<div>').text(text ?? '').html();
        }

        function renderStatus(data) {
            const playing = data.status === 'playing' && data.current_card;
            const finished = data.status === 'finished';

            if (data.status !== 'waiting') {
                $('.js-start-session-button').remove();
            }

            $('#room-status-title').text(playing ? 'Kartu Aktif' : (finished ? 'Sesi Selesai' : 'Waiting Room'));
            $('#room-status-text').text(
                playing
                    ? `Kartu ${data.current_card_order} sedang aktif.`
                    : (finished
                        ? 'Sesi sudah diakhiri. Room tetap bisa dibuka untuk melihat kartu terakhir dan riwayat chat.'
                        : 'Menunggu host memulai permainan.')
            );

            const panel = $('#active-card-panel');
            const titleEl = $('#active-card-title');
            const questionEl = $('#active-card-question');
            const iconEl = $('#active-card-icon i');
            const targetBadge = $('#target-badge');
            const cardsRemainingBadge = $('#cards-remaining-badge');
            const target = data.target_participant || null;

            panel.removeClass('state-playing state-finished state-waiting');

            if (playing) {
                panel.addClass('state-playing');
                iconEl.attr('class', 'fa-regular fa-heart');
                titleEl.text(data.current_card.title);
                questionEl.text(data.current_card.question);
            } else if (finished) {
                panel.addClass('state-finished');
                iconEl.attr('class', 'fa-solid fa-circle-check');
                titleEl.text('Sesi telah selesai');
                questionEl.text('Room ini sudah diakhiri, tetapi kamu masih bisa membuka kembali halaman ini untuk melihat kartu terakhir, peserta, dan riwayat percakapan.');
            } else {
                panel.addClass('state-waiting');
                iconEl.attr('class', 'fa-regular fa-hourglass-half');
                titleEl.text('Ruang tunggu aktif');
                questionEl.text('Peserta bisa bergabung menggunakan kode room ini. Host dapat menyalakan mode anonymous dan mulai game saat semua siap.');
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
                    panel.removeClass('state-playing').addClass('state-waiting');
                    iconEl.attr('class', 'fa-solid fa-layer-group');
                    titleEl.text('Semua kartu sudah terbuka');
                    questionEl.text('Semua kartu pada deck ini sudah dibuka. Kamu bisa reset kartu untuk memulai putaran baru, atau akhiri sesi jika diskusi sudah selesai.');
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

        const avatarPalette = [
            'from-blue-500 to-indigo-600',
            'from-violet-500 to-purple-600',
            'from-emerald-500 to-teal-600',
            'from-amber-500 to-orange-600',
            'from-pink-500 to-rose-600',
        ];

        function renderParticipants(data) {
            const html = data.participants.map((participant) => {
                const initial = (participant.name || '?').trim().charAt(0).toUpperCase() || '?';
                const avatarColor = avatarPalette[initial.charCodeAt(0) % avatarPalette.length];
                const avatar = participant.photo_url
                    ? `<img src="${escapeHtml(participant.photo_url)}" alt="${escapeHtml(participant.name)}" class="h-11 w-11 rounded-full object-cover shadow-sm ring-2 ring-white">`
                    : `<div class="flex h-11 w-11 items-center justify-center rounded-full bg-gradient-to-br ${avatarColor} text-sm font-bold text-white shadow-sm ring-2 ring-white">${escapeHtml(initial)}</div>`;

                return `
                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 transition hover:border-blue-200 hover:shadow-sm">
                        <div class="relative shrink-0">
                            ${avatar}
                            ${participant.status === 'active' ? '<span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-white bg-emerald-500"></span>' : ''}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="truncate text-sm font-semibold text-slate-900">${escapeHtml(participant.name)}</div>
                            <div class="mt-0.5 text-xs text-slate-500">${participant.type === 'registered' ? 'Terdaftar' : 'Tamu'}</div>
                        </div>
                        ${participant.is_host ? '<span class="shrink-0 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Host</span>' : ''}
                    </div>
                `;
            }).join('');

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

        function showFeedbackThenLeave(message = 'Sesi telah selesai. Bagikan kritik dan saranmu sebelum keluar.') {
            if (sessionEndHandled) {
                return;
            }

            sessionEndHandled = true;

            Swal.fire({
                icon: 'info',
                title: 'Sesi Telah Berakhir',
                text: message,
                input: 'textarea',
                inputPlaceholder: 'Tulis kritik dan saran kamu di sini...',
                inputAttributes: {
                    'aria-label': 'Kritik dan saran',
                },
                showDenyButton: true,
                confirmButtonText: 'Kirim & Keluar',
                denyButtonText: 'Lewati',
                confirmButtonColor: '#2563eb',
                denyButtonColor: '#64748b',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((result) => {
                const feedbackMessage = (result.value || '').trim();

                if (result.isConfirmed && feedbackMessage) {
                    $.ajax({
                        url: feedbackUrl,
                        type: 'POST',
                        data: {
                            _token: window.csrfToken,
                            message: feedbackMessage,
                        }
                    }).always(function() {
                        window.location.href = homeUrl;
                    });
                    return;
                }

                window.location.href = homeUrl;
            });
        }

        function pollRoom() {
            if (sessionEndHandled) {
                return;
            }

            $.get(statusUrl)
                .done(function(data) {
                    if (data.finished) {
                        showFeedbackThenLeave();
                        return;
                    }
                    renderStatus(data);
                })
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        showFeedbackThenLeave();
                    }
                });

            $.get(participantsUrl)
                .done(renderParticipants)
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        showFeedbackThenLeave();
                    }
                });

            $.get(messagesUrl)
                .done(renderMessages)
                .fail(function(xhr) {
                    if (xhr.status === 403) {
                        showFeedbackThenLeave();
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
                    showFeedbackThenLeave('Sesi telah diakhiri. Bagikan kritik dan saranmu sebelum keluar.');
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

        // Interactive pointer tilt on the active card (desktop only)
        (function() {
            const cardPanel = document.getElementById('active-card-panel');
            const cardContent = document.getElementById('card-content');

            if (!cardPanel || !cardContent || !window.matchMedia('(pointer: fine)').matches) {
                return;
            }

            cardPanel.addEventListener('pointermove', function(event) {
                if (isAnimatingShuffle) {
                    return;
                }
                const rect = cardPanel.getBoundingClientRect();
                const px = (event.clientX - rect.left) / rect.width - 0.5;
                const py = (event.clientY - rect.top) / rect.height - 0.5;
                cardContent.style.transform = `rotateY(${px * 7}deg) rotateX(${-py * 7}deg)`;
            });

            cardPanel.addEventListener('pointerleave', function() {
                cardContent.style.transform = '';
            });
        })();

        pollRoom();
        setInterval(pollRoom, 1500);
    </script>
@endpush
