@extends('layouts.dashboard')

@section('title', 'Offres d\'emploi')

@section('content')

@php
    $stats = $stats ?? ['total' => 0, 'publiees' => 0, 'brouillon' => 0, 'archivees' => 0];
@endphp

<div class="max-w-7xl mx-auto">

    {{-- FIL D'ARIANE --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition">Tableau de bord</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Offres d'emploi</span>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6">
            <span class="text-sm font-medium">✅ {{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6">
            <span class="text-sm font-medium">❌ {{ session('error') }}</span>
        </div>
    @endif

    {{-- EN-TÊTE --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Offres d'emploi</h1>
            <p class="text-sm text-slate-500 mt-1">
                Créez et publiez vos offres sur la page d'accueil.
            </p>
        </div>

        <a href="{{ route('gestion.offres.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle offre
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Publiées</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['publiees'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-amber-500 uppercase">Brouillons</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['brouillon'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <p class="text-xs font-semibold text-slate-500 uppercase">Archivées</p>
            <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['archivees'] }}</p>
        </div>
    </div>

    {{-- FILTRES --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-5 mb-6 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Rechercher…"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
            </div>
            <div>
                <select name="statut" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                    <option value="">Tous les statuts</option>
                    <option value="publiee"   {{ request('statut') === 'publiee'   ? 'selected' : '' }}>Publiée</option>
                    <option value="brouillon" {{ request('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="archivee"  {{ request('statut') === 'archivee'  ? 'selected' : '' }}>Archivée</option>
                </select>
            </div>
            <div>
                <select name="type_contrat" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                    <option value="">Tous les contrats</option>
                    @foreach(['CDI', 'CDD', 'Stage', 'Freelance', 'Intérim'] as $type)
                        <option value="{{ $type }}" {{ request('type_contrat') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-slate-900 hover:bg-black text-white text-sm font-bold px-5 py-2.5 rounded-xl">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'statut', 'type_contrat']))
                    <a href="{{ route('gestion.offres.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold px-5 py-2.5 rounded-xl">
                        Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- LISTE --}}
    @if($offres->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Titre</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Type</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Lieu</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Deadline</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-slate-500 uppercase">Statut</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($offres as $offre)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $offre->titre }}</p>
                                <p class="text-xs text-slate-400">{{ $offre->email_contact }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                    @if($offre->type_contrat === 'CDI') bg-purple-100 text-purple-700
                                    @elseif($offre->type_contrat === 'CDD') bg-pink-100 text-pink-700
                                    @elseif($offre->type_contrat === 'Stage') bg-blue-100 text-blue-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ $offre->type_contrat }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $offre->lieu }}</td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $offre->deadline?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($offre->statut === 'publiee')
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">Publiée</span>
                                @elseif($offre->statut === 'brouillon')
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700">Brouillon</span>
                                @else
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">Archivée</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    @if($offre->statut !== 'publiee')
                                        <form action="{{ route('gestion.offres.publier', $offre) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Publier"
                                                class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('gestion.offres.edit', $offre) }}" title="Modifier"
                                        class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form action="{{ route('gestion.offres.destroy', $offre) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Supprimer cette offre ?');">
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

        @if($offres->hasPages())
            <div class="mt-6">{{ $offres->links() }}</div>
        @endif
    @else
        <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-1">Aucune offre</h3>
            <p class="text-sm text-slate-500 mb-5">Créez votre première offre d'emploi.</p>
            <a href="{{ route('gestion.offres.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-bold px-6 py-3 rounded-xl">
                Créer une offre
            </a>
        </div>
    @endif
</div>

@endsection