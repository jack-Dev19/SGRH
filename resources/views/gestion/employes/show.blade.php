@extends('layouts.dashboard')

@section('title', $employe->nom_complet)

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- Fil d'Ariane --}}
    <a href="{{ route('gestion.employes.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour à la liste
    </a>

    {{-- Carte principale --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête dégradé --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-2xl border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    {{ $employe->initiales }}
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-extrabold">{{ $employe->nom_complet }}</h1>
                    <p class="text-purple-100 text-sm mt-1">{{ $employe->matricule }}</p>
                    <p class="text-purple-100 text-xs mt-1">
                        {{ $employe->poste?->nom ?? 'Poste non défini' }}
                        @if($employe->departement)
                            · {{ $employe->departement->nom }}
                        @endif
                    </p>
                </div>

                <div class="flex gap-2 justify-center md:justify-end">
                    @if($employe->statut === 'actif')
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-4 py-2 rounded-full">
                            Actif
                        </span>
                    @else
                        <span class="text-xs font-bold text-slate-700 bg-slate-100 px-4 py-2 rounded-full">
                            Inactif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Corps : Infos --}}
        <div class="p-8">

            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Email</p>
                    <p class="text-sm text-slate-800 break-all">{{ $employe->email ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Téléphone</p>
                    <p class="text-sm text-slate-800">{{ $employe->telephone ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Sexe</p>
                    <p class="text-sm text-slate-800">{{ $employe->sexe ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de naissance</p>
                    <p class="text-sm text-slate-800">{{ $employe->date_naissance?->format('d/m/Y') ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Département</p>
                    <p class="text-sm text-slate-800">{{ $employe->departement?->nom ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Poste</p>
                    <p class="text-sm text-slate-800">{{ $employe->poste?->nom ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date d'embauche</p>
                    <p class="text-sm text-slate-800">{{ $employe->date_embauche?->format('d/m/Y') ?? '—' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Adresse</p>
                    <p class="text-sm text-slate-800">{{ $employe->adresse ?? '—' }}</p>
                </div>
            </div>

            {{-- Contrats --}}
            @if($employe->contrats->isNotEmpty())
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4 pt-6 border-t border-slate-100">
                    Contrats ({{ $employe->contrats->count() }})
                </h3>

                <div class="space-y-3 mb-8">
                    @foreach($employe->contrats as $contrat)
                        <div class="flex items-center justify-between bg-slate-50 rounded-xl p-4">
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $contrat->numero }} — {{ $contrat->type }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $contrat->date_debut?->format('d/m/Y') }}
                                    → {{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminé' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-slate-900">
                                    {{ number_format($contrat->salaire, 0, ',', ' ') }} {{ $contrat->devise }}
                                </p>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ $contrat->statut === 'actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ ucfirst($contrat->statut) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.employes.edit', $employe) }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
            </div>

        </div>
    </div>

</div>

@endsection