@extends('layouts.dashboard')

@section('title', $departement->nom)

@section('content')

<div class="max-w-6xl mx-auto">

    <a href="{{ route('gestion.departements.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux départements
    </a>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm mb-6">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl font-extrabold">{{ $departement->nom }}</h1>
                    <p class="text-purple-100 text-sm mt-1">{{ $departement->description ?: 'Aucune description' }}</p>
                </div>

                <div class="flex gap-2 justify-center md:justify-end">
                    @if($departement->statut == 1)
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                    @else
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-slate-100 text-slate-700">Inactif</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-1">Employés</p>
                    <p class="text-3xl font-extrabold text-blue-700">{{ $departement->employes_count }}</p>
                </div>

                <div class="bg-purple-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-purple-600 uppercase tracking-wide mb-1">Postes</p>
                    <p class="text-3xl font-extrabold text-purple-700">{{ $departement->postes_count ?? 0 }}</p>
                </div>

                <div class="md:col-span-2 bg-slate-50 rounded-xl p-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Responsable</p>
                    @if($departement->responsable)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($departement->responsable->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $departement->responsable->name }}</p>
                                <p class="text-xs text-slate-500">{{ $departement->responsable->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate-400 italic">Non assigné</p>
                    @endif
                </div>
            </div>

            @if($departement->employes->isNotEmpty())
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4 pt-6 border-t border-slate-100">
                    Employés ({{ $departement->employes->count() }})
                </h3>

                <div class="space-y-2 mb-8">
                    @foreach($departement->employes as $emp)
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ $emp->initiales }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ $emp->nom_complet }}</p>
                                <p class="text-xs text-slate-500">{{ $emp->poste?->nom ?? 'Poste non défini' }}</p>
                            </div>
                            <div class="text-xs text-slate-400">{{ $emp->matricule }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.departements.edit', $departement) }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                    Modifier
                </a>
            </div>
        </div>
    </div>
</div>

@endsection