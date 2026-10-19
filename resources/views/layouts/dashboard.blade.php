<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SGRH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ⭐ SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html { scroll-behavior: smooth; }

        /* Personnalisation SweetAlert2 pour matcher le design SGRH */
        .swal2-popup {
            border-radius: 1.5rem !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            padding: 2rem 1.5rem !important;
        }
        .swal2-title {
            color: #1e293b !important;
            font-weight: 800 !important;
            font-size: 1.25rem !important;
        }
        .swal2-html-container {
            color: #475569 !important;
            font-size: 0.875rem !important;
            line-height: 1.6 !important;
        }
        .swal2-confirm {
            border-radius: 0.75rem !important;
            font-weight: 700 !important;
            padding: 0.625rem 1.5rem !important;
        }
        .swal2-close {
            color: #94a3b8 !important;
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-slate-50">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('partials.sidebar')

        {{-- ZONE PRINCIPALE --}}
        <div class="flex-1 flex flex-col min-w-0">
            <main class="flex-1 p-4 md:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>

    </div>

    {{-- ⭐ Flash messages (SweetAlert2) --}}
    @include('components.flash-messages')

    {{-- Scripts spécifiques aux pages --}}
    @stack('scripts')

</body>
</html>