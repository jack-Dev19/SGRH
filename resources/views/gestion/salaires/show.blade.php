@extends('layouts.dashboard')

@section('title', 'Salaire')

@section('content')

<div class="max-w-5xl mx-auto">

    <a href="{{ route('gestion.salaires.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux salaires
    </a>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Salaire</p>
                    <h1 class="text-2xl font-extrabold">
                        {{ number_format($salaire->salaire_base, 0, ',', ' ') }} MGA
                    </h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $salaire->employe?->nom_complet ?? 'Employé inconnu' }}
                    </p>
                </div>

                <div class="flex gap-2 justify-center md:justify-end">
                    @if($salaire->statut === 'actif')
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                    @else
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-slate-100 text-slate-700">Inactif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="p-8">

            @if($salaire->employe)
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ $salaire->employe->initiales }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $salaire->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $salaire->employe->matricule }}
                                @if($salaire->employe->poste)
                                    · {{ $salaire->employe->poste->nom }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Détails du salaire</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de début</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $salaire->date_debut?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de fin</p>
                    <p class="text-sm text-slate-800">
                        {{ $salaire->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}
                    </p>
                </div>
                @if($salaire->motif)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Motif</p>
                        <p class="text-sm text-slate-800">{{ $salaire->motif }}</p>
                    </div>
                @endif
                @if($salaire->commentaire)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Commentaire</p>
                        <p class="text-sm text-slate-800">{{ $salaire->commentaire }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.salaires.edit', $salaire) }}"
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