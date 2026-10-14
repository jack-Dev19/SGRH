<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SGRH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
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

    {{-- Scripts spécifiques aux pages --}}
    @stack('scripts')

</body>
</html>