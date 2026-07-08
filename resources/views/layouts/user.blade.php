@extends('layouts.base')

@section('body')
    <div class="min-h-screen bg-[#eef4fb]">
        <div class="glass-panel min-h-screen rounded-none border-0">
            <div class="border-b border-slate-200/80 px-4 py-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Dashboard Pengguna</h1>
                    <p class="mt-2 text-sm text-slate-500">Ruang aman untuk belajar, berbagi, dan bertumbuh bersama.</p>
                </div>

                <div class="mt-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div class="min-w-0 flex items-center gap-4">
                        <a href="{{ route('user.dashboard') }}" class="icon-badge flex h-14 w-14 rounded-2xl border border-blue-100 bg-white text-2xl text-blue-600 shadow-sm"><i class="fa-regular fa-heart"></i></a>

                        <nav class="hidden min-w-0 items-center gap-2 md:flex">
                            <a href="{{ route('user.dashboard') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.dashboard') ? 'active' : 'text-slate-600' }}">Beranda</a>
                            <a href="{{ route('user.articles.index') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.articles.*') ? 'active' : 'text-slate-600' }}">Artikel</a>
                            <a href="{{ route('user.videos.index') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.videos.*') ? 'active' : 'text-slate-600' }}">Video</a>
                            <a href="{{ route('user.game.index') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.game.*') ? 'active' : 'text-slate-600' }}">Game</a>
                            <a href="{{ route('user.profile.edit') }}" class="nav-chip px-4 py-3 text-sm font-semibold {{ request()->routeIs('user.profile.*') ? 'active' : 'text-slate-600' }}">Profil</a>
                        </nav>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <div class="relative" data-user-dropdown>
                            <button type="button" class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm" data-user-trigger>
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-100 to-pink-100 text-lg text-slate-700"><i class="fa-solid fa-user"></i></div>
                                <div class="text-left text-sm">
                                    <div class="font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                                    <div class="text-slate-500">Peserta</div>
                                </div>
                                <span class="ml-2 text-slate-400 transition" data-user-chevron><i class="fa-solid fa-chevron-down"></i></span>
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

                <nav class="mt-4 grid grid-cols-2 gap-2 md:hidden">
                    <a href="{{ route('user.dashboard') }}" class="nav-chip rounded-2xl px-4 py-3 text-center text-sm font-semibold {{ request()->routeIs('user.dashboard') ? 'active' : 'bg-white text-slate-600' }}">Beranda</a>
                    <a href="{{ route('user.articles.index') }}" class="nav-chip rounded-2xl px-4 py-3 text-center text-sm font-semibold {{ request()->routeIs('user.articles.*') ? 'active' : 'bg-white text-slate-600' }}">Artikel</a>
                    <a href="{{ route('user.videos.index') }}" class="nav-chip rounded-2xl px-4 py-3 text-center text-sm font-semibold {{ request()->routeIs('user.videos.*') ? 'active' : 'bg-white text-slate-600' }}">Video</a>
                    <a href="{{ route('user.game.index') }}" class="nav-chip rounded-2xl px-4 py-3 text-center text-sm font-semibold {{ request()->routeIs('user.game.*') ? 'active' : 'bg-white text-slate-600' }}">Game</a>
                    <a href="{{ route('user.profile.edit') }}" class="nav-chip col-span-2 rounded-2xl px-4 py-3 text-center text-sm font-semibold {{ request()->routeIs('user.profile.*') ? 'active' : 'bg-white text-slate-600' }}">Profil</a>
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
        });
    </script>
@endpush
