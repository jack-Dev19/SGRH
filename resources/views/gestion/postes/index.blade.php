@extends('layouts.dashboard')

@section('title', 'Postes')

@section('content')

    <div class="max-w-7xl mx-auto">

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- En-tête --}}
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Postes</h1>
                <p class="text-sm text-slate-500">{{ $postes->total() }} poste(s) enregistré(s) dans votre organisation.</p>
            </div>

            <a href="{{ route('gestion.postes.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouveau poste
            </a>
        </div>

        {{-- BARRE DE RECHERCHE + FILTRES --}}
        <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
            <form method="GET" action="{{ route('gestion.postes.index') }}" class="flex flex-wrap items-center gap-3">

                {{-- Recherche --}}
                <div class="flex-1 min-w-[280px]">
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher un poste..."
                            class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    </div>
                </div>

                {{-- Filtre Département (dropdown custom) --}}
                <div class="relative min-w-[220px]">
                    <input type="hidden" name="departement_id" id="input-departement"
                        value="{{ request('departement_id') }}">

                    <button type="button" id="dropdown-departement-btn"
                        class="w-full inline-flex items-center justify-between gap-2 pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium hover:border-purple-300 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span id="dropdown-departement-label" class="truncate text-left">
                            @php $deptSelectionne = $departements->firstWhere('id', request('departement_id')); @endphp
                            {{ $deptSelectionne ? $deptSelectionne->nom : 'Tous les départements' }}
                        </span>
                        <svg id="dropdown-departement-chevron" xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-slate-400 transition-transform duration-300 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdown-departement-menu"
                        class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 min-w-[240px] max-h-64 overflow-y-auto">
                        <button type="button" onclick="selectDropdown('departement', '', 'Tous les départements')"
                            class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <span class="font-medium">Tous les départements</span>
                        </button>
                        @foreach($departements as $dept)
                            <button type="button" onclick="selectDropdown('departement', '{{ $dept->id }}', '{{ $dept->nom }}')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3 border-t border-slate-100">
                                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="font-medium">{{ $dept->nom }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Filtre Statut (dropdown custom) --}}
                <div class="relative min-w-[190px]">
                    <input type="hidden" name="statut" id="input-statut" value="{{ request('statut') }}">

                    <button type="button" id="dropdown-statut-btn"
                        class="w-full inline-flex items-center justify-between gap-2 pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 font-medium hover:border-purple-300 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span id="dropdown-statut-label" class="truncate text-left">
                            @if(request('statut') === 'actif') Actif
                            @elseif(request('statut') === 'inactif') Inactif
                            @else Tous les statuts
                            @endif
                        </span>
                        <svg id="dropdown-statut-chevron" xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-slate-400 transition-transform duration-300 shrink-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdown-statut-menu"
                        class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 min-w-[200px]">
                        <button type="button" onclick="selectDropdown('statut', '', 'Tous les statuts')"
                            class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <span class="font-medium">Tous les statuts</span>
                        </button>
                        <button type="button" onclick="selectDropdown('statut', 'actif', 'Actif')"
                            class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3 border-t border-slate-100">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            </div>
                            <span class="font-medium">Actif</span>
                        </button>
                        <button type="button" onclick="selectDropdown('statut', 'inactif', 'Inactif')"
                            class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-slate-100 transition flex items-center gap-3 border-t border-slate-100">
                            <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            </div>
                            <span class="font-medium">Inactif</span>
                        </button>
                    </div>
                </div>

                {{-- Bouton Filtrer --}}
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filtrer
                </button>

                @if(request()->hasAny(['search', 'departement_id', 'statut']))
                    <a href="{{ route('gestion.postes.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div>

        {{-- Compteur --}}
        <p class="text-xs text-slate-500 mb-4 px-1">
            <span class="font-bold text-slate-700">{{ $postes->total() }}</span> poste(s) trouvé(s)
        </p>

        {{-- GRILLE DE CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

            @forelse($postes as $poste)
                @php
                    // Palette de couleurs variées (identique à Employés)
                    $palette = [
                        ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'gradient' => 'from-blue-500 to-blue-600', 'dot' => 'bg-blue-500'],
                        ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'gradient' => 'from-violet-500 to-violet-600', 'dot' => 'bg-violet-500'],
                        ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'gradient' => 'from-red-500 to-red-600', 'dot' => 'bg-red-500'],
                        ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'gradient' => 'from-emerald-500 to-emerald-600', 'dot' => 'bg-emerald-500'],
                        ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'gradient' => 'from-orange-500 to-orange-600', 'dot' => 'bg-orange-500'],
                        ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'gradient' => 'from-slate-600 to-slate-700', 'dot' => 'bg-slate-500'],
                    ];

                    // ✅ CORRECTION : abs() pour éviter les index négatifs (bug crc32)
                    $idx = abs(crc32((string) $poste->id)) % count($palette);
                    $c = $palette[$idx] ?? $palette[0];

                    // Icône différente selon le poste (initiales du nom)
                    $initiales = strtoupper(mb_substr($poste->nom ?? 'P', 0, 2));
                @endphp

                <div
                    class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-purple-200 hover:-translate-y-1 transition-all duration-300">

                    {{-- Bandeau gradient --}}
                    <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">
                        <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-6 -left-6 w-20 h-20 bg-white/10 rounded-full"></div>

                        {{-- Badge statut --}}
                        <div class="absolute top-3 right-3">
                            @if($poste->statut === 'actif')
                                <span
                                    class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    <span class="w-1 h-1 rounded-full bg-emerald-300 animate-pulse"></span>
                                    Actif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center bg-white/25 backdrop-blur-sm border border-white/30 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                    Inactif
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="relative px-4 pb-4">

                        {{-- Cercle avatar avec initiales (chevauchement) --}}
                        <div class="flex justify-center -mt-10 mb-3">
                            <div class="w-20 h-20 rounded-full bg-white p-1 shadow-lg ring-1 ring-slate-100">
                                <div
                                    class="w-full h-full rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-xl">
                                    {{ $initiales }}
                                </div>
                            </div>
                        </div>

                        {{-- Nom du poste --}}
                        <div class="text-center mb-3">
                            <h3 class="font-bold text-slate-900 text-sm leading-tight mb-1 truncate">{{ $poste->nom }}</h3>
                            <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">POSTE</p>
                        </div>

                        {{-- Département en pill --}}
                        <div class="flex justify-center mb-4">
                            <span
                                class="inline-flex items-center gap-1.5 text-[10px] font-semibold {{ $c['bg'] }} {{ $c['text'] }} px-3 py-1 rounded-full truncate max-w-full">
                                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }} shrink-0"></span>
                                {{ $poste->departement?->nom ?? 'Sans département' }}
                            </span>
                        </div>

                        {{-- Informations détaillées --}}
                        <div class="space-y-2 mb-4 pb-4 border-b border-slate-100">

                            {{-- Niveau --}}
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] text-slate-600 truncate">
                                    Niveau : <span class="font-semibold text-slate-700">{{ $poste->niveau ?? '—' }}</span>
                                </span>
                            </div>

                            {{-- Employés --}}
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] text-slate-600 truncate">
                                    <span class="font-semibold text-slate-700">{{ $poste->employes_count }}</span> employé(s)
                                </span>
                            </div>

                            {{-- Salaire --}}
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] text-slate-600 truncate">
                                    @if($poste->salaire_min || $poste->salaire_max)
                                        <span class="font-semibold text-slate-700">
                                            {{ $poste->salaire_min ? number_format($poste->salaire_min, 0, ',', ' ') : '—' }}
                                            @if($poste->salaire_max && $poste->salaire_max != $poste->salaire_min)
                                                - {{ number_format($poste->salaire_max, 0, ',', ' ') }}
                                            @endif
                                        </span>
                                        <span class="text-slate-400">MGA</span>
                                    @else
                                        <span class="italic text-slate-400">Salaire non défini</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('gestion.postes.show', $poste) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Détails
                            </a>
                            <a href="{{ route('gestion.postes.edit', $poste) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Modifier
                            </a>
                            <form action="{{ route('gestion.postes.destroy', $poste) }}" method="POST" data-delete-poste
                                data-poste-nom="{{ $poste->nom }}" data-employes="{{ $poste->employes_count }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Supprimer"
                                    class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <p class="text-slate-500 font-medium mb-1">Aucun poste trouvé</p>
                    <p class="text-slate-400 text-sm mb-4">Commencez par créer un nouveau poste.</p>
                    <a href="{{ route('gestion.postes.create') }}"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouveau poste
                    </a>
                </div>
            @endforelse
        </div>

        @if($postes->hasPages())
            <div class="mt-6">{{ $postes->links() }}</div>
        @endif
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ═══════════════════════════════════════════════════════════════
                // ⭐ Confirmation suppression poste (avec SweetAlert2)
                // ═══════════════════════════════════════════════════════════════
                document.querySelectorAll('form[data-delete-poste]').forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();

                        const nomPoste = form.dataset.posteNom || 'ce poste';
                        const employes = parseInt(form.dataset.employes || 0);

                        // ⭐ CAS 1 : Poste utilisé → avertir de la désactivation
                        if (employes > 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Poste utilisé',
                                html: `
                                        <div style="text-align: left; font-size: 13px; line-height: 1.6;">
                                            <p style="margin-bottom: 10px;">
                                                Le poste <strong>${nomPoste}</strong> est utilisé par
                                                <strong style="color: #f59e0b;">${employes} employé(s)</strong>.
                                            </p>
                                            <p style="color: #475569;">
                                                Le poste sera <strong>désactivé</strong> au lieu d'être supprimé
                                                pour préserver l'historique. Continuer ?
                                            </p>
                                        </div>
                                    `,
                                showCancelButton: true,
                                confirmButtonColor: '#f59e0b',
                                cancelButtonColor: '#64748b',
                                confirmButtonText: 'Oui, désactiver',
                                cancelButtonText: 'Annuler',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                            return;
                        }

                        // ⭐ CAS 2 : Poste non utilisé → confirmation simple
                        Swal.fire({
                            icon: 'warning',
                            title: 'Confirmer la suppression',
                            html: `Voulez-vous vraiment supprimer le poste <strong>${nomPoste}</strong> ?<br><br>
                                       <span style="color: #dc2626; font-size: 12px;">⚠️ Cette action est irréversible.</span>`,
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Oui, supprimer',
                            cancelButtonText: 'Annuler',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection

@push('scripts')
    <script>
        window.selectDropdown = function (type, value, label) {
            document.getElementById('input-' + type).value = value;
            document.getElementById('dropdown-' + type + '-label').textContent = label;
            document.getElementById('dropdown-' + type + '-menu').classList.add('hidden');
            document.getElementById('dropdown-' + type + '-chevron').style.transform = 'rotate(0deg)';
        };

        window.toggleDropdown = function (type) {
            const menu = document.getElementById('dropdown-' + type + '-menu');
            const chevron = document.getElementById('dropdown-' + type + '-chevron');
            const isHidden = menu.classList.contains('hidden');

            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');

            if (isHidden) {
                menu.classList.remove('hidden');
                chevron.style.transform = 'rotate(180deg)';
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const deptBtn = document.getElementById('dropdown-departement-btn');
            if (deptBtn) {
                deptBtn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('departement'); });
            }
            const statutBtn = document.getElementById('dropdown-statut-btn');
            if (statutBtn) {
                statutBtn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('statut'); });
            }
            document.addEventListener('click', () => {
                document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
                document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
            });
            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(menu => {
                menu.addEventListener('click', e => e.stopPropagation());
            });
        });
    </script>
@endpush