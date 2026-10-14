@php
    /**
     * Mapping des rôles du seeder (noms complets) vers des clés courtes.
     */
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

    // Admin et RH partagent le même menu de gestion
    $isAdminOrRh = in_array($role, ['admin', 'rh']);
@endphp

{{-- ============================================= --}}
{{-- BOUTON HAMBURGER (mobile) --}}
{{-- ============================================= --}}
<button id="sidebar-toggle"
    class="lg:hidden fixed top-4 left-4 z-50 bg-gradient-to-br from-purple-600 to-pink-600 text-white p-2 rounded-xl shadow-lg shadow-purple-600/40">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

{{-- Overlay mobile --}}
<div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"></div>

{{-- ============================================= --}}
{{-- SIDEBAR --}}
{{-- ============================================= --}}
<aside id="sidebar"
    class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-40 w-64 lg:h-screen bg-gradient-to-b from-purple-700 via-purple-600 to-pink-600 text-white flex flex-col shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 sidebar-scroll">

    {{-- Logo --}}
    <div class="h-16 flex items-center gap-3 px-5 border-b border-white/15 shrink-0">
        <div class="bg-white/20 backdrop-blur-sm p-2 rounded-xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
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
        {{-- ADMIN ou RH : menu commun --}}
        {{-- ============================================= --}}
        @if($isAdminOrRh)

            {{-- Tableau de bord --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Principal</p>

            <a href="{{ $role === 'admin' ? route('admin.dashboard') : route('rh.dashboard') }}"
               class="sidebar-link {{ in_array($currentRoute, ['admin.dashboard', 'rh.dashboard']) ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Tableau de bord
            </a>

            {{-- Personnel --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Personnel</p>

            {{-- Employés --}}
            <a href="{{ route('gestion.employes.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.employes.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Employés
            </a>

            {{-- Départements --}}
            <a href="{{ route('gestion.departements.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.departements.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Départements
            </a>

            {{-- Postes --}}
            <a href="{{ route('gestion.postes.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.postes.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Postes
            </a>

            {{-- Contrats --}}
            <a href="{{ route('gestion.contrats.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.contrats.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Contrats
            </a>

            {{-- Opérations --}}
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Opérations</p>

            {{-- Congés --}}
            <a href="{{ route('gestion.conges.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.conges.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Congés
            </a>

            {{-- Présences --}}
            <a href="{{ route('gestion.presences.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.presences.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Présences
            </a>

            {{-- Salaires --}}
            <a href="{{ route('gestion.salaires.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.salaires.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Salaires
            </a>

            {{-- Évaluations --}}
            <a href="{{ route('gestion.evaluations.index') }}"
               class="sidebar-link {{ request()->routeIs('gestion.evaluations.*') ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
                Évaluations
            </a>

            {{-- Paramètres — Admin UNIQUEMENT --}}
            @if($role === 'admin')
                <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 mt-4 font-semibold opacity-70">Système</p>

                <a href="{{ route('admin.parametres.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.parametres.*') ? 'active' : '' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Paramètres
                </a>
            @endif
        @endif

        {{-- ============================================= --}}
        {{-- CHEF DE DÉPARTEMENT --}}
        {{-- ============================================= --}}
        @if($role === 'chef')
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Chef de Département</p>

            <a href="{{ route('chef.dashboard') }}"
               class="sidebar-link {{ $currentRoute === 'chef.dashboard' ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Tableau de bord
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Mon équipe
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Valider les congés
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Présences
            </a>
        @endif

        {{-- ============================================= --}}
        {{-- EMPLOYÉ --}}
        {{-- ============================================= --}}
        @if($role === 'employe')
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Mon Espace</p>

            <a href="{{ route('employe.dashboard') }}"
               class="sidebar-link {{ $currentRoute === 'employe.dashboard' ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Tableau de bord
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Mon profil
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Mes congés
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Mes fiches de paie
            </a>
        @endif

        {{-- ============================================= --}}
        {{-- RECRUTEUR --}}
        {{-- ============================================= --}}
        @if($role === 'recruteur')
            <p class="text-[10px] uppercase tracking-widest text-purple-200 px-3 py-2 font-semibold opacity-70">Recrutement</p>

            <a href="{{ route('recruteur.dashboard') }}"
               class="sidebar-link {{ $currentRoute === 'recruteur.dashboard' ? 'active' : '' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Tableau de bord
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Offres d'emploi
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Candidatures
            </a>

            <a href="#" class="sidebar-link">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Entretiens
            </a>
        @endif

        {{-- Déconnexion --}}
        <div class="mt-4 pt-4 border-t border-white/15">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-100 hover:text-white hover:bg-red-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>

    </nav>

</aside>

{{-- ============================================= --}}
{{-- STYLES --}}
{{-- ============================================= --}}
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
        }
    });
</script>