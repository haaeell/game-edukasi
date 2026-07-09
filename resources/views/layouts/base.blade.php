<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SoluShare — Ruang Belajar dan Konseling Interaktif')</title>
    <meta name="description" content="@yield('meta_description', 'SoluShare adalah platform konseling dan edukasi interaktif untuk membantu kamu memahami diri, belajar bersama, dan bertumbuh lebih baik melalui artikel, video, dan game edukasi.')">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon-512.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="SoluShare">
    <meta property="og:title" content="@yield('og_title', 'SoluShare — Ruang Belajar dan Konseling Interaktif')">
    <meta property="og:description" content="@yield('og_description', 'Platform konseling dan edukasi interaktif untuk membantu kamu memahami diri, belajar bersama, dan bertumbuh lebih baik setiap hari.')">
    <meta property="og:image" content="{{ asset('logo-header.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'SoluShare — Ruang Belajar dan Konseling Interaktif')">
    <meta name="twitter:description" content="@yield('og_description', 'Platform konseling dan edukasi interaktif untuk membantu kamu memahami diri, belajar bersama, dan bertumbuh lebih baik setiap hari.')">
    <meta name="twitter:image" content="{{ asset('logo-header.png') }}">

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "Organization",
            "name": "SoluShare",
            "url": "{{ url('/') }}",
            "logo": "{{ asset('logo-header.png') }}"
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    @stack('styles')
    <style>
        html {
            scroll-behavior: smooth;
        }

        :root {
            --navy-950: #07192a;
            --navy-900: #0d2d46;
            --brand-50: #f2fbff;
            --brand-100: #d8f3ff;
            --brand-200: #b7ebff;
            --brand-400: #21a7f6;
            --brand-500: #118ee9;
            --brand-600: #0c74cf;
            --accent-400: #68d64c;
            --accent-500: #4fc83e;
            --accent-600: #34aa37;
            --ink-900: #143047;
            --panel-shadow: 0 18px 50px rgba(12, 41, 64, 0.10);
        }

        body {
            margin: 0;
            min-width: 320px;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(17, 142, 233, 0.12), transparent 24%),
                radial-gradient(circle at right, rgba(79, 200, 62, 0.10), transparent 28%),
                linear-gradient(180deg, #f5fdff 0%, #eef9ff 48%, #f4fff8 100%);
            color: var(--ink-900);
        }

        .panel {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: var(--panel-shadow);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.7);
            box-shadow: var(--panel-shadow);
            backdrop-filter: blur(18px);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: linear-gradient(135deg, var(--brand-500), var(--accent-500));
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            transition: 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(17, 142, 233, 0.22);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            transition: 0.2s ease;
        }

        .btn-secondary:hover {
            background: var(--brand-50);
            border-color: rgba(17, 142, 233, 0.32);
            color: var(--brand-600);
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
        }

        .field {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid #cbd5e1;
            background: #fff;
            padding: 0.85rem 1rem;
            font-size: 0.875rem;
            outline: none;
        }

        .field:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 4px rgba(17, 142, 233, 0.12);
        }

        @media (max-width: 767px) {
            /* iOS Safari auto-zooms the page when a focused input's font-size is below 16px. */
            .field {
                font-size: 16px;
            }
        }

        .label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
        }

        .metric-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(245, 252, 255, 0.98));
            border: 1px solid #d9ebf3;
            border-radius: 1.5rem;
            box-shadow: 0 14px 32px rgba(9, 55, 88, 0.08);
        }

        .metric-card::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, rgba(17, 142, 233, 0.12), rgba(17, 142, 233, 0.82), rgba(79, 200, 62, 0.82));
        }

        .nav-chip {
            border-radius: 1rem;
            transition: 0.2s ease;
        }

        .nav-chip:hover {
            background: rgba(17, 142, 233, 0.08);
        }

        .nav-chip.active {
            background: linear-gradient(135deg, rgba(17, 142, 233, 0.14), rgba(79, 200, 62, 0.18));
            color: var(--brand-600);
            box-shadow: inset 0 0 0 1px rgba(17, 142, 233, 0.14);
        }

        .icon-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
        }
    </style>
</head>
<body class="min-h-screen">
    @yield('body')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $(document).on('submit', '.delete-form', function (event) {
            event.preventDefault();

            const form = this;

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang sudah dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#2563eb'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
