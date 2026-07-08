@extends('layouts.base')

@section('body')
    <div class="min-h-screen bg-[#eef4fb]">
        <div class="p-0 lg:p-5">
            <div class="glass-panel min-h-screen overflow-hidden rounded-none border-0 p-4 sm:p-5 lg:min-h-0 lg:rounded-[2.2rem] lg:border lg:p-6">
                <div class="space-y-12">
                    <section class="panel overflow-hidden p-6 lg:p-8">
                        <div class="flex flex-col gap-6">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <a href="{{ route('home') }}" class="flex items-center gap-3">
                                    <div class="icon-badge flex h-12 w-12 rounded-2xl border border-blue-100 bg-white text-2xl text-blue-600 shadow-sm"><i class="fa-regular fa-heart"></i></div>
                                    <div>
                                        <div class="text-2xl font-bold tracking-tight text-slate-900">RuangKonseling</div>
                                        <div class="text-sm text-slate-500">Platform Konseling</div>
                                    </div>
                                </a>

                                <div class="hidden items-center gap-6 text-sm font-semibold text-slate-600 lg:flex">
                                    <a href="#beranda" class="transition hover:text-blue-600">Beranda</a>
                                    <a href="#fitur" class="transition hover:text-blue-600">Fitur</a>
                                    <a href="#siapa" class="transition hover:text-blue-600">Untuk Siapa</a>
                                    <a href="#tentang" class="transition hover:text-blue-600">Tentang</a>
                                    <a href="#blog" class="transition hover:text-blue-600">Blog</a>
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
                                    <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                                </div>
                            </div>

                            <div id="beranda" class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
                                <div>
                                    <h1 class="mt-6 text-5xl font-bold leading-[1.08] tracking-tight text-slate-900">
                                        Ruang aman untuk
                                        <span class="text-blue-600">bertumbuh</span>
                                        dan menemukan solusi
                                    </h1>
                                    <p class="mt-5 max-w-xl text-base leading-8 text-slate-500">
                                        RuangKonseling adalah platform konseling interaktif yang membantu kamu memahami diri, belajar bersama, dan berkembang lebih baik setiap hari.
                                    </p>
                                    <div class="mt-8 flex flex-wrap gap-3">
                                        <a href="{{ route('register') }}" class="btn-primary">Mulai Sekarang</a>
                                        <a href="#fitur" class="btn-secondary">Lihat Fitur</a>
                                    </div>
                                </div>

                                <div class="relative">
                                    <div class="absolute inset-x-10 inset-y-8 rounded-[3rem] bg-[radial-gradient(circle_at_top,_rgba(96,165,250,0.2),_transparent_45%),linear-gradient(180deg,_rgba(219,234,254,0.8),_rgba(255,255,255,0.2))]"></div>
                                    <div class="relative mx-auto flex max-w-xl items-end justify-center gap-6 px-6 py-8">
                                        <div class="hidden h-28 w-28 rounded-[2rem] bg-gradient-to-br from-slate-200 to-blue-100 lg:block"></div>
                                        <div class="rounded-[2.4rem] bg-gradient-to-b from-[#f6f8ff] to-[#dde8ff] px-8 py-10 shadow-lg">
                                            <div class="flex items-end gap-6">
                                                <div class="flex w-40 flex-col items-center">
                                                    <div class="flex h-32 w-28 items-center justify-center rounded-t-[2.4rem] bg-white text-4xl text-slate-700 shadow"><i class="fa-solid fa-user-doctor"></i></div>
                                                    <div class="h-20 w-36 rounded-[2rem] bg-[#294a95]"></div>
                                                </div>
                                                <div class="flex w-40 flex-col items-center">
                                                    <div class="flex h-32 w-28 items-center justify-center rounded-t-[2.4rem] bg-white text-4xl text-slate-700 shadow"><i class="fa-solid fa-user"></i></div>
                                                    <div class="h-20 w-36 rounded-[2rem] bg-[#4767d4]"></div>
                                                </div>
                                            </div>
                                            <div class="mt-4 flex justify-center gap-4 text-2xl">
                                                <span class="text-blue-600"><i class="fa-solid fa-heart"></i></span>
                                                <span class="text-slate-500"><i class="fa-regular fa-comment-dots"></i></span>
                                            </div>
                                        </div>
                                        <div class="hidden h-24 w-24 rounded-[2rem] bg-gradient-to-br from-slate-200 to-blue-100 lg:block"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                                @foreach ([
                                    ['value' => '10K+', 'label' => 'Pengguna Aktif', 'icon' => 'fa-solid fa-users'],
                                    ['value' => '500+', 'label' => 'Artikel Edukasi', 'icon' => 'fa-regular fa-newspaper'],
                                    ['value' => '300+', 'label' => 'Video Inspiratif', 'icon' => 'fa-regular fa-circle-play'],
                                ] as $metric)
                                    <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-700"><i class="{{ $metric['icon'] }}"></i></div>
                                            <div>
                                                <div class="text-2xl font-bold text-slate-900">{{ $metric['value'] }}</div>
                                                <div class="text-sm text-slate-500">{{ $metric['label'] }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="fitur" class="panel p-6 lg:p-8">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Fitur yang Mendukung Perjalananmu</h2>
                            <p class="mt-3 text-sm text-slate-500">Berbagai fitur interaktif untuk pengalaman belajar dan konseling yang lebih bermakna.</p>
                        </div>
                        <div class="mt-8 grid gap-5 lg:grid-cols-3">
                            @foreach ([
                                ['title' => 'Artikel Edukasi', 'desc' => 'Baca artikel tentang self-reflection, kesehatan diri, dan pengembangan diri.', 'accent' => 'bg-blue-50 text-blue-700', 'cta' => 'Jelajahi Artikel'],
                                ['title' => 'Video Inspiratif', 'desc' => 'Tonton video edukasi dan motivasi untuk mendukung kesehatan mentalmu.', 'accent' => 'bg-violet-50 text-violet-700', 'cta' => 'Jelajahi Video'],
                                ['title' => 'Game Edukasi', 'desc' => 'Mainkan game edukasi yang menyenangkan dan membantu pengembangan diri.', 'accent' => 'bg-emerald-50 text-emerald-700', 'cta' => 'Mulai Bermain'],
                            ] as $feature)
                                <div class="metric-card p-6">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $feature['accent'] }} text-2xl"><i class="fa-regular fa-compass"></i></div>
                                    <h3 class="mt-5 text-2xl font-bold text-slate-900">{{ $feature['title'] }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-slate-500">{{ $feature['desc'] }}</p>
                                    <div class="mt-6 text-sm font-semibold text-blue-600">{{ $feature['cta'] }} →</div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="panel p-8 text-center">
                        <div class="mx-auto max-w-2xl">
                            <div class="text-5xl text-slate-300">“</div>
                            <p class="mt-2 text-lg leading-8 text-slate-600">RuangKonseling membantuku memahami diri sendiri lebih dalam dan merasa tidak sendirian.</p>
                            <div class="mt-8 flex items-center justify-center gap-4">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-orange-100 to-pink-100 text-lg text-slate-700"><i class="fa-solid fa-user"></i></div>
                                <div class="text-left">
                                    <div class="font-semibold text-slate-900">Alya Putri</div>
                                    <div class="text-sm text-slate-500">Pengguna Aktif</div>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-center gap-2">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                                <span class="h-2.5 w-2.5 rounded-full bg-slate-300"></span>
                            </div>
                        </div>
                    </section>

                    <section class="panel overflow-hidden bg-[linear-gradient(135deg,_rgba(219,234,254,0.8),_rgba(255,255,255,0.95))] p-8 text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-slate-900">Siap memulai perjalanan terbaikmu?</h2>
                        <p class="mt-3 text-sm text-slate-500">Bergabung sekarang dan temukan ruang aman untuk bertumbuh bersama.</p>
                        <div class="mt-6 flex flex-wrap justify-center gap-3">
                            <a href="{{ route('register') }}" class="btn-primary">Daftar Gratis</a>
                            <a href="{{ route('login') }}" class="btn-secondary">Pelajari Lebih Lanjut</a>
                        </div>
                    </section>

                    <footer class="overflow-hidden rounded-[2rem] bg-[radial-gradient(circle_at_top_right,_rgba(96,165,250,0.2),_transparent_25%),linear-gradient(135deg,_#1539b2_0%,_#1e40af_45%,_#132b7a_100%)] p-8 text-white shadow-2xl">
                        <div class="grid gap-8 lg:grid-cols-[1.2fr_1fr_1fr_1fr]">
                            <div>
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-2xl"><i class="fa-regular fa-heart"></i></div>
                                    <div class="text-2xl font-bold">RuangKonseling</div>
                                </div>
                                <p class="mt-4 max-w-sm text-sm leading-7 text-blue-100/80">Platform konseling interaktif untuk mendukung kesehatan mental dan pengembangan diri.</p>
                                <div class="mt-5 flex gap-3 text-lg text-blue-100/90">
                                    <span><i class="fa-brands fa-facebook-f"></i></span>
                                    <span><i class="fa-brands fa-instagram"></i></span>
                                    <span><i class="fa-brands fa-x-twitter"></i></span>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.22em] text-blue-100/70">Platform</div>
                                <div class="mt-4 space-y-3 text-sm text-blue-50/90">
                                    <div>Artikel</div>
                                    <div>Video</div>
                                    <div>Konseling</div>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.22em] text-blue-100/70">Perusahaan</div>
                                <div class="mt-4 space-y-3 text-sm text-blue-50/90">
                                    <div>Tentang Kami</div>
                                    <div>Blog</div>
                                    <div>Hubungi Kami</div>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold uppercase tracking-[0.22em] text-blue-100/70">Bantuan</div>
                                <div class="mt-4 space-y-3 text-sm text-blue-50/90">
                                    <div>FAQ</div>
                                    <div>Kebijakan Privasi</div>
                                    <div>Syarat & Ketentuan</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8 border-t border-white/10 pt-6 text-center text-sm text-blue-100/70">© 2024 RuangKonseling. All rights reserved.</div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
@endsection
