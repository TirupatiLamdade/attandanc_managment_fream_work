<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Attendance Management System')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        appBg: '#0B1120',
                        surface: '#111827',
                        card: '#172033',
                        cardHover: '#1E293B',
                        borderCol: '#334155',

                        textPrimary: '#F8FAFC',
                        textSecondary: '#CBD5E1',
                        textMuted: '#94A3B8',

                        primaryBtn: '#3B82F6',
                        primaryBtnHover: '#2563EB',

                        orangeAccent: '#F97316',
                        orangeLight: '#FDBA74',

                        success: '#22C55E',
                        successDark: '#15803D',

                        danger: '#EF4444',
                        dangerDark: '#DC2626',

                        warning: '#F59E0B',
                        info: '#38BDF8',
                    },

                    boxShadow: {
                        blueGlow: '0 8px 26px rgba(59, 130, 246, 0.20)',
                        orangeBorderGlow: '0 0 0 3px rgba(249, 115, 22, 0.14)',
                        darkCard: '0 12px 30px rgba(0, 0, 0, 0.28)',
                    }
                }
            }
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            background:
                radial-gradient(circle at 100% 0%, rgba(249, 115, 22, 0.07), transparent 28%),
                radial-gradient(circle at 0% 100%, rgba(59, 130, 246, 0.08), transparent 30%),
                #0B1120;
        }

        .app-card {
            background: rgba(23, 32, 51, 0.94);
            border: 1px solid #334155;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.28);
            transition: all 0.22s ease;
        }

        .app-card:hover {
            background: #1E293B;
            border-color: #F97316;
            transform: translateY(-3px);
            box-shadow: 0 14px 34px rgba(249, 115, 22, 0.10);
        }

        .btn-primary {
            background: #3B82F6;
            color: #FFFFFF;
            border: 1px solid #60A5FA;
            box-shadow: 0 8px 26px rgba(59, 130, 246, 0.20);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #2563EB;
            border-color: #93C5FD;
            transform: translateY(-1px);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.30);
        }

        .btn-orange-outline {
            background: transparent;
            color: #FDBA74;
            border: 1px solid #F97316;
            transition: all 0.2s ease;
        }

        .btn-orange-outline:hover {
            background: rgba(249, 115, 22, 0.10);
            color: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.14);
        }

        .btn-success {
            background: #22C55E;
            color: #FFFFFF;
            border: 1px solid #4ADE80;
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.18);
            transition: all 0.2s ease;
        }

        .btn-success:hover {
            background: #15803D;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #EF4444;
            color: #FFFFFF;
            border: 1px solid #F87171;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.18);
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            background: #DC2626;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: #CBD5E1;
            border: 1px solid #475569;
            transition: all 0.2s ease;
        }

        .btn-outline:hover {
            color: #FFFFFF;
            background: #1E293B;
            border-color: #F97316;
        }

        .input-dark {
            width: 100%;
            background: #0F172A;
            color: #F8FAFC;
            border: 1px solid #334155;
            transition: all 0.2s ease;
        }

        .input-dark::placeholder {
            color: #94A3B8;
        }

        .input-dark:focus {
            outline: none;
            border-color: #F97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.14);
        }

        .table-dark {
            width: 100%;
            border-collapse: collapse;
        }

        .table-dark thead {
            background: #0F172A;
            color: #CBD5E1;
            border-bottom: 1px solid #F97316;
        }

        .table-dark tbody tr {
            border-top: 1px solid #334155;
            transition: background 0.18s ease;
        }

        .table-dark tbody tr:hover {
            background: rgba(30, 41, 59, 0.82);
        }

        .table-dark th,
        .table-dark td {
            padding: 0.95rem 1rem;
        }

        .page-container {
            max-width: 80rem;
            margin-left: auto;
            margin-right: auto;
            padding: 1.5rem 1rem;
        }

        @media (min-width: 640px) {
            .page-container {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans antialiased text-textPrimary selection:bg-orangeAccent selection:text-white">

    <!-- Header -->
    <header class="w-full py-3 px-4 sm:px-6 border-b border-borderCol bg-surface/95 backdrop-blur-md sticky top-0 z-50 shadow-lg shadow-black/20">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-3">

            <a href="{{ route('landing') }}" class="flex items-center gap-3 min-w-0">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="College Logo"
                    class="w-11 h-11 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-orangeAccent bg-white shrink-0"
                >

                <div class="min-w-0">
                    <span class="font-bold text-sm sm:text-base text-textPrimary tracking-tight block leading-tight truncate">
                        Matoshri Pratishthan's
                    </span>

                    <span class="text-[10px] sm:text-xs text-orangeLight font-semibold tracking-wide uppercase block leading-tight">
                        Vishwabharti Polytechnic Institute
                    </span>
                </div>
            </a>

            <div class="hidden sm:flex items-center gap-2 bg-card px-3 py-1.5 rounded-xl border border-borderCol">
                <span class="w-2.5 h-2.5 rounded-full bg-success animate-pulse"></span>
                <span class="text-xs font-semibold text-textSecondary tracking-wide">
                    AMS 2026
                </span>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="w-full py-5 sm:py-6 text-center text-xs text-textMuted border-t border-borderCol bg-surface/95">
        <p>
            &copy; {{ date('Y') }}
            Matoshri Pratishthan's Vishwabharti Polytechnic Institute.
            All rights reserved.
        </p>
    </footer>

</body>
</html>