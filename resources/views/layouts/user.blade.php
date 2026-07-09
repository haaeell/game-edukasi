@extends('layouts.base')

@section('title', 'Dashboard — SoluShare')

@section('body')
    <div class="min-h-screen bg-[#eef4fb]">
        <div class="glass-panel min-h-screen rounded-none border-0">
            <div class="border-b border-slate-200/80 px-4 py-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Dashboard Pengguna</h1>
                    <p class="mt-2 text-sm text-slate-500">Ruang aman untuk belajar, berbagi, dan bertumbuh bersama.</p>
                </div>

                <div class="mt-6 flex items-center justify-between gap-3">
                    <a href="{{ route('user.dashboard') }}" class="flex h-12 shrink-0 items-center  md:h-14">
                        <img src="{{ asset('logo-header.png') }}" alt="SoluShare" class="h-10 w-auto object-contain md:h-11">
                    </a>

                    <nav class="hidden min-w-0 items-center gap-2 md:flex">
                        <a href="{{ route('user.dashboard') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.dashboard') ? 'active' : 'text-slate-600' }}">Beranda</a>
                        <a href="{{ route('user.articles.index') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.articles.*') ? 'active' : 'text-slate-600' }}">Artikel</a>
                        <a href="{{ route('user.videos.index') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.videos.*') ? 'active' : 'text-slate-600' }}">Video</a>
                        <a href="{{ route('user.game.index') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.game.*') ? 'active' : 'text-slate-600' }}">Game</a>
                        <a href="{{ route('user.profile.edit') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.profile.*') ? 'active' : 'text-slate-600' }}">Profil</a>
                    </nav>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="userNavToggle"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-lg text-slate-600 transition hover:border-blue-200 hover:text-blue-600 md:hidden"
                            aria-controls="userNavMenu"
                            aria-expanded="false"
                            aria-label="Buka menu navigasi"
                        >
                            <i class="fa-solid fa-bars" id="userNavToggleIcon"></i>
                        </button>

                        <div class="relative" data-user-dropdown>
                            <button type="button" class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-2 py-2 shadow-sm sm:gap-3 sm:px-3" data-user-trigger>
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-pink-100 text-base text-slate-700 sm:h-12 sm:w-12 sm:text-lg"><i class="fa-solid fa-user"></i></div>
                                <div class="hidden text-left text-sm sm:block">
                                    <div class="font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                                    <div class="text-slate-500">Peserta</div>
                                </div>
                                <span class="hidden text-slate-400 transition sm:ml-2 sm:inline" data-user-chevron><i class="fa-solid fa-chevron-down"></i></span>
                            </button>

                            <div class="absolute right-0 top-[calc(100%+0.75rem)] z-30 hidden w-56 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl" data-user-menu>
                                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                    <i class="fa-solid fa-house w-4 text-center"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('user.profile.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                    <i class="fa-regular fa-user w-4 text-center"></i>
                                    <span>Profil</span>
                                </a>
                                <div class="my-2 border-t border-slate-100"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <nav id="userNavMenu" class="mt-4 hidden flex-col gap-2 md:hidden">
                    <a href="{{ route('user.dashboard') }}" class="nav-chip flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.dashboard') ? 'active' : 'bg-white text-slate-600' }}"><i class="fa-solid fa-house w-4 text-center"></i>Beranda</a>
                    <a href="{{ route('user.articles.index') }}" class="nav-chip flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.articles.*') ? 'active' : 'bg-white text-slate-600' }}"><i class="fa-regular fa-newspaper w-4 text-center"></i>Artikel</a>
                    <a href="{{ route('user.videos.index') }}" class="nav-chip flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.videos.*') ? 'active' : 'bg-white text-slate-600' }}"><i class="fa-regular fa-circle-play w-4 text-center"></i>Video</a>
                    <a href="{{ route('user.game.index') }}" class="nav-chip flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.game.*') ? 'active' : 'bg-white text-slate-600' }}"><i class="fa-solid fa-dice w-4 text-center"></i>Game</a>
                    <a href="{{ route('user.profile.edit') }}" class="nav-chip flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.profile.*') ? 'active' : 'bg-white text-slate-600' }}"><i class="fa-regular fa-user w-4 text-center"></i>Profil</a>
                </nav>
            </div>

            <main class="px-4 py-8 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const dropdown = $('[data-user-dropdown]');
            const trigger = dropdown.find('[data-user-trigger]');
            const menu = dropdown.find('[data-user-menu]');
            const chevron = dropdown.find('[data-user-chevron]');

            trigger.on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                menu.toggleClass('hidden');
                chevron.toggleClass('rotate-180');
            });

            $(document).on('click', function() {
                menu.addClass('hidden');
                chevron.removeClass('rotate-180');
            });

            menu.on('click', function(event) {
                event.stopPropagation();
            });

            const navToggle = $('#userNavToggle');
            const navMenu = $('#userNavMenu');
            const navToggleIcon = $('#userNavToggleIcon');

            function closeNavMenu() {
                navMenu.addClass('hidden').removeClass('flex');
                navToggle.attr('aria-expanded', 'false');
                navToggleIcon.removeClass('fa-xmark').addClass('fa-bars');
            }

            navToggle.on('click', function(event) {
                event.stopPropagation();
                const isHidden = navMenu.hasClass('hidden');
                navMenu.toggleClass('hidden', !isHidden).toggleClass('flex', isHidden);
                navToggle.attr('aria-expanded', isHidden ? 'true' : 'false');
                navToggleIcon.toggleClass('fa-bars', !isHidden).toggleClass('fa-xmark', isHidden);
            });

            navMenu.on('click', 'a', closeNavMenu);

            $(window).on('resize', function() {
                if (window.innerWidth >= 768) {
                    closeNavMenu();
                }
            });
        });
    </script>
@endpush
