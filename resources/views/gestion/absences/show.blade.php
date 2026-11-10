@extends('layouts.dashboard')

@section('title', 'Absence de ' . $absence->employe?->nom_complet)

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.absences.index') }}" class="hover:text-purple-700 transition-colors">Absences</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">Détails</span>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @php
        $statuts = [
            'en_attente'   => ['label' => 'En attente',    'bg' => 'bg-yellow-50',  'text' => 'text-yellow-700',  'ring' => 'ring-yellow-200',  'dot' => 'bg-yellow-500'],
            'justifie'     => ['label' => 'Justifiée',     'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-200', 'dot' => 'bg-emerald-500'],
            'non_justifie' => ['label' => 'Non justifiée', 'bg' => 'bg-red-50',     'text' => 'text-red-700',     'ring' => 'ring-red-200',     'dot' => 'bg-red-500'],
            'autorise'     => ['label' => 'Autorisée',     'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'ring' => 'ring-blue-200',    'dot' => 'bg-blue-500'],
            'annule'       => ['label' => 'Annulée',       'bg' => 'bg-slate-50',   'text' => 'text-slate-600',   'ring' => 'ring-slate-200',   'dot' => 'bg-slate-400'],
        ];
        $s = $statuts[$absence->statut] ?? ['label' => $absence->statut, 'bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'ring' => 'ring-slate-200', 'dot' => 'bg-slate-400'];
    @endphp

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Avatar --}}
            <div class="mx-auto w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white font-extrabold text-2xl">
                    {{ $absence->employe?->initiales ?? '??' }}
                </div>
            </div>

            <p class="mt-4 text-[10px] font-bold text-purple-400 uppercase tracking-widest">Absence</p>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $absence->employe?->nom_complet }}</h1>
            <p class="text-sm text-slate-500 font-mono">{{ $absence->employe?->matricule }}</p>

            {{-- Badge Statut --}}
            <div class="mt-3">
                <span class="inline-flex items-center gap-1.5 rounded-full {{ $s['bg'] }} px-3 py-1 text-xs font-bold {{ $s['text'] }} ring-1 ring-inset {{ $s['ring'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $s['dot'] }} @if($absence->statut === 'en_attente') animate-pulse @endif"></span>
                    {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section Période --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Période</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-8">
                {{-- Date début --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Début</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $absence->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Date fin --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Fin</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $absence->date_fin?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Durée --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Durée</p></div>
                    <p class="text-sm font-bold text-purple-600">{{ (int) $absence->nombre_jours }} jour(s)</p>
                </div>
            </div>

            {{-- Section Motif --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Motif</h3>
            </div>

            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                <p class="text-sm font-bold text-slate-900 mb-2">{{ $absence->motif }}</p>
                @if($absence->description)
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $absence->description }}</p>
                @endif
            </div>

            {{-- Origine --}}
            <div class="flex items-center gap-2 mb-8">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Origine :</span>
                @if($absence->origine === 'auto')
                    <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-700 text-xs font-bold px-3 py-1 rounded-full ring-1 ring-inset ring-orange-200">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Détection automatique
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1 rounded-full ring-1 ring-inset ring-purple-200">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Saisie manuelle
                    </span>
                @endif
            </div>

            {{-- Justificatif --}}
            @if($absence->justificatif)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">3</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Justificatif</h3>
                </div>

                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-emerald-800">Justificatif fourni</p>
                        <p class="text-xs text-emerald-600">Fichier joint à cette absence</p>
                    </div>
                    <a href="{{ Storage::url($absence->justificatif) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-full transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Ouvrir
                    </a>
                </div>
            @endif

            {{-- Validation --}}
            @if($absence->validateur)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">4</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Validation</h3>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs">
                            {{ strtoupper(substr($absence->validateur->name ?? 'NA', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $absence->validateur->name }}</p>
                            <p class="text-xs text-slate-500">{{ $absence->date_validation?->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    @if($absence->commentaire_validation)
                        <p class="text-xs text-slate-600 italic mt-2 pl-12">"{{ $absence->commentaire_validation }}"</p>
                    @endif
                </div>
            @endif

            {{-- Action requise --}}
            @if($absence->statut === 'en_attente')
                <div class="rounded-2xl bg-yellow-50 border border-yellow-200 p-4 mb-8">
                    <p class="text-[10px] font-bold text-yellow-700 uppercase tracking-widest mb-3">Action requise</p>
                    <div class="flex gap-2">
                        <form action="{{ route('gestion.absences.refuser', $absence) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" onclick="return confirm('Refuser cette absence ?');"
                                class="w-full inline-flex items-center justify-center gap-2 text-red-600 bg-white hover:bg-red-50 border border-red-200 text-sm font-bold py-3 rounded-full transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Refuser
                            </button>
                        </form>
                        <form action="{{ route('gestion.absences.approuver', $absence) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" onclick="return confirm('Approuver cette absence ?');"
                                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold py-3 rounded-full shadow-md transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Approuver
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                <a href="{{ route('gestion.absences.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <form action="{{ route('gestion.absences.destroy', $absence) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette absence ?');" class="flex-1 md:flex-initial">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 text-sm font-bold px-5 py-3 rounded-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer
                        </button>
                    </form>

                    <a href="{{ route('gestion.absences.edit', $absence) }}" class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier
                    </a>
                </div>
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