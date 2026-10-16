@extends('layouts.dashboard')

@section('title', 'Mon profil')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-3xl font-extrabold">
                    {{ $employe->initiales }}
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-extrabold">{{ $employe->nom_complet }}</h1>
                    <p class="text-purple-100 text-sm mt-1">{{ $employe->matricule }}</p>
                    <p class="text-purple-100 text-sm">
                        {{ $employe->poste?->nom ?? '—' }}
                        @if($employe->departement) · {{ $employe->departement->nom }} @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="p-8">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Email</p><p class="text-sm text-slate-800 break-all">{{ $employe->email ?? '—' }}</p></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Téléphone</p><p class="text-sm text-slate-800">{{ $employe->telephone ?? '—' }}</p></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Date de naissance</p><p class="text-sm text-slate-800">{{ $employe->date_naissance?->format('d/m/Y') ?? '—' }}</p></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Sexe</p><p class="text-sm text-slate-800">{{ $employe->sexe ?? '—' }}</p></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Date d'embauche</p><p class="text-sm text-slate-800">{{ $employe->date_embauche?->format('d/m/Y') ?? '—' }}</p></div>
                <div><p class="text-xs font-bold text-slate-400 uppercase mb-1">Adresse</p><p class="text-sm text-slate-800">{{ $employe->adresse ?? '—' }}</p></div>
            </div>

            {{-- Contrats --}}
            @if($employe->contrats->isNotEmpty())
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4 pt-6 border-t border-slate-100">
                    Mes contrats ({{ $employe->contrats->count() }})
                </h3>

                <div class="space-y-3">
                    @foreach($employe->contrats as $contrat)
                        <div class="flex items-center justify-between bg-slate-50 rounded-xl p-4">
                            <div>
                                <p class="text-sm font-bold text-slate-900 font-mono">{{ $contrat->numero }} · {{ $contrat->type }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $contrat->date_debut?->format('d/m/Y') }}
                                    → {{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}
                                </p>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $contrat->statut === 'actif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($contrat->statut) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

@endsection