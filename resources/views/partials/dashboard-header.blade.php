{{-- ============================================= --}}
{{-- HEADER DU DASHBOARD (glassmorphism + dégradé)  --}}
{{-- ============================================= --}}
<header class="h-16 bg-white/70 backdrop-blur-xl border-b border-purple-200/40 flex items-center justify-between px-4 md:px-6 shrink-0 relative z-10">

    {{-- Bandeau dégradé subtil en haut --}}
    <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500"></div>

    {{-- Gauche : Titre --}}
    <div class="flex items-center gap-3">
        <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Tableau de bord')</h1>
    </div>

    {{-- Droite : Actions --}}
    <div class="flex items-center gap-3">

        {{-- Barre de recherche --}}
        <div class="hidden md:flex items-center gap-2 bg-purple-50/80 border border-purple-100 rounded-full px-4 py-2 w-64 focus-within:ring-2 focus-within:ring-purple-400 transition">
            {{-- Icône : Loupe --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Recherche rapide..." class="bg-transparent text-sm outline-none w-full text-slate-700 placeholder-slate-400">
        </div>

        {{-- Notifications --}}
        <button class="relative p-2 text-slate-600 hover:text-purple-600 hover:bg-purple-100/70 rounded-full transition">
            {{-- Icône : Cloche --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            {{-- Pastille rouge --}}
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-gradient-to-br from-pink-500 to-red-500 rounded-full ring-2 ring-white"></span>
        </button>

        {{-- Séparateur --}}
        <div class="hidden md:block w-px h-6 bg-purple-200"></div>

        {{-- Profil utilisateur --}}
        <div class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xs shadow-md shadow-purple-600/30">
                {{ strtoupper(substr(Auth::user()->nom, 0, 2)) }}
            </div>
        </div>

    </div>

</header>