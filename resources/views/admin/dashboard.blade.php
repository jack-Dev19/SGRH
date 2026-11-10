@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- ============================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ============================================= --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-2.5 py-1 text-[10px] font-bold text-purple-600 uppercase tracking-wider ring-1 ring-inset ring-purple-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Vue d'ensemble
                </span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Tableau de bord</h1>
            <p class="text-sm text-slate-500 mt-1">Vue d'ensemble de l'organisation · {{ now()->translatedFormat('l j F Y') }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Actualiser
            </a>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- LIGNE 1 : CARTES STATS --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 mb-8">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Employés</p>
                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total_employes'] }}</p>
            <p class="text-[10px] text-slate-400 mt-1">{{ $stats['employes_actifs'] }} actifs</p>
        </div>

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-wider">Actifs</p>
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700">{{ $stats['employes_actifs'] }}</p>
            <p class="text-[10px] text-emerald-500 mt-1">En poste</p>
        </div>

        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">Congés</p>
                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-blue-700">{{ $stats['conges_en_cours'] }}</p>
            <p class="text-[10px] text-blue-500 mt-1">{{ $stats['conges_en_attente'] }} en attente</p>
        </div>

        <div class="rounded-2xl border border-purple-200 bg-purple-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider">Masse salariale</p>
                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-lg font-extrabold text-purple-700 truncate">{{ number_format($masseSalariale, 0, ',', ' ') }}</p>
            <p class="text-[10px] text-purple-500 mt-1">MGA / mois</p>
        </div>

        <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Recrutements</p>
                <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700">{{ $stats['recrutements'] }}</p>
            <p class="text-[10px] text-amber-500 mt-1">Offres publiées</p>
        </div>

        <div class="rounded-2xl border border-red-200 bg-red-50/50 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Notifications</p>
                <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-red-600">{{ $notificationsNonLues }}</p>
            <p class="text-[10px] text-red-500 mt-1">Non lues</p>
        </div>

    </div>

    {{-- ============================================= --}}
    {{-- LIGNE 2 : GRAPHIQUE CONGÉS + DONUT --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        {{-- Graphique congés par mois --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        </div>
                        <h2 class="text-base font-bold text-slate-900">Congés par mois</h2>
                    </div>
                    <p class="text-xs text-slate-500 ml-9">Année {{ now()->year }}</p>
                </div>
            </div>

            <div class="h-64">
                <canvas id="chart-conges"
                        data-labels='@json(array_map(fn($m) => \Carbon\Carbon::create()->month($m)->locale("fr")->shortMonthName, array_keys($congesParMoisComplet)))'
                        data-values='@json(array_values($congesParMoisComplet))'>
                </canvas>
            </div>
        </div>

        {{-- Donut : répartition par département --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                </div>
                <h2 class="text-base font-bold text-slate-900">Répartition</h2>
            </div>
            <p class="text-xs text-slate-500 ml-9 mb-4">Effectif total : {{ $stats['total_employes'] }}</p>

            <div class="h-48 mb-4">
                <canvas id="chart-departements"
                        data-labels='@json($repartitionDepartements->pluck("nom"))'
                        data-values='@json($repartitionDepartements->pluck("employes_count"))'>
                </canvas>
            </div>

            <div class="space-y-1.5 mt-4">
                @php
                    $palette = ['#3b82f6', '#ef4444', '#10b981', '#8b5cf6', '#f59e0b', '#ec4899', '#06b6d4', '#84cc16'];
                @endphp
                @foreach($repartitionDepartements as $i => $dep)
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $palette[$i % count($palette)] }}"></span>
                            <span class="text-xs font-medium text-slate-700 truncate">{{ $dep->nom }}</span>
                        </div>
                        <span class="text-xs font-bold text-slate-900 shrink-0">{{ $dep->employes_count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ============================================= --}}
    {{-- LIGNE 3 : ÉVOLUTION EFFECTIF + ACTIVITÉ --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        {{-- Évolution effectif --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900">Évolution de l'effectif</h2>
                    <p class="text-xs text-slate-500">Cumul des embauches — {{ now()->year }}</p>
                </div>
            </div>

            <div class="h-48">
                <canvas id="chart-effectif"
                        data-labels='@json(array_map(fn($m) => \Carbon\Carbon::create()->month($m)->locale("fr")->shortMonthName, array_keys($effectifParMois)))'
                        data-values='@json(array_values($effectifParMois))'>
                </canvas>
            </div>
        </div>

        {{-- Activité récente --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-base font-bold text-slate-900">Activité récente</h2>
            </div>

            <div class="space-y-3">
                @forelse($activiteRecente as $item)
                    <div class="flex items-start gap-3 pb-3 border-b border-slate-100 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-[10px] font-bold shrink-0 shadow-sm">
                            {{ strtoupper(substr($item['user'] ?? 'NA', 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-900 truncate">{{ $item['titre'] }}</p>
                            <p class="text-[10px] text-slate-500 truncate">{{ $item['user'] }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item['date']?->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-slate-400">Aucune activité récente.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ============================================= --}}
    {{-- LIGNE 4 : CONGÉS EN ATTENTE --}}
    {{-- ============================================= --}}
    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h2 class="text-base font-bold text-slate-900">Congés en attente de validation</h2>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-700 ring-1 ring-inset ring-yellow-200">
                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                {{ $stats['conges_en_attente'] }} en attente
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-left px-6 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Employé</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Type</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider hidden md:table-cell">Période</th>
                        <th class="text-left px-6 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Durée</th>
                        <th class="text-right px-6 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($congesEnAttente as $conge)
                        <tr class="group hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-sm">
                                        {{ $conge->employe?->initiales ?? '??' }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate">{{ $conge->employe?->nom_complet ?? 'Employé inconnu' }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $conge->employe?->matricule }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700 ring-1 ring-inset ring-blue-200">
                                    {{ $conge->typeConge?->nom ?? 'Type inconnu' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-xs text-slate-700">{{ $conge->date_debut?->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-slate-400">→ {{ $conge->date_fin?->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-slate-900">{{ $conge->nombre_jours }}</span>
                                <span class="text-[10px] text-slate-400 ml-1">jour(s)</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button class="inline-flex items-center gap-1 text-[10px] font-bold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-100 ring-1 ring-inset ring-emerald-200 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Approuver
                                    </button>
                                    <button class="inline-flex items-center gap-1 text-[10px] font-bold px-3 py-1.5 rounded-full bg-red-50 text-red-700 hover:bg-red-100 ring-1 ring-inset ring-red-200 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        Refuser
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-900">Aucun congé en attente</p>
                                <p class="text-xs text-slate-500 mt-1">Toutes les demandes ont été traitées.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection