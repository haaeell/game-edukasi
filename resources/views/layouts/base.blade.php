<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Game Edukasi' }}</title>
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
        :root {
            --navy-950: #081227;
            --navy-900: #0d1b38;
            --brand-500: #3b82f6;
            --brand-600: #2563eb;
            --panel-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
        }

        body {
            margin: 0;
            min-width: 320px;
            overflow-x: hidden;
            font-family: 'Poppins', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.08), transparent 22%),
                radial-gradient(circle at right, rgba(14, 165, 233, 0.08), transparent 26%),
                #eef4fb;
            color: #1e293b;
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
            background: linear-gradient(135deg, var(--brand-500), var(--brand-600));
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #fff;
            transition: 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(37, 99, 235, 0.22);
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
            background: #f8fafc;
            border-color: #93c5fd;
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
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.98));
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
        }

        .metric-card::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), rgba(59, 130, 246, 0.75), rgba(16, 185, 129, 0.75));
        }

        .nav-chip {
            border-radius: 1rem;
            transition: 0.2s ease;
        }

        .nav-chip:hover {
            background: rgba(59, 130, 246, 0.08);
        }

        .nav-chip.active {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.14), rgba(96, 165, 250, 0.2));
            color: #1d4ed8;
            box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.12);
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
