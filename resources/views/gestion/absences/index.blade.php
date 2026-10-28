@extends('layouts.dashboard')

@section('title', 'Absences')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    origineOpen: false,
    selectedOrigine: '{{ request('origine') }}',
    originesList: [
        { id: '', nom: 'Toutes origines' },
        { id: 'auto', nom: '🤖 Automatique' },
        { id: 'manuelle', nom: '✏️ Manuelle' }
    ]
}">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 border border-red-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Suivi
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Absences</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $absences->total() }} absence(s) enregistrée(s)</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            {{-- Bouton Détecter --}}
            <form action="{{ route('gestion.absences.detecter') }}" method="POST" class="flex-1 md:flex-initial">
                @csrf
                <button type="submit" onclick="return confirm('Lancer la détection automatique des absences pour aujourd\'hui ?\n\nCela vérifiera tous les employés n\'ayant pas pointé.');"
                    class="w-full inline-flex items-center justify-center gap-2 bg-orange-50 hover:bg-orange-100 text-orange-700 text-sm font-bold px-5 py-3 rounded-xl transition-colors ring-1 ring-inset ring-orange-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Détecter
                </button>
            </form>

            {{-- Bouton Nouvelle absence --}}
            <a href="{{ route('gestion.absences.create') }}"
                class="group flex-1 md:flex-initial inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nouvelle absence
            </a>
        </div>
    </div>

    {{-- Onglets stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-8">
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
                'slate'   => ['active' => 'bg-slate-900 text-white border-slate-900',     'inactive' => 'bg-white text-slate-700 border-slate-200 hover:border-slate-400',   'dot' => 'bg-slate-500'],
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
                    <p class="text-[10px] font-bold uppercase tracking-wider {{ $isActive ? 'text-white' : 'text-slate-500' }}">{{ $tab['label'] }}</p>
                </div>
                <p class="text-2xl font-extrabold">{{ $tab['count'] }}</p>
            </a>
        @endforeach
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.absences.index') }}" class="flex flex-wrap items-center gap-2">

            @if(request('statut'))
                <input type="hidden" name="statut" value="{{ request('statut') }}">
            @endif

            <input type="hidden" name="origine" x-model="selectedOrigine">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[220px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un employé…"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- ⭐ FILTRE ORIGINE DROPDOWN PRO ⭐ --}}
            <div class="min-w-[180px] relative" @click.away="origineOpen = false">
                <button type="button" @click="origineOpen = !origineOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="originesList.find(o => o.id === selectedOrigine)?.nom || 'Toutes origines'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="origineOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="origineOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par origine</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <template x-for="o in originesList" :key="o.id">
                            <li @click="selectedOrigine = o.id; origineOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedOrigine === o.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="o.nom"></span>
                                <div :class="selectedOrigine === o.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedOrigine === o.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Date début --}}
            <div class="min-w-[160px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- Date fin --}}
            <div class="min-w-[160px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- Bouton Filtrer --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'origine', 'date_debut', 'date_fin']))
                <a href="{{ route('gestion.absences.index', request('statut') ? ['statut' => request('statut')] : []) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Grille de cartes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($absences as $absence)
            @php
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

                $statuts = [
                    'en_attente'   => ['label' => 'En attente',    'dot' => 'bg-yellow-400',  'badge' => 'bg-yellow-50 text-yellow-700 ring-yellow-200'],
                    'justifie'     => ['label' => 'Justifiée',     'dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
                    'non_justifie' => ['label' => 'Non justifiée', 'dot' => 'bg-red-400',     'badge' => 'bg-red-50 text-red-700 ring-red-200'],
                    'autorise'     => ['label' => 'Autorisée',     'dot' => 'bg-blue-400',    'badge' => 'bg-blue-50 text-blue-700 ring-blue-200'],
                    'annule'       => ['label' => 'Annulée',       'dot' => 'bg-slate-400',   'badge' => 'bg-slate-100 text-slate-600 ring-slate-200'],
                ];
                $s = $statuts[$absence->statut] ?? ['label' => $absence->statut, 'dot' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-600 ring-slate-200'];
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
                                <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Auto
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                                <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Manuel
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
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
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $absence->employe?->nom_complet ?? 'Employé inconnu' }}</p>
                            <p class="text-[10px] text-slate-400 truncate font-mono">{{ $absence->employe?->matricule }}</p>
                        </div>
                    </div>

                    {{-- Motif --}}
                    <div class="mb-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Motif</p>
                        <p class="text-xs font-semibold {{ $c['text'] }} leading-tight line-clamp-2 h-8">{{ $absence->motif ?? 'Aucun motif' }}</p>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5 text-center">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Durée</p>
                            <p class="text-lg font-extrabold {{ $c['text'] }} leading-none">{{ (int) $absence->nombre_jours }}<span class="text-[10px] font-normal text-slate-500 ml-1">j</span></p>
                        </div>
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date début</p>
                            <p class="text-[10px] font-bold {{ $c['text'] }} leading-tight">{{ $absence->date_debut?->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    {{-- Justificatif --}}
                    @if($absence->justificatif || $absence->justificatif_path)
                        <div class="mb-3">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-full ring-1 ring-inset ring-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Justificatif fourni
                            </span>
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-auto">

                        @if($absence->statut === 'en_attente')
                            <div class="flex items-center gap-1.5 mb-2">
                                <form action="{{ route('gestion.absences.approuver', $absence) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Approuver cette absence ?');"
                                        class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 py-2 rounded-lg transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Approuver
                                    </button>
                                </form>
                                <form action="{{ route('gestion.absences.refuser', $absence) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Refuser cette absence ?');"
                                        class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-bold text-white bg-red-600 hover:bg-red-700 py-2 rounded-lg transition shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Refuser
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('gestion.absences.show', $absence) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Voir
                            </a>
                            <a href="{{ route('gestion.absences.edit', $absence) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold {{ $c['text'] }} {{ $c['bg'] }} hover:brightness-95 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Modifier
                            </a>
                            <form action="{{ route('gestion.absences.destroy', $absence) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette absence ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition ring-1 ring-inset ring-red-200" title="Supprimer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Aucune absence trouvée</h3>
                <p class="mt-1 text-sm text-slate-500">
                    @if(request()->hasAny(['search', 'origine', 'date_debut', 'date_fin']))
                        Aucun résultat ne correspond à vos filtres. Essayez d'autres critères.
                    @else
                        Toutes les absences apparaîtront ici, qu'elles soient manuelles ou détectées automatiquement.
                    @endif
                </p>
                <div class="mt-5">
                    @if(request()->hasAny(['search', 'origine', 'date_debut', 'date_fin']))
                        <a href="{{ route('gestion.absences.index', request('statut') ? ['statut' => request('statut')] : []) }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                            Effacer les filtres
                        </a>
                    @else
                        <a href="{{ route('gestion.absences.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Créer une absence
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($absences->hasPages())
        <div class="mt-6">{{ $absences->links() }}</div>
    @endif
</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>

@endsection