@extends('layouts.dashboard')

@section('title', 'Salaires')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    statutOpen: false,
    selectedStatut: '{{ request('statut') }}',
    statutsList: [
        { id: '', nom: 'Tous les statuts' },
        { id: 'actif', nom: 'Actif' },
        { id: 'inactif', nom: 'Inactif' }
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
                    Rémunération
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Salaires</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $salaires->total() }} salaire(s) enregistré(s)</p>
        </div>

        <a href="{{ route('gestion.salaires.create') }}"
            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau salaire
        </a>
    </div>

    {{-- Cartes stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Actifs</p>
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700">{{ $stats['actifs'] }}</p>
        </div>

        <div class="rounded-2xl border border-purple-200 bg-purple-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">Masse salariale</p>
                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                </div>
            </div>
            <p class="text-lg font-extrabold text-purple-700 truncate">{{ number_format($stats['masse_salariale'], 0, ',', ' ') }}</p>
            <p class="text-[9px] text-purple-500 font-semibold">MGA / mois</p>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Salaire moyen</p>
                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
            </div>
            <p class="text-lg font-extrabold text-blue-700 truncate">{{ number_format($stats['salaire_moyen'], 0, ',', ' ') }}</p>
            <p class="text-[9px] text-blue-500 font-semibold">MGA / mois</p>
        </div>
    </div>

    {{-- Barre de recherche + filtres --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.salaires.index') }}" class="flex flex-wrap items-center gap-2">
            
            <input type="hidden" name="statut" x-model="selectedStatut">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[240px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un employé…"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- ⭐ FILTRE STATUT DROPDOWN PRO ⭐ --}}
            <div class="min-w-[180px] relative" @click.away="statutOpen = false">
                <button type="button" @click="statutOpen = !statutOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="statutsList.find(s => s.id === selectedStatut)?.nom || 'Tous les statuts'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="statutOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="statutOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par statut</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <template x-for="s in statutsList" :key="s.id">
                            <li @click="selectedStatut = s.id; statutOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedStatut === s.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="s.nom"></span>
                                <div :class="selectedStatut === s.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedStatut === s.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Bouton Filtrer --}}
            <button type="submit"
                class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'statut']))
                <a href="{{ route('gestion.salaires.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Grille de cartes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($salaires as $salaire)
            @php
                $palette = [
                    ['from' => 'from-blue-500',    'to' => 'to-blue-600',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-100'],
                    ['from' => 'from-violet-500',  'to' => 'to-violet-600',  'bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'border' => 'border-violet-100'],
                    ['from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100'],
                    ['from' => 'from-orange-500',  'to' => 'to-orange-600',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'border' => 'border-orange-100'],
                    ['from' => 'from-pink-500',    'to' => 'to-pink-600',    'bg' => 'bg-pink-50',    'text' => 'text-pink-700',    'border' => 'border-pink-100'],
                    ['from' => 'from-cyan-500',    'to' => 'to-cyan-600',    'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'border' => 'border-cyan-100'],
                ];
                $idx = crc32($salaire->id) % count($palette);
                $c = $palette[$idx];
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">

                {{-- Bandeau --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }}">
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-full"></div>

                    {{-- Badge statut --}}
                    <div class="absolute top-3 right-3 z-10">
                        @if($salaire->statut === 'actif')
                            <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-300"></span>
                                </span>
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                Inactif
                            </span>
                        @endif
                    </div>

                    {{-- Montant en gros --}}
                    <div class="absolute bottom-3 left-5">
                        <p class="text-white/80 text-[10px] font-semibold uppercase tracking-wider">Salaire de base</p>
                        <p class="text-white text-lg font-extrabold leading-none">
                            {{ number_format($salaire->salaire_base, 0, ',', ' ') }}
                            <span class="text-xs font-normal">MGA</span>
                        </p>
                    </div>
                </div>

                {{-- Icône à cheval --}}
                <div class="relative">
                    <div class="absolute -top-7 right-5">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-md ring-4 ring-white flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                            {{ $salaire->employe?->initiales ?? '??' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $salaire->employe?->nom_complet ?? 'Employé inconnu' }}</p>
                            <p class="text-[10px] text-slate-400 truncate font-mono">{{ $salaire->employe?->matricule }}</p>
                        </div>
                    </div>

                    {{-- Période --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Depuis</p>
                            <p class="text-[11px] font-bold {{ $c['text'] }} leading-tight">{{ $salaire->date_debut?->format('d/m/Y') }}</p>
                        </div>
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jusqu'à</p>
                            <p class="text-[11px] font-bold {{ $c['text'] }} leading-tight">{{ $salaire->date_fin?->format('d/m/Y') ?? 'Indéterminé' }}</p>
                        </div>
                    </div>

                    {{-- Motif --}}
                    @if($salaire->motif)
                        <p class="text-xs text-slate-500 mb-4 line-clamp-2 min-h-[2rem]">{{ $salaire->motif }}</p>
                    @endif

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 mt-auto">
                        <a href="{{ route('gestion.salaires.show', $salaire) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Voir
                        </a>
                        <a href="{{ route('gestion.salaires.edit', $salaire) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold {{ $c['text'] }} {{ $c['bg'] }} hover:brightness-95 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Modifier
                        </a>
                        <form action="{{ route('gestion.salaires.destroy', $salaire) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce salaire ?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition ring-1 ring-inset ring-red-200" title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Aucun salaire trouvé</h3>
                <p class="mt-1 text-sm text-slate-500">
                    @if(request()->hasAny(['search', 'statut']))
                        Aucun résultat ne correspond à vos filtres. Essayez d'autres critères.
                    @else
                        Commencez par créer un salaire.
                    @endif
                </p>
                <div class="mt-5">
                    @if(request()->hasAny(['search', 'statut']))
                        <a href="{{ route('gestion.salaires.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200 transition">
                            Effacer les filtres
                        </a>
                    @else
                        <a href="{{ route('gestion.salaires.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-bold text-white hover:bg-slate-800 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Nouveau salaire
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($salaires->hasPages())
        <div class="mt-6">{{ $salaires->links() }}</div>
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