@extends('layouts.dashboard')

@section('title', $poste->nom)

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- Fil d'Ariane --}}
    <a href="{{ route('gestion.postes.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux postes
    </a>

    {{-- Carte principale --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête dégradé --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-extrabold">{{ $poste->nom }}</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $poste->departement?->nom ?? 'Sans département' }}
                        @if($poste->niveau)
                            · {{ $poste->niveau }}
                        @endif
                    </p>
                </div>

                <div class="flex gap-2 justify-center md:justify-end">
                    @if($poste->statut === 'actif')
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-emerald-100 text-emerald-700">
                            Actif
                        </span>
                    @else
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-slate-100 text-slate-700">
                            Inactif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="p-8">

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">Employés</p>
                    <p class="text-3xl font-extrabold text-blue-700">{{ $poste->employes_count }}</p>
                </div>

                <div class="md:col-span-2 bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Fourchette salariale</p>
                    @if($poste->salaire_min || $poste->salaire_max)
                        <p class="text-lg font-bold text-slate-900">
                            {{ $poste->salaire_min ? number_format($poste->salaire_min, 0, ',', ' ') : '—' }}
                            @if($poste->salaire_max && $poste->salaire_max != $poste->salaire_min)
                                → {{ number_format($poste->salaire_max, 0, ',', ' ') }}
                            @endif
                            <span class="text-sm font-normal text-slate-400">MGA</span>
                        </p>
                    @else
                        <p class="text-sm text-slate-400 italic">Non définie</p>
                    @endif
                </div>
            </div>

            {{-- Description --}}
            @if($poste->description)
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-3">Description</h3>
                <p class="text-sm text-slate-700 mb-8">{{ $poste->description }}</p>
            @endif

            {{-- Employés occupant ce poste --}}
            @if($poste->employes->isNotEmpty())
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4 pt-6 border-t border-slate-100">
                    Employés occupant ce poste ({{ $poste->employes->count() }})
                </h3>

                <div class="space-y-2 mb-8">
                    @foreach($poste->employes as $emp)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ $emp->initiales }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ $emp->nom_complet }}</p>
                                <p class="text-xs text-slate-500">{{ $emp->email }}</p>
                            </div>
                            <div class="text-xs text-slate-400">{{ $emp->matricule }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.postes.edit', $poste) }}"
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