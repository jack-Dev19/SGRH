<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SGRH</title>
    
    {{-- Vite : CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Chart.js (chargé une seule fois pour tout le dashboard) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- ============================================= --}}
        {{-- SIDEBAR                                       --}}
        {{-- ============================================= --}}
        @include('partials.sidebar')

        {{-- ============================================= --}}
        {{-- ZONE PRINCIPALE                               --}}
        {{-- ============================================= --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- HEADER --}}
            @include('partials.dashboard-header')

            {{-- CONTENU SCROLLABLE --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @yield('content')
            </main>

        </div>

    </div>

    {{-- Scripts spécifiques aux pages (graphiques, modales, etc.) --}}
    @stack('scripts')
</body>
</html>