@extends('layouts.dashboard')

@section('title', 'Absence de ' . $absence->employe?->nom_complet)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.absences.index') }}" class="hover:text-purple-600 transition">Absences</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">Détails</span>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @php
        $statuts = [
            'en_attente'   => ['label' => 'En attente',    'badge' => 'bg-yellow-100 text-yellow-700',  'dot' => 'bg-yellow-500'],
            'justifie'     => ['label' => 'Justifiée',     'badge' => 'bg-emerald-100 text-emerald-700','dot' => 'bg-emerald-500'],
            'non_justifie' => ['label' => 'Non justifiée', 'badge' => 'bg-red-100 text-red-700',        'dot' => 'bg-red-500'],
            'autorise'     => ['label' => 'Autorisée',     'badge' => 'bg-blue-100 text-blue-700',      'dot' => 'bg-blue-500'],
            'annule'       => ['label' => 'Annulée',       'badge' => 'bg-slate-100 text-slate-600',    'dot' => 'bg-slate-400'],
        ];
        $s = $statuts[$absence->statut] ?? ['label' => $absence->statut, 'badge' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400'];
    @endphp

    {{-- Carte principale --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row items-center gap-5">

                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-md border-2 border-white/30 flex items-center justify-center text-2xl font-extrabold shrink-0">
                    {{ $absence->employe?->initiales ?? '??' }}
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Absence</p>
                    <h1 class="text-2xl font-extrabold">{{ $absence->employe?->nom_complet }}</h1>
                    <p class="text-purple-100 text-sm mt-1 font-mono">{{ $absence->employe?->matricule }}</p>
                </div>

                <span class="text-xs font-bold px-4 py-2 rounded-full {{ $s['badge'] }}">
                    {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Détails --}}
        <div class="p-8">

            {{-- Dates --}}
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Période</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date de début</p>
                    <p class="text-sm font-bold text-slate-900">
                        {{ $absence->date_debut?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Date de fin</p>
                    <p class="text-sm font-bold text-slate-900">
                        {{ $absence->date_fin?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Durée</p>
                    <p class="text-sm font-bold text-purple-600">
                        {{ (int) $absence->nombre_jours }} jour(s)
                    </p>
                </div>
            </div>

            {{-- Motif --}}
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Motif</h3>

            <div class="bg-slate-50 rounded-xl p-4 mb-8">
                <p class="text-sm font-bold text-slate-900 mb-2">{{ $absence->motif }}</p>
                @if($absence->description)
                    <p class="text-sm text-slate-600">{{ $absence->description }}</p>
                @endif
            </div>

            {{-- Origine --}}
            <div class="flex items-center gap-2 mb-8">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Origine :</span>
                @if($absence->origine === 'auto')
                    <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1 rounded-full">
                        🤖 Détection automatique
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1 rounded-full">
                        ✏️ Saisie manuelle
                    </span>
                @endif
            </div>

            {{-- Justificatif --}}
            @if($absence->justificatif)
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Justificatif</h3>

                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-emerald-800">Justificatif fourni</p>
                        <p class="text-xs text-emerald-600">Fichier joint à cette absence</p>
                    </div>
                    <a href="{{ Storage::url($absence->justificatif) }}" target="_blank"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Ouvrir
                    </a>
                </div>
            @endif

            {{-- Validation --}}
            @if($absence->validateur)
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-4">Validation</h3>

                <div class="bg-slate-50 rounded-xl p-4 mb-8">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs">
                            {{ strtoupper(substr($absence->validateur->name ?? 'NA', 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $absence->validateur->name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $absence->date_validation?->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                    @if($absence->commentaire_validation)
                        <p class="text-xs text-slate-600 italic mt-2 pl-11">
                            "{{ $absence->commentaire_validation }}"
                        </p>
                    @endif
                </div>
            @endif

            {{-- Actions de validation --}}
            @if($absence->statut === 'en_attente')
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-8">
                    <p class="text-xs font-bold text-yellow-800 uppercase tracking-wide mb-3">Action requise</p>
                    <div class="flex gap-2">
                        <form action="{{ route('gestion.absences.approuver', $absence) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Approuver cette absence ?');"
                                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold py-3 rounded-xl transition shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approuver
                            </button>
                        </form>
                        <form action="{{ route('gestion.absences.refuser', $absence) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Refuser cette absence ?');"
                                class="w-full inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-3 rounded-xl transition shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Refuser
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Actions bas --}}
            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.absences.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour
                </a>

                <div class="flex items-center gap-2">
                    <form action="{{ route('gestion.absences.destroy', $absence) }}" method="POST"
                        onsubmit="return confirm('Voulez-vous vraiment supprimer cette absence ?');"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 text-sm font-bold px-4 py-2.5 rounded-xl transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </form>

                    <a href="{{ route('gestion.absences.edit', $absence) }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection