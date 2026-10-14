@extends('layouts.dashboard')

@section('title', 'Contrat ' . $contrat->numero)

@section('content')

<div class="max-w-5xl mx-auto">

    <a href="{{ route('gestion.contrats.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux contrats
    </a>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Contrat</p>
                    <h1 class="text-2xl font-extrabold font-mono">{{ $contrat->numero }}</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $contrat->type }}
                        @if($contrat->employe)
                            · {{ $contrat->employe->nom_complet }}
                        @endif
                    </p>
                </div>

                <div class="flex gap-2 justify-center md:justify-end">
                    @if($contrat->statut === 'actif')
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                    @elseif($contrat->statut === 'expire')
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-yellow-100 text-yellow-700">Expiré</span>
                    @else
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-red-100 text-red-700">Résilié</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="p-8">

            {{-- Employé --}}
            @if($contrat->employe)
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé concerné</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ $contrat->employe->initiales }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $contrat->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $contrat->employe->matricule }}
                                @if($contrat->employe->poste)
                                    · {{ $contrat->employe->poste->nom }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Détails --}}
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Détails du contrat</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Type de contrat</p>
                    <p class="text-sm font-medium text-slate-800">{{ $contrat->type }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Salaire</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ number_format($contrat->salaire, 0, ',', ' ') }} {{ $contrat->devise }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de début</p>
                    <p class="text-sm text-slate-800">{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de fin</p>
                    <p class="text-sm text-slate-800">
                        {{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}
                    </p>
                </div>
                @if($contrat->periode_essai)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Période d'essai</p>
                        <p class="text-sm text-slate-800">
                            {{ $contrat->periode_essai }} {{ $contrat->unite_periode_essai ?? 'mois' }}
                        </p>
                    </div>
                @endif
                @if($contrat->description)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Description</p>
                        <p class="text-sm text-slate-800">{{ $contrat->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.contrats.edit', $contrat) }}"
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