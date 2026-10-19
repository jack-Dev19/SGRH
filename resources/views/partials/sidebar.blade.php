@php
    $roleMapping = [
        'Administrateur'      => 'admin',
        'Responsable RH'      => 'rh',
        'Chef de département' => 'chef',
        'Employé'             => 'employe',
        'Recruteur'           => 'recruteur',
    ];

    $roleNom      = Auth::user()->role->nom ?? null;
    $role         = $roleMapping[$roleNom] ?? 'invite';
    $currentRoute = request()->route()->getName();
    $isAdminOrRh  = in_array($role, ['admin', 'rh']);
@endphp

{{-- Bouton hamburger (mobile) --}}
<button id="sidebar-toggle"
    aria-label="Ouvrir le menu"
    class="lg:hidden fixed top-4 left-4 z-50 bg-gradient-to-br from-purple-600 to-pink-600 text-white p-2 rounded-xl shadow-lg shadow-purple-600/40">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

{{-- Overlay mobile --}}
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"></div>

{{-- Sidebar --}}
<aside id="sidebar"
    class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-40 w-64 lg:h-screen bg-gradient-to-b from-purple-700 via-purple-600 to-pink-600 text-white flex flex-col shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 sidebar-scroll">

    {{-- Logo --}}
    <div class="h-16 flex items-center gap-3 px-5 border-b border-white/15 shrink-0">
        <div class="bg-white/20 backdrop-blur-sm p-2 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <span class="font-bold text-base tracking-tight">DIGITAL AGENCY</span>
    </div>

    {{-- Carte profil --}}
    <div class="px-4 py-4 border-b border-white/15 shrink-0">
        <div class="flex items-center gap-3">
            <div class="relative">
                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-sm border border-white/30">
                    {{ strtoupper(substr(Auth::user()->name ?? 'NA', 0, 2)) }}
                </div>
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-purple-600 rounded-full"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name ?? 'Utilisateur' }}</p>
                <p class="text-xs text-purple-100 truncate">{{ $roleNom ?? 'Sans rôle' }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-3 px-3 sidebar-nav">

        {{-- ============================================= --}}
        {{-- ADMIN ou RH --}}
        {{-- ============================================= --}}
        @if($isAdminOrRh)

            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Principal</p>

            <a href="{{ $role === 'admin' ? route('admin.dashboard') : route('rh.dashboard') }}"
               class="sidebar-link {{ in_array($currentRoute, ['admin.dashboard', 'rh.dashboard']) ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Tableau de bord</span>
            </a>

            {{-- PERSONNEL --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Personnel</p>

            <a href="{{ route('gestion.employes.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.employes.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Employés</span>
            </a>

            <a href="{{ route('gestion.users.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.users.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Utilisateurs</span>
            </a>

            <a href="{{ route('gestion.departements.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.departements.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Départements</span>
            </a>

            {{-- ⚠️ Lien "Postes" RETIRÉ — géré depuis les Départements --}}

            <a href="{{ route('gestion.contrats.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.contrats.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Contrats</span>
            </a>

            <a href="{{ route('gestion.documents.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.documents.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <span>Documents</span>
            </a>

            {{-- RECRUTEMENT --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Recrutement</p>

            <a href="{{ route('gestion.offres.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.offres.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Offres d'emploi</span>
                <span class="ml-auto text-[9px] font-bold bg-white/25 px-1.5 py-0.5 rounded uppercase tracking-wider">Public</span>
            </a>

            {{-- POINTAGE --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Pointage</p>

            <a href="{{ route('gestion.pointage.scanner') }}"
               class="sidebar-link {{ request()->routeIs('gestion.pointage.scanner') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span>Scanner QR</span>
                <span class="ml-auto text-[9px] font-bold bg-white/25 px-1.5 py-0.5 rounded uppercase tracking-wider">NEW</span>
            </a>

            <a href="{{ route('gestion.pointage.historique') }}"
               class="sidebar-link {{ request()->routeIs('gestion.pointage.historique') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Historique</span>
            </a>

            <a href="{{ route('gestion.pointage.anomalies') }}"
               class="sidebar-link {{ request()->routeIs('gestion.pointage.anomalies') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Anomalies</span>
            </a>

            {{-- OPÉRATIONS --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Opérations</p>

            <a href="{{ route('gestion.absences.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.absences.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <span>Absences</span>
            </a>

            <a href="{{ route('gestion.conges.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.conges.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Congés</span>
            </a>

            <a href="{{ route('gestion.presences.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.presences.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Présences</span>
            </a>

            <a href="{{ route('gestion.salaires.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.salaires.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Salaires</span>
            </a>

            <a href="{{ route('gestion.evaluations.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.evaluations.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span>Évaluations</span>
            </a>
        @endif

        {{-- CHEF DE DÉPARTEMENT --}}
        @if($role === 'chef')
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Chef de Département</p>

            <a href="{{ route('chef.dashboard') }}"
               class="sidebar-link {{ $currentRoute === 'chef.dashboard' ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('chef.equipe.index') }}"
               class="sidebar-link {{ request()->routeIs('chef.equipe.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Mon équipe</span>
            </a>

            <a href="{{ route('chef.conges.index') }}"
               class="sidebar-link {{ request()->routeIs('chef.conges.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Valider les congés</span>
            </a>

            <a href="{{ route('chef.presences.index') }}"
               class="sidebar-link {{ request()->routeIs('chef.presences.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Présences</span>
            </a>
        @endif

        {{-- EMPLOYÉ --}}
        @if($role === 'employe')
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Mon Espace</p>

            <a href="{{ route('employe.dashboard') }}"
               class="sidebar-link {{ $currentRoute === 'employe.dashboard' ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('employe.profil') }}"
               class="sidebar-link {{ $currentRoute === 'employe.profil' ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Mon profil</span>
            </a>

            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Ma Vie Pro</p>

            <a href="{{ route('employe.conges.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.conges.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Mes congés</span>
            </a>

            <a href="{{ route('employe.presences.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.presences.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mes présences</span>
            </a>

            <a href="{{ route('employe.paie.index') }}"
               class="sidebar-link {{ request()->routeIs('employe.paie.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mes fiches de paie</span>
            </a>

            <a href="{{ route('employe.conges.create') }}"
               class="mt-4 flex items-center gap-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20 rounded-xl px-3 py-2.5 transition-all duration-200 hover:scale-[1.02] group">
                <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center shrink-0 group-hover:rotate-90 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-white">Demander un congé</span>
            </a>
        @endif

        {{-- RECRUTEUR --}}
        @if($role === 'recruteur')
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Recrutement</p>

            <a href="{{ route('recruteur.dashboard') }}"
               class="sidebar-link {{ $currentRoute === 'recruteur.dashboard' ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span>Tableau de bord</span>
            </a>

            <a href="{{ route('gestion.offres.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.offres.*') ? 'active' : '' }}">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span>Offres d'emploi</span>
            </a>
        @endif

        {{-- DÉCONNEXION --}}
        <div class="mt-4 pt-4 border-t border-white/15">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-100 hover:text-white hover:bg-red-500/30">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>

    </nav>

</aside>

<style>
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.85);
        transition: all 0.2s ease;
        margin-bottom: 0.125rem;
    }

    .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }

    .sidebar-link.active {
        background-color: rgba(255, 255, 255, 0.25);
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
    }

    .sidebar-nav,
    .sidebar-scroll {
        scrollbar-width: none;
    }

    .sidebar-nav::-webkit-scrollbar,
    .sidebar-scroll::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle  = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (toggle && sidebar && overlay) {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });

            sidebar.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>