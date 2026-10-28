@extends('layouts.dashboard')

@section('title', $evaluation->titre)

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.evaluations.index') }}" class="hover:text-purple-700 transition-colors">Évaluations</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">Détails</span>
    </div>

    @php
        $statuts = [
            'en_cours' => ['label' => 'En cours', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'ring' => 'ring-yellow-200', 'dot' => 'bg-yellow-500'],
            'terminee' => ['label' => 'Terminée', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-200', 'dot' => 'bg-emerald-500'],
            'annulee'  => ['label' => 'Annulée',  'bg' => 'bg-slate-50',   'text' => 'text-slate-600',   'ring' => 'ring-slate-200',   'dot' => 'bg-slate-400'],
        ];
        $s = $statuts[$evaluation->statut] ?? ['label' => $evaluation->statut, 'bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'ring' => 'ring-slate-200', 'dot' => 'bg-slate-400'];

        $score = $evaluation->score_global ?? 0;
        if ($score >= 90)      $scoreColor = 'text-emerald-600';
        elseif ($score >= 75)  $scoreColor = 'text-blue-600';
        elseif ($score >= 60)  $scoreColor = 'text-yellow-600';
        else                   $scoreColor = 'text-red-600';
    @endphp

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Icône --}}
            <div class="mx-auto w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-[10px] font-bold text-purple-400 uppercase tracking-widest">Évaluation</p>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $evaluation->titre }}</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ ucfirst($evaluation->type) }}
                @if($evaluation->periode) · {{ $evaluation->periode }} @endif
            </p>

            {{-- Badge Statut --}}
            <div class="mt-3">
                <span class="inline-flex items-center gap-1.5 rounded-full {{ $s['bg'] }} px-3 py-1 text-xs font-bold {{ $s['text'] }} ring-1 ring-inset {{ $s['ring'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $s['dot'] }} @if($evaluation->statut === 'en_cours') animate-pulse @endif"></span>
                    {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section Employé --}}
            @if($evaluation->employe)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Employé évalué</h3>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ $evaluation->employe->initiales }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $evaluation->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $evaluation->employe->matricule }}
                                @if($evaluation->employe->poste) · {{ $evaluation->employe->poste->nom }} @endif
                            </p>
                        </div>
                        <a href="{{ route('gestion.employes.show', $evaluation->employe) }}"
                            class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:text-purple-700 bg-white px-3 py-2 rounded-full shadow-sm transition">
                            Voir
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endif

            {{-- Section Score --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Score global</h3>
            </div>

            <div class="rounded-3xl bg-gradient-to-br from-slate-50 to-white border border-slate-200 p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    {{-- Score géant --}}
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Score</p>
                        <p class="text-6xl font-extrabold {{ $scoreColor }} leading-none">
                            {{ $score > 0 ? number_format($score, 0) : '—' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1">/ 100</p>
                    </div>

                    {{-- Barre + Appréciation --}}
                    <div class="flex-1 w-full">
                        @if($score > 0)
                            <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden mb-3">
                                <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full" style="width: {{ $score }}%"></div>
                            </div>
                        @endif

                        @if($evaluation->niveauAppreciation)
                            <div class="text-center md:text-left">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Appréciation</p>
                                <span class="inline-block text-base font-extrabold bg-purple-100 text-purple-700 px-4 py-1.5 rounded-full ring-1 ring-inset ring-purple-200">
                                    {{ $evaluation->niveauAppreciation->nom }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Section Informations --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">3</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Informations</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                {{-- Type --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Type</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ ucfirst($evaluation->type) }}</p>
                </div>

                {{-- Période --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Période</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $evaluation->periode ?? '—' }}</p>
                </div>

                {{-- Date d'évaluation --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $evaluation->date_evaluation?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Évaluateur --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Évaluateur</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $evaluation->evaluateur?->name ?? '—' }}</p>
                </div>

                {{-- Appréciation textuelle --}}
                @if($evaluation->appreciation)
                    <div class="md:col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Appréciation</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $evaluation->appreciation }}</p>
                    </div>
                @endif

                {{-- Commentaire --}}
                @if($evaluation->commentaire)
                    <div class="md:col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Commentaire</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $evaluation->commentaire }}</p>
                    </div>
                @endif

                {{-- Recommandation --}}
                @if($evaluation->recommandation)
                    <div class="md:col-span-2 rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Recommandation</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $evaluation->recommandation }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                <a href="{{ route('gestion.evaluations.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Retour à la liste
                </a>

                <a href="{{ route('gestion.evaluations.edit', $evaluation) }}"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier l'évaluation
                </a>
            </div>
        </div>

        {{-- Vagues décoratives --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-40">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                <path fill="rgba(167, 139, 250, 0.2)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                <path fill="rgba(139, 92, 246, 0.3)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                <path fill="rgba(124, 58, 237, 0.15)" d="M0,140 C150,190 350,90 500,140 L500,150 L0,150 Z"></path>
            </svg>
        </div>
    </div>
</div>

@endsection