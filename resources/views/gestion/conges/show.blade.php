@extends('layouts.dashboard')

@section('title', 'Demande de congé')

@section('content')

<div class="max-w-5xl mx-auto">

    <a href="{{ route('gestion.conges.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux congés
    </a>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @php
        $statuts = [
            'en_attente' => ['label' => 'En attente', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
            'approuve'   => ['label' => 'Approuvé',   'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
            'refuse'     => ['label' => 'Refusé',     'bg' => 'bg-red-100',     'text' => 'text-red-700'],
        ];
        $s = $statuts[$conge->statut] ?? ['label' => $conge->statut, 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'];
    @endphp

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold text-2xl border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    {{ $conge->employe?->initiales ?? '??' }}
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Demande de congé</p>
                    <h1 class="text-2xl font-extrabold">{{ $conge->employe?->nom_complet ?? 'Employé inconnu' }}</h1>
                    <p class="text-purple-100 text-sm mt-1 font-mono">{{ $conge->employe?->matricule }}</p>
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

            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Détails du congé</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Type</p>
                    <p class="text-sm font-medium text-slate-800">{{ $conge->typeConge?->nom ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Durée</p>
                    <p class="text-sm font-bold text-slate-800">{{ $conge->nombre_jours }} jour(s)</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de début</p>
                    <p class="text-sm text-slate-800">{{ $conge->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de fin</p>
                    <p class="text-sm text-slate-800">{{ $conge->date_fin?->format('d/m/Y') ?? '—' }}</p>
                </div>
                @if($conge->motif)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Motif</p>
                        <p class="text-sm text-slate-800">{{ $conge->motif }}</p>
                    </div>
                @endif
            </div>

            {{-- Validation --}}
            @if($conge->valide_par && $conge->valideur)
                <div class="bg-slate-50 rounded-xl p-4 mb-8">
                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Validation</h4>
                    <p class="text-sm text-slate-700">
                        Validé par <strong>{{ $conge->valideur->name }}</strong>
                        le {{ $conge->date_validation?->format('d/m/Y à H:i') ?? '—' }}
                    </p>
                    @if($conge->commentaire_validation)
                        <p class="text-xs text-slate-500 mt-2 italic">"{{ $conge->commentaire_validation }}"</p>
                    @endif
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center justify-between flex-wrap gap-3 pt-6 border-t border-slate-100">

                {{-- Actions valider --}}
                <div class="flex items-center gap-2">
                    @if($conge->statut === 'en_attente')
                        <form action="{{ route('gestion.conges.approuver', $conge) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Approuver ce congé ?');"
                                class="inline-flex items-center gap-2 text-sm font-bold px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition shadow-lg shadow-emerald-600/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approuver
                            </button>
                        </form>

                        <form action="{{ route('gestion.conges.refuser', $conge) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Refuser ce congé ?');"
                                class="inline-flex items-center gap-2 text-sm font-bold px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white transition shadow-lg shadow-red-600/30">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Refuser
                            </button>
                        </form>
                    @endif
                </div>

                {{-- Actions secondaires --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('gestion.conges.edit', $conge) }}"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>

                    <form action="{{ route('gestion.conges.destroy', $conge) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Supprimer cette demande ?');"
                            class="text-sm font-medium text-red-600 hover:text-red-800 px-4 py-3 rounded-xl hover:bg-red-50 transition">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection