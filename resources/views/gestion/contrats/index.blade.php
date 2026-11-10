@extends('layouts.dashboard')

@section('title', 'Gestion des contrats')

@section('content')

@php
    $stats = $stats ?? [
        'total'  => 0,
        'cdi'    => 0,
        'cdd'    => 0,
        'actifs' => 0,
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="{
    typeOpen: false,
    statutOpen: false,
    selectedType: '{{ request('type') }}',
    selectedStatut: '{{ request('statut') }}',
    typesList: [
        { id: '', nom: 'Tous les types' },
        { id: 'CDI', nom: 'CDI' },
        { id: 'CDD', nom: 'CDD' },
        { id: 'Stage', nom: 'Stage' },
        { id: 'Intérim', nom: 'Intérim' },
        { id: 'Freelance', nom: 'Freelance' }
    ],
    statutsList: [
        { id: '', nom: 'Tous les statuts' },
        { id: 'actif', nom: 'Actif' },
        { id: 'expire', nom: 'Expiré' },
        { id: 'resilie', nom: 'Résilié' }
    ]
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-purple-700 transition-colors">Tableau de bord</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold">Contrats</span>
    </div>

    {{-- Messages Flash --}}
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
                    Contrats
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Gestion des contrats</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gérez tous les contrats de travail : CDI, CDD, Stage, Intérim, Freelance.
            </p>
        </div>

        <a href="{{ route('gestion.contrats.create') }}"
            class="group inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-900/20 transition-all hover:bg-slate-800 hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau contrat
        </a>
    </div>

    {{-- Statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        {{-- Total --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- CDI --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">CDI</p>
                    <p class="text-2xl font-extrabold text-emerald-700 mt-0.5">{{ $stats['cdi'] }}</p>
                </div>
            </div>
        </div>

        {{-- CDD --}}
        <div class="rounded-2xl border border-orange-200 bg-orange-50/50 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider">CDD</p>
                    <p class="text-2xl font-extrabold text-orange-700 mt-0.5">{{ $stats['cdd'] }}</p>
                </div>
            </div>
        </div>

        {{-- Actifs --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Actifs</p>
                    <p class="text-2xl font-extrabold text-blue-700 mt-0.5">{{ $stats['actifs'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filtres --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-3 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.contrats.index') }}" class="flex flex-wrap items-center gap-2">
            
            <input type="hidden" name="type" x-model="selectedType">
            <input type="hidden" name="statut" x-model="selectedStatut">

            {{-- Recherche --}}
            <div class="flex-1 min-w-[280px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par numéro ou nom d'employé…"
                        class="w-full pl-11 pr-4 py-2.5 rounded-lg border-0 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 ring-1 ring-inset ring-slate-200 focus:bg-white focus:ring-2 focus:ring-inset focus:ring-slate-900 transition">
                </div>
            </div>

            {{-- ⭐ FILTRE TYPE DROPDOWN PRO ⭐ --}}
            <div class="min-w-[200px] relative" @click.away="typeOpen = false">
                <button type="button" @click="typeOpen = !typeOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="typesList.find(t => t.id === selectedType)?.nom || 'Tous les types'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="typeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="typeOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
                    <div class="bg-slate-50 px-4 py-2.5 border-b border-slate-100"><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Filtrer par type</p></div>
                    <ul class="max-h-60 overflow-y-auto p-1.5 space-y-1 custom-scrollbar">
                        <template x-for="t in typesList" :key="t.id">
                            <li @click="selectedType = t.id; typeOpen = false" class="group flex items-center justify-between px-3 py-2 rounded-xl cursor-pointer transition-all duration-150" :class="selectedType === t.id ? 'bg-slate-900 text-white' : 'hover:bg-slate-100 text-slate-700'">
                                <span class="text-sm font-medium" x-text="t.nom"></span>
                                <div :class="selectedType === t.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-slate-200'" class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                                    <svg x-show="selectedType === t.id" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- ⭐ FILTRE STATUT DROPDOWN PRO ⭐ --}}
            <div class="min-w-[200px] relative" @click.away="statutOpen = false">
                <button type="button" @click="statutOpen = !statutOpen" 
                    class="relative w-full pl-4 pr-10 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 text-left text-sm rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-slate-900 transition-all hover:bg-slate-100">
                    <span x-text="statutsList.find(s => s.id === selectedStatut)?.nom || 'Tous les statuts'" class="text-slate-700 font-medium"></span>
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="statutOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </button>
                <div x-show="statutOpen" x-transition class="absolute z-40 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_40px_-15px_rgba(147,51,234,0.4)] ring-1 ring-slate-200 overflow-hidden" style="display: none;">
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

            @if(request()->hasAny(['search', 'type', 'statut']))
                <a href="{{ route('gestion.contrats.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-3 py-2.5 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Liste des contrats --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        @if($contrats->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Numéro</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Employé</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Période</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider hidden lg:table-cell">Salaire</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Statut</th>
                            <th class="text-right px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($contrats as $contrat)
                            <tr class="group hover:bg-slate-50/80 transition-colors">
                                {{-- Numéro --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('gestion.contrats.show', $contrat) }}" class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-mono font-bold text-purple-700 ring-1 ring-inset ring-purple-200 hover:bg-purple-100 transition">
                                        {{ $contrat->numero }}
                                    </a>
                                </td>

                                {{-- Employé --}}
                                <td class="px-6 py-4">
                                    @if($contrat->employe)
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                                {{ $contrat->employe->initiales }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-900 truncate">{{ $contrat->employe->nom_complet }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">{{ $contrat->employe->matricule }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">—</span>
                                    @endif
                                </td>

                                {{-- Type --}}
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'CDI'       => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                            'CDD'       => 'bg-orange-50 text-orange-700 ring-orange-200',
                                            'Stage'     => 'bg-blue-50 text-blue-700 ring-blue-200',
                                            'Intérim'   => 'bg-purple-50 text-purple-700 ring-purple-200',
                                            'Freelance' => 'bg-pink-50 text-pink-700 ring-pink-200',
                                        ];
                                        $badgeClass = $typeColors[$contrat->type] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $badgeClass }}">
                                        {{ $contrat->type }}
                                    </span>
                                </td>

                                {{-- Période --}}
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <p class="text-xs font-medium text-slate-700">{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">→ {{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}</p>
                                </td>

                                {{-- Salaire --}}
                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <span class="font-bold text-slate-800">{{ number_format($contrat->salaire, 0, ',', ' ') }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase ml-1">{{ $contrat->devise }}</span>
                                </td>

                                {{-- Statut --}}
                                <td class="px-6 py-4">
                                    @if($contrat->statut === 'actif')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Actif
                                        </span>
                                    @elseif($contrat->statut === 'expire')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-yellow-700 shadow-sm ring-1 ring-inset ring-yellow-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                            Expiré
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-red-700 shadow-sm ring-1 ring-inset ring-red-200">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Résilié
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('gestion.contrats.show', $contrat) }}" title="Voir" class="p-2 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('gestion.contrats.download', $contrat->id) }}" title="Télécharger le PDF" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <a href="{{ route('gestion.contrats.edit', $contrat) }}" title="Modifier" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="{{ route('gestion.contrats.destroy', $contrat) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce contrat ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($contrats->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $contrats->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Aucun contrat trouvé</h3>
                <p class="text-sm text-slate-500 mb-5">
                    @if(request()->hasAny(['search', 'type', 'statut']))
                        Aucun résultat ne correspond à vos filtres. Essayez d'autres critères.
                    @else
                        Commencez par créer votre premier contrat.
                    @endif
                </p>
                <div class="flex items-center justify-center gap-2">
                    @if(request()->hasAny(['search', 'type', 'statut']))
                        <a href="{{ route('gestion.contrats.index') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-lg transition">
                            Effacer les filtres
                        </a>
                    @else
                        <a href="{{ route('gestion.contrats.create') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold px-5 py-2.5 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Créer un contrat
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
</style>

@endsection