@extends('layouts.dashboard')

@section('title', 'Présence du ' . $presence->date?->format('d/m/Y'))

@section('content')

<div class="max-w-5xl mx-auto">

    <a href="{{ route('gestion.presences.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux présences
    </a>

    @php
        $statuts = [
            'present' => ['label' => 'Présent', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
            'absent'  => ['label' => 'Absent',  'bg' => 'bg-red-100',     'text' => 'text-red-700'],
            'retard'  => ['label' => 'Retard',  'bg' => 'bg-orange-100',  'text' => 'text-orange-700'],
            'conge'   => ['label' => 'Congé',   'bg' => 'bg-blue-100',    'text' => 'text-blue-700'],
        ];
        $s = $statuts[$presence->statut] ?? ['label' => $presence->statut, 'bg' => 'bg-slate-100', 'text' => 'text-slate-700'];
    @endphp

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- En-tête --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Présence du</p>
                    <h1 class="text-2xl font-extrabold">{{ $presence->date?->format('d/m/Y') ?? '—' }}</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $presence->employe?->nom_complet ?? 'Employé inconnu' }}
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
            @if($presence->employe)
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ $presence->employe->initiales }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $presence->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $presence->employe->matricule }}
                                @if($presence->employe->poste)
                                    · {{ $presence->employe->poste->nom }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Détails --}}
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Détails de la présence</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Heure d'entrée</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $presence->heure_entree ? substr($presence->heure_entree, 0, 5) : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Heure de sortie</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $presence->heure_sortie ? substr($presence->heure_sortie, 0, 5) : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Heures travaillées</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $presence->heures_travaillees ? number_format($presence->heures_travaillees, 1) . ' h' : '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Retard</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ $presence->retard_minutes > 0 ? $presence->retard_minutes . ' minutes' : 'Aucun' }}
                    </p>
                </div>
                @if($presence->commentaire)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Commentaire</p>
                        <p class="text-sm text-slate-800">{{ $presence->commentaire }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.presences.edit', $presence) }}"
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