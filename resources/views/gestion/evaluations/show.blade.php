@extends('layouts.dashboard')

@section('title', $evaluation->titre)

@section('content')

<div class="max-w-5xl mx-auto">

    <a href="{{ route('gestion.evaluations.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux évaluations
    </a>

    @php
        $statuts = [
            'en_cours' => ['label' => 'En cours', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
            'terminee' => ['label' => 'Terminée', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
            'annulee'  => ['label' => 'Annulée',  'bg' => 'bg-slate-100',  'text' => 'text-slate-700'],
        ];
        $s = $statuts[$evaluation->statut] ?? ['label' => $evaluation->statut, 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'];

        $score = $evaluation->score_global ?? 0;
        if ($score >= 90)      $scoreColor = 'text-emerald-600';
        elseif ($score >= 75)  $scoreColor = 'text-blue-600';
        elseif ($score >= 60)  $scoreColor = 'text-yellow-600';
        else                   $scoreColor = 'text-red-600';
    @endphp

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Évaluation</p>
                    <h1 class="text-2xl font-extrabold">{{ $evaluation->titre }}</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ ucfirst($evaluation->type) }}
                        @if($evaluation->periode)
                            · {{ $evaluation->periode }}
                        @endif
                    </p>
                </div>

                <div class="flex gap-2 justify-center md:justify-end">
                    <span class="text-xs font-bold px-4 py-2 rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                        {{ $s['label'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Détails --}}
        <div class="p-8">

            {{-- Employé --}}
            @if($evaluation->employe)
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé évalué</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ $evaluation->employe->initiales }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $evaluation->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $evaluation->employe->matricule }}
                                @if($evaluation->employe->poste)
                                    · {{ $evaluation->employe->poste->nom }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Score géant --}}
            <div class="bg-gradient-to-br from-slate-50 to-white border-2 border-slate-100 rounded-2xl p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="text-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Score global</p>
                        <p class="text-6xl font-extrabold {{ $scoreColor }} leading-none">
                            {{ $score > 0 ? number_format($score, 0) : '—' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1">/ 100</p>
                    </div>

                    <div class="flex-1 w-full">
                        @if($score > 0)
                            <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden mb-3">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"
                                     style="width: {{ $score }}%"></div>
                            </div>
                        @endif

                        @if($evaluation->niveauAppreciation)
                            <div class="text-center md:text-left">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Appréciation</p>
                                <span class="inline-block text-base font-extrabold bg-purple-100 text-purple-700 px-4 py-1.5 rounded-full">
                                    {{ $evaluation->niveauAppreciation->nom }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Détails --}}
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Informations</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Type</p>
                    <p class="text-sm font-medium text-slate-800">{{ ucfirst($evaluation->type) }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Période</p>
                    <p class="text-sm text-slate-800">{{ $evaluation->periode ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date d'évaluation</p>
                    <p class="text-sm text-slate-800">{{ $evaluation->date_evaluation?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Évaluateur</p>
                    <p class="text-sm text-slate-800">{{ $evaluation->evaluateur?->name ?? '—' }}</p>
                </div>
                @if($evaluation->appreciation)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Appréciation textuelle</p>
                        <p class="text-sm text-slate-800">{{ $evaluation->appreciation }}</p>
                    </div>
                @endif
                @if($evaluation->commentaire)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Commentaire</p>
                        <p class="text-sm text-slate-800">{{ $evaluation->commentaire }}</p>
                    </div>
                @endif
                @if($evaluation->recommandation)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Recommandation</p>
                        <p class="text-sm text-slate-800">{{ $evaluation->recommandation }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.evaluations.edit', $evaluation) }}"
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