{{-- ============================================= --}}
{{-- HEADER DU DASHBOARD (glassmorphism + dégradé) --}}
{{-- ============================================= --}}
@php
    $nombreNotificationsNonLues = Auth::user()
        ->notifications()
        ->nonLues()
        ->count();
@endphp
<header class="sticky top-0 h-16 bg-white/70 backdrop-blur-xl border-b border-purple-200/40 flex items-center justify-between px-4 md:px-6 shrink-0 z-20">

    {{-- Bandeau dégradé subtil en haut --}}
    <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500"></div>

    {{-- Gauche : Titre --}}
    <div class="flex items-center gap-3 min-w-0">
        <h1 class="text-lg font-semibold text-slate-800 truncate">
            @yield('page-title', 'Tableau de bord')
        </h1>
    </div>

    {{-- Droite : Actions --}}
    <div class="flex items-center gap-2 md:gap-3">

        {{-- Barre de recherche --}}
        <div class="hidden md:flex items-center gap-2 bg-purple-50/80 border border-purple-100 rounded-full px-4 py-2 w-64 focus-within:ring-2 focus-within:ring-purple-400 transition">
            {{-- Icône : Loupe --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4 text-purple-500 shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>

            <input type="text"
                   placeholder="Recherche rapide..."
                   class="bg-transparent text-sm outline-none w-full text-slate-700 placeholder-slate-400">
        </div>

        {{-- Notifications --}}
        <a href="{{ route('notifications.index') }}"
           aria-label="Voir les notifications"
           title="Notifications"
           class="relative p-2 text-slate-600 hover:text-purple-600 hover:bg-purple-100/70 rounded-full transition">

            {{-- Icône : Cloche --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>

            {{-- Badge dynamique des notifications non lues --}}
            @if ($nombreNotificationsNonLues > 0)
                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-gradient-to-br from-pink-500 to-red-500 text-white text-[10px] font-bold rounded-full ring-2 ring-white flex items-center justify-center shadow-sm">
                    {{ $nombreNotificationsNonLues > 99 ? '99+' : $nombreNotificationsNonLues }}
                </span>
            @endif
        </a>

        {{-- Séparateur --}}
        <div class="hidden sm:block w-px h-6 bg-purple-200"></div>

        {{-- Profil utilisateur déplacé de la sidebar --}}
        <div class="flex items-center gap-2 md:gap-3 pl-1">

            {{-- Avatar + statut --}}
            <div class="relative shrink-0">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs md:text-sm shadow-lg shadow-purple-500/30 ring-2 ring-purple-100">
                    {{ strtoupper(substr(Auth::user()->name ?? 'NA', 0, 2)) }}
                </div>

                {{-- Indicateur de présence --}}
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 md:w-3 md:h-3 bg-emerald-400 border-2 border-white rounded-full"></span>
            </div>

            {{-- Nom et rôle --}}
            <div class="hidden sm:block min-w-0 max-w-[150px] md:max-w-[190px]">
                <p class="text-xs md:text-sm font-semibold text-slate-800 truncate">
                    {{ Auth::user()->name ?? 'Utilisateur' }}
                </p>

                <p class="text-[9px] md:text-[10px] text-purple-600 truncate uppercase tracking-wider font-medium">
                    {{ Auth::user()->role->nom ?? 'Sans rôle' }}
                </p>
            </div>

        </div>

    </div>

</header>