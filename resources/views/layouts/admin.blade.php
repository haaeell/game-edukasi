@extends('layouts.base')

@section('title', (trim($__env->yieldContent('page-title')) ?: 'Admin').' — SoluShare')

@push('styles')
    <style>
        .admin-sidebar {
            background:
                radial-gradient(circle at top, rgba(33, 167, 246, 0.24), transparent 32%),
                radial-gradient(circle at bottom right, rgba(104, 214, 76, 0.16), transparent 28%),
                linear-gradient(180deg, #07192a 0%, #0b2940 52%, #10334d 100%);
        }

        .admin-nav-link {
            color: rgba(240, 249, 255, 0.82);
        }

        .admin-nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
        }

        .admin-nav-link.active {
            background: linear-gradient(135deg, rgba(17, 142, 233, 0.96), rgba(79, 200, 62, 0.92));
            color: #fff;
            box-shadow: 0 18px 32px rgba(17, 142, 233, 0.22);
        }

        .admin-settings-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.07), rgba(255, 255, 255, 0.04));
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .admin-settings-card:hover {
            border-color: rgba(104, 214, 76, 0.32);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.06));
        }

        .admin-profile-avatar {
            background: linear-gradient(135deg, rgba(17, 142, 233, 0.14), rgba(104, 214, 76, 0.2));
            color: #15517b;
        }

        .admin-mobile-card {
            border-radius: 1.5rem;
            border: 1px solid #e2e8f0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(247, 250, 252, 0.98));
            box-shadow: 0 14px 30px rgba(12, 41, 64, 0.06);
        }

        .admin-detail-pair {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
        }

        .admin-detail-pair-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .admin-detail-pair-value {
            min-width: 0;
            text-align: right;
            color: #334155;
        }

        @media (max-width: 1023px) {
            #admin-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 50;
                display: flex;
                height: 100vh;
                border-radius: 0 2rem 2rem 0;
                transform: translateX(-110%);
                transition: transform 0.25s ease;
            }

            #admin-sidebar.is-open {
                transform: translateX(0);
            }

            .admin-mobile-hide {
                display: none;
            }
        }

        @media (max-width: 639px) {
            .admin-detail-pair {
                flex-direction: column;
                gap: 0.35rem;
            }

            .admin-detail-pair-value {
                text-align: left;
            }
        }

        @media (min-width: 1024px) {
            .admin-shell.is-collapsed #admin-sidebar {
                width: 104px;
            }

            .admin-shell.is-collapsed [data-admin-brand] {
                justify-content: center;
            }

            .admin-shell.is-collapsed [data-admin-brand-copy],
            .admin-shell.is-collapsed [data-admin-menu-label],
            .admin-shell.is-collapsed [data-admin-nav-text],
            .admin-shell.is-collapsed [data-admin-settings-copy],
            .admin-shell.is-collapsed [data-admin-settings-title] {
                display: none;
            }

            .admin-shell.is-collapsed [data-admin-nav-link] {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }

            .admin-shell.is-collapsed [data-admin-settings-card] {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('body')
    <div class="min-h-screen bg-[#eef4fb]">
        <div class="admin-shell flex min-h-screen">
            <div id="admin-sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-slate-950/35 backdrop-blur-[2px] lg:hidden"></div>

            <aside id="admin-sidebar" class="admin-sidebar hidden h-screen w-[280px] shrink-0 overflow-y-auto border-r border-white/10 text-white shadow-2xl lg:sticky lg:top-0 lg:flex lg:flex-col">
                <div class="p-7">
                    <div class="flex items-center gap-3" data-admin-brand>
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 p-2 shadow-lg shadow-blue-500/20">
                            <img src="{{ asset('favicon-96.png') }}" alt="SoluShare" class="h-full w-full object-contain">
                        </div>
                        <div data-admin-brand-copy>
                            <a href="{{ route('admin.dashboard') }}" class="block text-2xl font-bold tracking-tight">SoluShare</a>
                            <p class="mt-1 text-sm text-blue-100/70">Platform admin interaktif</p>
                        </div>
                    </div>
                </div>

                <div class="px-7 text-[11px] font-semibold uppercase tracking-[0.28em] text-blue-100/45" data-admin-menu-label>Menu Utama</div>

                <nav class="mt-5 flex-1 space-y-2 px-4 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-solid fa-house w-5 text-center"></i>
                        <span data-admin-nav-text>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.articles.index') }}" class="admin-nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-regular fa-newspaper w-5 text-center"></i>
                        <span data-admin-nav-text>Articles</span>
                    </a>
                    <a href="{{ route('admin.videos.index') }}" class="admin-nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-regular fa-circle-play w-5 text-center"></i>
                        <span data-admin-nav-text>Videos</span>
                    </a>
                    <a href="{{ route('admin.game-card-sets.index') }}" class="admin-nav-link {{ request()->routeIs('admin.game-card-sets.*', 'admin.game-cards.*') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-regular fa-clone w-5 text-center"></i>
                        <span data-admin-nav-text>Card Sets</span>
                    </a>
                    <a href="{{ route('admin.room-reports.index') }}" class="admin-nav-link {{ request()->routeIs('admin.room-reports.*') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-regular fa-file-lines w-5 text-center"></i>
                        <span data-admin-nav-text>Laporan</span>
                    </a>
                    <a href="{{ route('admin.peserta.index') }}" class="admin-nav-link {{ request()->routeIs('admin.peserta.*') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-solid fa-users w-5 text-center"></i>
                        <span data-admin-nav-text>Peserta</span>
                    </a>
                    <a href="{{ route('admin.settings.account.edit') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.account.*') ? 'active' : '' }} flex items-center gap-3 rounded-2xl px-4 py-3 transition" data-admin-nav-link>
                        <i class="fa-solid fa-gear w-5 text-center"></i>
                        <span data-admin-nav-text>Pengaturan Akun</span>
                    </a>
                </nav>

                <div class="mt-auto border-t border-white/10 p-5">
                    <a href="{{ route('admin.settings.account.edit') }}" class="admin-settings-card block rounded-2xl p-4 transition" data-admin-settings-card>
                        <div class="text-sm font-semibold" data-admin-settings-title>Pengaturan Akun</div>
                        <div class="mt-1 text-xs text-blue-100/60" data-admin-settings-copy>Ubah email login dan password admin kapan saja.</div>
                    </a>
                </div>
            </aside>

            <main class="glass-panel min-w-0 flex-1 rounded-none border-0 px-4 py-5 sm:px-6 lg:px-8">
                <div class="mb-6 border-b border-slate-200/80 pb-5">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold tracking-tight text-slate-900">@yield('page-title')</h1>
                        <p class="mt-2 text-sm text-slate-500">@yield('page-description')</p>
                    </div>

                    <div class="mt-6 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-center">
                            <button id="admin-sidebar-toggle" type="button" class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-sky-300 hover:text-sky-600">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div class="relative min-w-0 flex-1 xl:w-[360px]">
                                <input id="admin-search-input" type="text" class="field h-12 pl-11 pr-24" placeholder="Cari sesuatu...">
                                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <button id="admin-search-shortcut" type="button" class="admin-mobile-hide absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-500 transition hover:bg-slate-200">Ctrl K</button>
                                <div id="admin-search-results" class="absolute left-0 right-0 top-[calc(100%+0.75rem)] z-30 hidden overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-2xl">
                                    <div class="border-b border-slate-100 px-4 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Quick Search</div>
                                    <div id="admin-search-results-list" class="max-h-80 overflow-y-auto py-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-end gap-3">
                            <div class="admin-mobile-hide flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-lg text-slate-500 shadow-sm"><i class="fa-regular fa-bell"></i></div>

                            <div class="relative min-w-0" data-profile-dropdown>
                                <button type="button" class="flex w-full items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm sm:w-auto" data-profile-trigger>
                                    <div class="admin-profile-avatar flex h-12 w-12 items-center justify-center rounded-2xl text-lg"><i class="fa-solid fa-user-tie"></i></div>
                                    <div class="min-w-0 flex-1 text-left text-sm sm:flex-none">
                                        <div class="font-semibold text-slate-900">{{ auth()->user()->name }}</div>
                                        <div class="text-slate-500">Super Admin</div>
                                    </div>
                                    <span class="ml-2 text-slate-400 transition" data-profile-chevron><i class="fa-solid fa-chevron-down"></i></span>
                                </button>

                                <div class="absolute left-0 right-0 top-[calc(100%+0.75rem)] z-30 hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl sm:left-auto sm:right-0 sm:w-56" data-profile-menu>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                        <i class="fa-solid fa-house w-4 text-center"></i>
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('admin.game-card-sets.index') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                        <i class="fa-regular fa-clone w-4 text-center"></i>
                                        <span>Card Sets</span>
                                    </a>
                                    <a href="{{ route('admin.settings.account.edit') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                        <i class="fa-solid fa-gear w-4 text-center"></i>
                                        <span>Pengaturan Akun</span>
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
                </div>

                @yield('content')
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const shell = $('.admin-shell');
            const sidebar = $('#admin-sidebar');
            const backdrop = $('#admin-sidebar-backdrop');
            const sidebarToggle = $('#admin-sidebar-toggle');
            const searchInput = $('#admin-search-input');
            const searchShortcut = $('#admin-search-shortcut');
            const searchResults = $('#admin-search-results');
            const searchResultsList = $('#admin-search-results-list');
            const contentRoot = $('main').first();
            const dropdown = $('[data-profile-dropdown]');
            const trigger = dropdown.find('[data-profile-trigger]');
            const menu = dropdown.find('[data-profile-menu]');
            const chevron = dropdown.find('[data-profile-chevron]');
            const searchItems = [];

            function isDesktop() {
                return window.innerWidth >= 1024;
            }

            function lockBody(locked) {
                $('body').toggleClass('overflow-hidden', locked);
            }

            function openSidebar() {
                sidebar.removeClass('hidden').addClass('is-open');
                backdrop.removeClass('hidden');
                lockBody(true);
            }

            function closeSidebar() {
                if (isDesktop()) {
                    backdrop.addClass('hidden');
                    lockBody(false);
                    return;
                }

                sidebar.removeClass('is-open');
                backdrop.addClass('hidden');
                lockBody(false);

                window.setTimeout(function() {
                    if (!isDesktop()) {
                        sidebar.addClass('hidden');
                    }
                }, 250);
            }

            function syncSidebarState() {
                if (isDesktop()) {
                    sidebar.removeClass('hidden is-open');
                    backdrop.addClass('hidden');
                    lockBody(false);
                } else {
                    shell.removeClass('is-collapsed');
                    sidebar.addClass('hidden').removeClass('is-open');
                    backdrop.addClass('hidden');
                    lockBody(false);
                }
            }

            function escapeHtml(value) {
                return $('<div>').text(value || '').html();
            }

            function addSearchItem(item) {
                if (!item.label) {
                    return;
                }

                const alreadyExists = searchItems.some(function(candidate) {
                    return candidate.label === item.label && candidate.type === item.type;
                });

                if (!alreadyExists) {
                    searchItems.push(item);
                }
            }

            function collectSearchItems() {
                sidebar.find('[data-admin-nav-link]').each(function() {
                    const link = $(this);
                    addSearchItem({
                        label: $.trim(link.text()),
                        meta: 'Menu navigasi',
                        type: 'link',
                        href: link.attr('href'),
                        icon: 'fa-solid fa-arrow-up-right-from-square'
                    });
                });

                contentRoot.find('h2, h3').each(function(index) {
                    const element = $(this);
                    const label = $.trim(element.text()).replace(/\s+/g, ' ');

                    if (!label || label.length < 4 || label.length > 90) {
                        return;
                    }

                    if (!element.attr('id')) {
                        element.attr('id', 'admin-search-target-' + index);
                    }

                    addSearchItem({
                        label: label,
                        meta: 'Bagian halaman',
                        type: 'section',
                        target: '#' + element.attr('id'),
                        icon: 'fa-solid fa-layer-group'
                    });
                });
            }

            function hideSearchResults() {
                searchResults.addClass('hidden');
                searchResultsList.empty();
            }

            function renderSearchResults(query) {
                const normalizedQuery = $.trim(query).toLowerCase();

                if (!normalizedQuery) {
                    hideSearchResults();
                    return;
                }

                const results = searchItems.filter(function(item) {
                    return (item.label + ' ' + item.meta).toLowerCase().indexOf(normalizedQuery) !== -1;
                }).slice(0, 8);

                if (!results.length) {
                    searchResultsList.html(
                        '<div class="px-4 py-4 text-sm text-slate-500">Tidak ada hasil untuk <span class="font-semibold text-slate-700">' + escapeHtml(query) + '</span>.</div>'
                    );
                    searchResults.removeClass('hidden');
                    return;
                }

                const resultMarkup = results.map(function(item, index) {
                    return '' +
                        '<button type="button" class="flex w-full items-start gap-3 px-4 py-3 text-left transition hover:bg-slate-50 ' + (index === 0 ? 'bg-blue-50/60' : '') + '" data-search-type="' + item.type + '" data-search-href="' + (item.href || '') + '" data-search-target="' + (item.target || '') + '">' +
                            '<span class="mt-1 flex h-9 w-9 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">' +
                                '<i class="' + item.icon + '"></i>' +
                            '</span>' +
                            '<span class="min-w-0">' +
                                '<span class="block truncate text-sm font-semibold text-slate-800">' + escapeHtml(item.label) + '</span>' +
                                '<span class="mt-1 block truncate text-xs text-slate-500">' + escapeHtml(item.meta) + '</span>' +
                            '</span>' +
                        '</button>';
                }).join('');

                searchResultsList.html(resultMarkup);
                searchResults.removeClass('hidden');
            }

            function activateSearchResult(button) {
                const result = $(button);
                const type = result.data('search-type');
                const href = result.data('search-href');
                const target = result.data('search-target');

                if (type === 'link' && href) {
                    window.location.href = href;
                    return;
                }

                if (type === 'section' && target) {
                    const section = $(target);

                    if (section.length) {
                        hideSearchResults();
                        $('html, body').animate({
                            scrollTop: Math.max(section.offset().top - 120, 0)
                        }, 250);
                        section.addClass('ring-4 ring-blue-200 ring-offset-4');

                        window.setTimeout(function() {
                            section.removeClass('ring-4 ring-blue-200 ring-offset-4');
                        }, 1200);
                    }
                }
            }

            function focusSearch() {
                searchInput.trigger('focus');
                if (searchInput.length) {
                    searchInput.get(0).select();
                }
            }

            sidebarToggle.on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                if (isDesktop()) {
                    shell.toggleClass('is-collapsed');
                    return;
                }

                if (sidebar.hasClass('hidden') || !sidebar.hasClass('is-open')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            backdrop.on('click', closeSidebar);

            $(window).on('resize', syncSidebarState);

            searchShortcut.on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                focusSearch();
            });

            searchInput.on('focus input', function() {
                renderSearchResults($(this).val());
            });

            searchInput.on('keydown', function(event) {
                if (event.key === 'Enter') {
                    const firstResult = searchResultsList.find('[data-search-type]').first();

                    if (firstResult.length) {
                        event.preventDefault();
                        activateSearchResult(firstResult);
                    }
                }
            });

            searchResults.on('click', '[data-search-type]', function(event) {
                event.preventDefault();
                activateSearchResult(this);
            });

            trigger.on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                menu.toggleClass('hidden');
                chevron.toggleClass('rotate-180');
            });

            $(document).on('keydown', function(event) {
                if ((event.ctrlKey || event.metaKey) && String(event.key).toLowerCase() === 'k') {
                    event.preventDefault();
                    focusSearch();
                }

                if (event.key === 'Escape') {
                    closeSidebar();
                    hideSearchResults();
                    menu.addClass('hidden');
                    chevron.removeClass('rotate-180');
                }
            });

            $(document).on('click', function() {
                hideSearchResults();
                menu.addClass('hidden');
                chevron.removeClass('rotate-180');
            });

            menu.on('click', function(event) {
                event.stopPropagation();
            });

            searchInput.on('click', function(event) {
                event.stopPropagation();
            });

            searchResults.on('click', function(event) {
                event.stopPropagation();
            });

            collectSearchItems();
            syncSidebarState();
        });
    </script>
@endpush
