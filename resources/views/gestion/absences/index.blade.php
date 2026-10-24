@extends('layouts.dashboard')

@section('title', 'Absences')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- ============================================= --}}
    {{-- MESSAGES FLASH --}}
    {{-- ============================================= --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ============================================= --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Absences</h1>
            <p class="text-sm text-slate-500">{{ $absences->total() }} absence(s) enregistrée(s)</p>
        </div>

        <div class="flex flex-wrap gap-2">
            {{-- Bouton Détecter (auto) --}}
            <form action="{{ route('gestion.absences.detecter') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    onclick="return confirm('Lancer la détection automatique des absences pour aujourd\'hui ?\n\nCela vérifiera tous les employés n\'ayant pas pointé.');"
                    class="inline-flex items-center gap-2 bg-orange-100 hover:bg-orange-200 text-orange-700 text-sm font-bold px-5 py-3 rounded-full transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Détecter
                </button>
            </form>

            {{-- Bouton Nouvelle absence --}}
            <a href="{{ route('gestion.absences.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle absence
            </a>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- ONGLETS STATS --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
        @php
            $tabs = [
                ['key' => '',              'label' => 'Toutes',        'count' => $stats['total'],         'color' => 'slate'],
                ['key' => 'en_attente',    'label' => 'En attente',    'count' => $stats['en_attente'],    'color' => 'yellow'],
                ['key' => 'justifie',      'label' => 'Justifiées',    'count' => $stats['justifie'],      'color' => 'emerald'],
                ['key' => 'non_justifie',  'label' => 'Non justifiées','count' => $stats['non_justifie'],  'color' => 'red'],
                ['key' => 'autorise',      'label' => 'Autorisées',    'count' => $stats['autorise'],      'color' => 'blue'],
            ];
            $currentStatut = request('statut', '');

            $colors = [
                'slate'   => ['active' => 'bg-slate-800 text-white border-slate-800',     'inactive' => 'bg-white text-slate-700 border-slate-200 hover:border-slate-400',   'dot' => 'bg-slate-500'],
                'yellow'  => ['active' => 'bg-yellow-500 text-white border-yellow-500',   'inactive' => 'bg-white text-yellow-700 border-slate-200 hover:border-yellow-300', 'dot' => 'bg-yellow-500'],
                'emerald' => ['active' => 'bg-emerald-600 text-white border-emerald-600', 'inactive' => 'bg-white text-emerald-700 border-slate-200 hover:border-emerald-300', 'dot' => 'bg-emerald-500'],
                'red'     => ['active' => 'bg-red-600 text-white border-red-600',         'inactive' => 'bg-white text-red-700 border-slate-200 hover:border-red-300',       'dot' => 'bg-red-500'],
                'blue'    => ['active' => 'bg-blue-600 text-white border-blue-600',       'inactive' => 'bg-white text-blue-700 border-slate-200 hover:border-blue-300',     'dot' => 'bg-blue-500'],
            ];
        @endphp

        @foreach($tabs as $tab)
            @php
                $isActive = $currentStatut === $tab['key'];
                $cc = $colors[$tab['color']];
            @endphp

            <a href="{{ route('gestion.absences.index', $tab['key'] ? ['statut' => $tab['key']] : []) }}"
               class="rounded-2xl border-2 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md {{ $isActive ? $cc['active'] : $cc['inactive'] }}">
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-white' : $cc['dot'] }}"></span>
                    <p class="text-[10px] font-bold uppercase tracking-wider {{ $isActive ? 'text-white' : 'text-slate-500' }}">
                        {{ $tab['label'] }}
                    </p>
                </div>
                <p class="text-2xl font-extrabold">{{ $tab['count'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- ============================================= --}}
    {{-- FILTRES --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.absences.index') }}" class="flex flex-wrap items-center gap-3">

            {{-- Préserver le statut si défini --}}
            @if(request('statut'))
                <input type="hidden" name="statut" value="{{ request('statut') }}">
            @endif

            {{-- Recherche --}}
            <div class="flex-1 min-w-[220px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un employé..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition">
                </div>
            </div>

            {{-- Filtre origine --}}
            <div class="min-w-[160px]">
                <select name="origine"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none">
                    <option value="">Toutes origines</option>
                    <option value="auto" {{ request('origine') === 'auto' ? 'selected' : '' }}>🤖 Automatique</option>
                    <option value="manuelle" {{ request('origine') === 'manuelle' ? 'selected' : '' }}>✏️ Manuelle</option>
                </select>
            </div>

            {{-- Filtre date début --}}
            <div class="min-w-[160px]">
                <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none">
            </div>

            {{-- Filtre date fin --}}
            <div class="min-w-[160px]">
                <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none">
            </div>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'origine', 'date_debut', 'date_fin']))
                <a href="{{ route('gestion.absences.index', request('statut') ? ['statut' => request('statut')] : []) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- ============================================= --}}
    {{-- GRILLE DE CARTES --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($absences as $absence)
            @php
                // Palette variée
                $palette = [
                    ['from' => 'from-blue-500',    'to' => 'to-blue-600',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-100'],
                    ['from' => 'from-violet-500',  'to' => 'to-violet-600',  'bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'border' => 'border-violet-100'],
                    ['from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100'],
                    ['from' => 'from-orange-500',  'to' => 'to-orange-600',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'border' => 'border-orange-100'],
                    ['from' => 'from-pink-500',    'to' => 'to-pink-600',    'bg' => 'bg-pink-50',    'text' => 'text-pink-700',    'border' => 'border-pink-100'],
                    ['from' => 'from-cyan-500',    'to' => 'to-cyan-600',    'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'border' => 'border-cyan-100'],
                ];
                $idx = crc32($absence->id) % count($palette);
                $c = $palette[$idx];

                // Statuts
                $statuts = [
                    'en_attente'   => ['label' => 'En attente',    'dot' => 'bg-yellow-400',  'badge' => 'bg-yellow-100 text-yellow-700 border-yellow-200'],
                    'justifie'     => ['label' => 'Justifiée',     'dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                    'non_justifie' => ['label' => 'Non justifiée', 'dot' => 'bg-red-400',     'badge' => 'bg-red-100 text-red-700 border-red-200'],
                    'autorise'     => ['label' => 'Autorisée',     'dot' => 'bg-blue-400',    'badge' => 'bg-blue-100 text-blue-700 border-blue-200'],
                    'annule'       => ['label' => 'Annulée',       'dot' => 'bg-slate-400',   'badge' => 'bg-slate-100 text-slate-600 border-slate-200'],
                ];
                $s = $statuts[$absence->statut] ?? ['label' => $absence->statut, 'dot' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-600 border-slate-200'];
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">

                {{-- Bandeau gradient --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }}">
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-full"></div>

                    {{-- Badge statut --}}
                    <div class="absolute top-3 right-3 z-10">
                        <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            <span class="relative flex h-1.5 w-1.5">
                                @if($absence->statut === 'en_attente')
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $s['dot'] }} opacity-75"></span>
                                @endif
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $s['dot'] }}"></span>
                            </span>
                            {{ $s['label'] }}
                        </span>
                    </div>

                    {{-- Badge origine --}}
                    <div class="absolute top-3 left-3 z-10">
                        @if($absence->origine === 'auto')
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                🤖 Auto
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                ✏️ Manuelle
                            </span>
                        @endif
                    </div>

                    {{-- Dates en gros --}}
                    <div class="absolute bottom-3 left-5">
                        <p class="text-white/80 text-[10px] font-semibold uppercase tracking-wider">Période</p>
                        <p class="text-white text-sm font-extrabold leading-none">
                            {{ $absence->date_debut?->format('d/m') }}
                            @if($absence->date_debut?->format('Y-m-d') !== $absence->date_fin?->format('Y-m-d'))
                                → {{ $absence->date_fin?->format('d/m') }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Icône à cheval --}}
                <div class="relative">
                    <div class="absolute -top-7 right-5">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-md ring-4 ring-white flex items-center justify-center transform group-hover:scale-105 transition-transform">
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="pt-10 px-5 pb-5 flex-1 flex flex-col">

                    {{-- Employé --}}
                    <div class="flex items-center gap-2.5 mb-4 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} flex items-center justify-center text-white font-bold text-[11px] shrink-0">
                            {{ $absence->employe?->initiales ?? '??' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ $absence->employe?->nom_complet ?? 'Employé inconnu' }}
                            </p>
                            <p class="text-[10px] text-slate-400 truncate font-mono">
                                {{ $absence->employe?->matricule }}
                            </p>
                        </div>
                    </div>

                    {{-- Motif + Durée --}}
                    <div class="mb-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Motif</p>
                        <p class="text-xs font-semibold {{ $c['text'] }} leading-tight line-clamp-2 h-8">
                            {{ $absence->motif ?? 'Aucun motif' }}
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5 text-center">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Durée</p>
                            <p class="text-lg font-extrabold {{ $c['text'] }} leading-none">
                                {{ (int) $absence->nombre_jours }}
                                <span class="text-[10px] font-normal text-slate-500">j</span>
                            </p>
                        </div>
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date début</p>
                            <p class="text-[10px] font-bold {{ $c['text'] }} leading-tight">
                                {{ $absence->date_debut?->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Justificatif --}}
                    @if($absence->justificatif || $absence->justificatif_path)
                        <div class="mb-3">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Justificatif fourni
                            </span>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-auto">

                        {{-- Boutons validation (si en attente) --}}
                        @if($absence->statut === 'en_attente')
                            <div class="flex items-center gap-1.5 mb-2">
                                <form action="{{ route('gestion.absences.approuver', $absence) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Approuver cette absence ?');"
                                        class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 py-2 rounded-lg transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approuver
                                    </button>
                                </form>
                                <form action="{{ route('gestion.absences.refuser', $absence) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Refuser cette absence ?');"
                                        class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-bold text-white bg-red-600 hover:bg-red-700 py-2 rounded-lg transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Actions secondaires --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('gestion.absences.show', $absence) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir
                            </a>
                            <a href="{{ route('gestion.absences.edit', $absence) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold {{ $c['text'] }} {{ $c['bg'] }} hover:brightness-95 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Modifier
                            </a>
                            <form action="{{ route('gestion.absences.destroy', $absence) }}" method="POST"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette absence ?');"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition"
                                    title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            {{-- Aucune absence --}}
            <div class="col-span-full bg-white rounded-2xl border-2 border-dashed border-slate-200 p-16 text-center">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-medium mb-1">Aucune absence trouvée</p>
                <p class="text-slate-400 text-sm mb-6">
                    Toutes les absences apparaîtront ici, qu'elles soient manuelles ou détectées automatiquement.
                </p>
                <a href="{{ route('gestion.absences.create') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer une absence
                </a>
            </div>
        @endforelse
    </div>

    {{-- ============================================= --}}
    {{-- PAGINATION --}}
    {{-- ============================================= --}}
    <div class="mt-6">
        {{ $absences->links() }}
    </div>

</div>

@endsection