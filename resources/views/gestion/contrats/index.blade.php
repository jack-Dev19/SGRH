@extends('layouts.dashboard')

@section('title', 'Contrats')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- ============================================= --}}
    {{-- MESSAGES FLASH                                --}}
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
    {{-- EN-TÊTE                                       --}}
    {{-- ============================================= --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Contrats</h1>
            <p class="text-sm text-slate-500">{{ $contrats->total() }} contrat(s) enregistré(s)</p>
        </div>

        <a href="{{ route('gestion.contrats.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau contrat
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- CARTES STATS                                  --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total</p>
            <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">CDI</p>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['cdi'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">CDD</p>
            <p class="text-2xl font-extrabold text-orange-600">{{ $stats['cdd'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm hover:shadow-md transition">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Actifs</p>
            <p class="text-2xl font-extrabold text-purple-600">{{ $stats['actifs'] }}</p>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- BARRE DE RECHERCHE + FILTRES                  --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.contrats.index') }}" class="flex flex-wrap items-center gap-3">

            <div class="flex-1 min-w-[240px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par numéro ou employé..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                </div>
            </div>

            <div class="min-w-[140px]">
                <select name="type"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    <option value="">Tous les types</option>
                    @foreach(['CDI', 'CDD', 'Stage', 'Intérim', 'Freelance'] as $t)
                        <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[140px]">
                <select name="statut"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    <option value="">Tous les statuts</option>
                    <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="expire" {{ request('statut') === 'expire' ? 'selected' : '' }}>Expiré</option>
                    <option value="resilie" {{ request('statut') === 'resilie' ? 'selected' : '' }}>Résilié</option>
                </select>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'type', 'statut']))
                <a href="{{ route('gestion.contrats.index') }}"
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
    {{-- GRILLE DE CARDS                                --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($contrats as $contrat)
            @php
                // Palette par type de contrat
                $palette = [
                    'CDI'       => ['gradient' => 'from-emerald-500 to-teal-600',  'bg' => 'bg-emerald-50',  'text' => 'text-emerald-700',  'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'CDD'       => ['gradient' => 'from-orange-500 to-amber-600',  'bg' => 'bg-orange-50',   'text' => 'text-orange-700',   'border' => 'border-orange-200',  'dot' => 'bg-orange-500'],
                    'Stage'     => ['gradient' => 'from-blue-500 to-indigo-600',   'bg' => 'bg-blue-50',     'text' => 'text-blue-700',     'border' => 'border-blue-200',    'dot' => 'bg-blue-500'],
                    'Intérim'   => ['gradient' => 'from-purple-500 to-violet-600', 'bg' => 'bg-purple-50',   'text' => 'text-purple-700',   'border' => 'border-purple-200',  'dot' => 'bg-purple-500'],
                    'Freelance' => ['gradient' => 'from-pink-500 to-rose-600',     'bg' => 'bg-pink-50',     'text' => 'text-pink-700',     'border' => 'border-pink-200',    'dot' => 'bg-pink-500'],
                ];
                $c = $palette[$contrat->type] ?? ['gradient' => 'from-slate-500 to-slate-600', 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'border' => 'border-slate-200', 'dot' => 'bg-slate-500'];

                // Couleur du badge type
                $typeColors = [
                    'CDI'       => 'bg-emerald-100 text-emerald-700',
                    'CDD'       => 'bg-orange-100 text-orange-700',
                    'Stage'     => 'bg-blue-100 text-blue-700',
                    'Intérim'   => 'bg-purple-100 text-purple-700',
                    'Freelance' => 'bg-pink-100 text-pink-700',
                ];
                $typeColor = $typeColors[$contrat->type] ?? 'bg-slate-100 text-slate-700';

                // Statut
                $statuts = [
                    'actif'   => ['label' => 'Actif',   'dot' => 'bg-emerald-300'],
                    'expire'  => ['label' => 'Expiré',  'dot' => 'bg-yellow-300'],
                    'resilie' => ['label' => 'Résilié', 'dot' => 'bg-red-300'],
                ];
                $s = $statuts[$contrat->statut] ?? ['label' => $contrat->statut, 'dot' => 'bg-slate-300'];
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-purple-200 hover:-translate-y-1 transition-all duration-300">

                {{-- ============================================= --}}
                {{-- BANDEAU AVEC ICÔNE INTÉGRÉE                    --}}
                {{-- ============================================= --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['gradient'] }} overflow-hidden">

                    {{-- Décorations --}}
                    <div class="absolute -top-8 -right-8 w-24 h-24 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-10 -left-10 w-28 h-28 bg-white/10 rounded-full"></div>

                    {{-- Badge statut (haut droite) --}}
                    <div class="absolute top-3 right-3 z-10">
                        <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            <span class="relative flex h-1.5 w-1.5">
                                @if($contrat->statut === 'actif')
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $s['dot'] }} opacity-75"></span>
                                @endif
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $s['dot'] }}"></span>
                            </span>
                            {{ $s['label'] }}
                        </span>
                    </div>

                    {{-- Icône + Type (bas gauche) --}}
                    <div class="absolute bottom-3 left-4 flex items-center gap-3">

                        {{-- Icône --}}
                        <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-sm border-2 border-white/40 flex items-center justify-center group-hover:scale-110 group-hover:rotate-3 transition duration-300 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        {{-- Type --}}
                        <div>
                            <p class="text-white/70 text-[9px] uppercase tracking-widest font-bold">Type</p>
                            <p class="text-white font-bold text-lg leading-none">{{ $contrat->type }}</p>
                        </div>
                    </div>

                </div>

                {{-- ============================================= --}}
                {{-- CONTENU                                        --}}
                {{-- ============================================= --}}
                <div class="p-5">

                    {{-- Numéro + Badge type --}}
                    <div class="flex items-center justify-between gap-2 mb-4 pb-4 border-b border-slate-100">
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Numéro</p>
                            <h3 class="font-mono font-bold text-slate-900 text-sm leading-tight truncate">
                                {{ $contrat->numero }}
                            </h3>
                        </div>
                        <span class="inline-block text-[10px] font-bold px-2.5 py-1 rounded-lg {{ $typeColor }} shrink-0">
                            {{ $contrat->type }}
                        </span>
                    </div>

                    {{-- Employé --}}
                    <div class="flex items-center gap-3 p-3 {{ $c['bg'] }} rounded-xl mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $c['gradient'] }} flex items-center justify-center text-white font-bold text-xs shrink-0">
                            {{ $contrat->employe?->initiales ?? '??' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 truncate">
                                {{ $contrat->employe?->nom_complet ?? 'Employé inconnu' }}
                            </p>
                            <p class="text-[10px] text-slate-500 truncate font-mono">
                                {{ $contrat->employe?->matricule }}
                            </p>
                        </div>
                    </div>

                    {{-- Période + Salaire --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">

                        {{-- Période --}}
                        <div class="{{ $c['border'] }} border rounded-xl p-2.5">
                            <div class="flex items-center gap-1 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $c['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Période</p>
                            </div>
                            <p class="text-[10px] font-bold {{ $c['text'] }} leading-tight">
                                {{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}
                            </p>
                            <p class="text-[10px] text-slate-500 leading-tight">
                                @if($contrat->date_fin)
                                    → {{ $contrat->date_fin->format('d/m/Y') }}
                                @else
                                    <span class="italic">Indéterminée</span>
                                @endif
                            </p>
                        </div>

                        {{-- Salaire --}}
                        <div class="{{ $c['border'] }} border rounded-xl p-2.5">
                            <div class="flex items-center gap-1 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $c['text'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">Salaire</p>
                            </div>
                            <p class="text-sm font-extrabold {{ $c['text'] }} leading-tight truncate">
                                {{ number_format($contrat->salaire, 0, ',', ' ') }}
                            </p>
                            <p class="text-[9px] text-slate-500">{{ $contrat->devise }}</p>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('gestion.contrats.show', $contrat) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Voir
                        </a>
                        <a href="{{ route('gestion.contrats.edit', $contrat) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                        <form action="{{ route('gestion.contrats.destroy', $contrat) }}" method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce contrat ?');" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-red-700 bg-red-50 hover:bg-red-100 py-2 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Suppr.
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-500 font-medium mb-1">Aucun contrat trouvé</p>
                <p class="text-slate-400 text-sm mb-4">Commencez par créer un contrat.</p>
                <a href="{{ route('gestion.contrats.create') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau contrat
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $contrats->links() }}
    </div>
</div>

@endsection