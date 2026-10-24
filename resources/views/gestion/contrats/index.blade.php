@extends('layouts.dashboard')

@section('title', 'Gestion des contrats')

@section('content')

@php
    /* ============================================================
     |  SÉCURISATION : valeurs par défaut si $stats n'est pas passé
     | ============================================================ */
    $stats = $stats ?? [
        'total'  => 0,
        'cdi'    => 0,
        'cdd'    => 0,
        'actifs' => 0,
    ];
@endphp

<div class="max-w-7xl mx-auto">

    {{-- ============================================= --}}
    {{-- FIL D'ARIANE --}}
    {{-- ============================================= --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition">Tableau de bord</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">Contrats</span>
    </div>

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
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Gestion des contrats</h1>
            <p class="text-sm text-slate-500 mt-1">
                Gérez tous les contrats de travail : CDI, CDD, Stage, Intérim, Freelance.
            </p>
        </div>

        <a href="{{ route('gestion.contrats.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau contrat
        </a>
    </div>

    {{-- ============================================= --}}
    {{-- STATISTIQUES --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- CDI --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">CDI</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['cdi'] }}</p>
                </div>
            </div>
        </div>

        {{-- CDD --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">CDD</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['cdd'] }}</p>
                </div>
            </div>
        </div>

        {{-- Actifs --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Actifs</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['actifs'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- FILTRES & RECHERCHE --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.contrats.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">

            {{-- Recherche --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Rechercher</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Numéro ou nom d'employé…"
                        class="w-full pl-9 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                </div>
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Type</label>
                <select name="type" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    <option value="">Tous les types</option>
                    @foreach(['CDI', 'CDD', 'Stage', 'Intérim', 'Freelance'] as $t)
                        <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Statut --}}
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Statut</label>
                <select name="statut" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    <option value="">Tous les statuts</option>
                    <option value="actif"   {{ request('statut') === 'actif'   ? 'selected' : '' }}>Actif</option>
                    <option value="expire"  {{ request('statut') === 'expire'  ? 'selected' : '' }}>Expiré</option>
                    <option value="resilie" {{ request('statut') === 'resilie' ? 'selected' : '' }}>Résilié</option>
                </select>
            </div>

            {{-- Boutons --}}
            <div class="md:col-span-4 flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-slate-900 hover:bg-black text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filtrer
                </button>

                @if(request()->hasAny(['search', 'type', 'statut']))
                    <a href="{{ route('gestion.contrats.index') }}"
                        class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ============================================= --}}
    {{-- LISTE DES CONTRATS --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">

        @if($contrats->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Numéro</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Employé</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Période</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Salaire</th>
                            <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Statut</th>
                            <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($contrats as $contrat)
                            <tr class="hover:bg-slate-50 transition">
                                {{-- Numéro --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('gestion.contrats.show', $contrat) }}"
                                        class="font-mono font-bold text-purple-600 hover:text-purple-700">
                                        {{ $contrat->numero }}
                                    </a>
                                </td>

                                {{-- Employé --}}
                                <td class="px-6 py-4">
                                    @if($contrat->employe)
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                                {{ $contrat->employe->initiales }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $contrat->employe->nom_complet }}</p>
                                                <p class="text-xs text-slate-400">{{ $contrat->employe->matricule }}</p>
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
                                            'CDI'       => 'bg-emerald-100 text-emerald-700',
                                            'CDD'       => 'bg-orange-100 text-orange-700',
                                            'Stage'     => 'bg-blue-100 text-blue-700',
                                            'Intérim'   => 'bg-purple-100 text-purple-700',
                                            'Freelance' => 'bg-pink-100 text-pink-700',
                                        ];
                                        $badgeClass = $typeColors[$contrat->type] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $badgeClass }}">
                                        {{ $contrat->type }}
                                    </span>
                                </td>

                                {{-- Période --}}
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    <p>{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</p>
                                    <p class="text-slate-400">
                                        → {{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}
                                    </p>
                                </td>

                                {{-- Salaire --}}
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ number_format($contrat->salaire, 0, ',', ' ') }}
                                    <span class="text-xs text-slate-400">{{ $contrat->devise }}</span>
                                </td>

                                {{-- Statut --}}
                                <td class="px-6 py-4">
                                    @if($contrat->statut === 'actif')
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                                    @elseif($contrat->statut === 'expire')
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700">Expiré</span>
                                    @else
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-red-100 text-red-700">Résilié</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        {{-- Voir --}}
                                        <a href="{{ route('gestion.contrats.show', $contrat) }}"
                                            title="Voir"
                                            class="p-2 rounded-lg text-slate-400 hover:text-purple-600 hover:bg-purple-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        {{-- Télécharger PDF --}}
                                        <a href="{{ route('gestion.contrats.download', $contrat->id) }}"
                                            title="Télécharger le PDF"
                                            class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>

                                        {{-- Modifier --}}
                                        <a href="{{ route('gestion.contrats.edit', $contrat) }}"
                                            title="Modifier"
                                            class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Supprimer --}}
                                        <form action="{{ route('gestion.contrats.destroy', $contrat) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Supprimer ce contrat ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Supprimer"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($contrats->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $contrats->links() }}
                </div>
            @endif

        @else
            {{-- ÉTAT VIDE --}}
            <div class="p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Aucun contrat trouvé</h3>
                <p class="text-sm text-slate-500 mb-5">
                    @if(request()->hasAny(['search', 'type', 'statut']))
                        Aucun résultat ne correspond à vos filtres.
                    @else
                        Commencez par créer votre premier contrat.
                    @endif
                </p>
                <a href="{{ route('gestion.contrats.create') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer un contrat
                </a>
            </div>
        @endif
    </div>

</div>

@endsection