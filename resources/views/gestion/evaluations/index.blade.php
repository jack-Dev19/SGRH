@extends('layouts.dashboard')

@section('title', 'Évaluations')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- En-tête --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Évaluations</h1>
            <p class="text-sm text-slate-500">{{ $evaluations->total() }} évaluation(s) enregistrée(s)</p>
        </div>

        <a href="{{ route('gestion.evaluations.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-5 py-3 rounded-full transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle évaluation
        </a>
    </div>

    {{-- Cartes stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
                <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terminées</p>
                <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['termine'] }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">En cours</p>
                <div class="w-7 h-7 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-yellow-600">{{ $stats['en_cours'] }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Score moyen</p>
                <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-purple-600">
                {{ number_format($stats['moyenne'], 1) }}<span class="text-sm text-slate-400">/100</span>
            </p>
        </div>
    </div>

    {{-- Barre de recherche + filtres --}}
    <div class="bg-white rounded-2xl border border-purple-100 p-4 mb-6 shadow-sm">
        <form method="GET" action="{{ route('gestion.evaluations.index') }}" class="flex flex-wrap items-center gap-3">

            <div class="flex-1 min-w-[240px]">
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un employé..."
                        class="w-full pl-11 pr-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                </div>
            </div>

            <div class="min-w-[160px]">
                <select name="type"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    <option value="">Tous les types</option>
                    <option value="annuelle" {{ request('type') === 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                    <option value="trimestrielle" {{ request('type') === 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                    <option value="probatoire" {{ request('type') === 'probatoire' ? 'selected' : '' }}>Probatoire</option>
                    <option value="ponctuelle" {{ request('type') === 'ponctuelle' ? 'selected' : '' }}>Ponctuelle</option>
                </select>
            </div>

            <div class="min-w-[140px]">
                <select name="statut"
                    class="w-full px-4 py-3 rounded-full border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition-all duration-200">
                    <option value="">Tous les statuts</option>
                    <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée</option>
                    <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-full transition shadow-lg shadow-purple-600/30">
                Filtrer
            </button>

            @if(request()->hasAny(['search', 'type', 'statut']))
                <a href="{{ route('gestion.evaluations.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 hover:bg-purple-50 px-4 py-3 rounded-full transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Grille de cartes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($evaluations as $evaluation)
            @php
                $palette = [
                    ['from' => 'from-blue-500',    'to' => 'to-blue-600',    'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-100'],
                    ['from' => 'from-violet-500',  'to' => 'to-violet-600',  'bg' => 'bg-violet-50',  'text' => 'text-violet-700',  'border' => 'border-violet-100'],
                    ['from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-100'],
                    ['from' => 'from-orange-500',  'to' => 'to-orange-600',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'border' => 'border-orange-100'],
                    ['from' => 'from-pink-500',    'to' => 'to-pink-600',    'bg' => 'bg-pink-50',    'text' => 'text-pink-700',    'border' => 'border-pink-100'],
                    ['from' => 'from-cyan-500',    'to' => 'to-cyan-600',    'bg' => 'bg-cyan-50',    'text' => 'text-cyan-700',    'border' => 'border-cyan-100'],
                ];
                $idx = crc32($evaluation->id) % count($palette);
                $c = $palette[$idx];

                $statuts = [
                    'en_cours' => ['label' => 'En cours', 'dot' => 'bg-yellow-400'],
                    'terminee' => ['label' => 'Terminée', 'dot' => 'bg-emerald-400'],
                    'annulee'  => ['label' => 'Annulée',  'dot' => 'bg-slate-400'],
                ];
                $s = $statuts[$evaluation->statut] ?? ['label' => $evaluation->statut, 'dot' => 'bg-slate-400'];

                // Couleur du score
                $score = $evaluation->score_global ?? 0;
                if ($score >= 90)      $scoreColor = 'text-emerald-600';
                elseif ($score >= 75)  $scoreColor = 'text-blue-600';
                elseif ($score >= 60)  $scoreColor = 'text-yellow-600';
                else                   $scoreColor = 'text-red-600';
            @endphp

            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:border-slate-300 hover:-translate-y-1 transition-all duration-300 flex flex-col">

                {{-- Bandeau --}}
                <div class="relative h-24 bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }}">
                    <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                    <div class="absolute -bottom-8 -left-8 w-24 h-24 bg-white/10 rounded-full"></div>

                    {{-- Badge statut --}}
                    <div class="absolute top-3 right-3 z-10">
                        <span class="inline-flex items-center gap-1.5 bg-white/25 backdrop-blur-md border border-white/40 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                            <span class="relative flex h-1.5 w-1.5">
                                @if($evaluation->statut === 'en_cours')
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $s['dot'] }} opacity-75"></span>
                                @endif
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $s['dot'] }}"></span>
                            </span>
                            {{ $s['label'] }}
                        </span>
                    </div>

                    {{-- Type + Période --}}
                    <div class="absolute bottom-3 left-5">
                        <p class="text-white/80 text-[10px] font-semibold uppercase tracking-wider">Type</p>
                        <p class="text-white text-base font-extrabold leading-none">
                            {{ ucfirst($evaluation->type) }}
                            @if($evaluation->periode)
                                <span class="text-xs font-normal">· {{ $evaluation->periode }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Icône à cheval --}}
                <div class="relative">
                    <div class="absolute -top-7 right-5">
                        <div class="relative">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-md ring-4 ring-white flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                                <div class="w-full h-full rounded-2xl bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contenu --}}
                <div class="pt-10 px-5 pb-5 flex-1 flex flex-col">

                    {{-- Titre --}}
                    <h3 class="font-bold text-slate-900 text-base leading-tight mb-3 truncate">
                        {{ $evaluation->titre }}
                    </h3>

                    {{-- Employé --}}
                    <div class="flex items-center gap-2.5 mb-4 pb-4 border-b border-slate-100">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $c['from'] }} {{ $c['to'] }} flex items-center justify-center text-white font-bold text-[11px] shrink-0">
                            {{ $evaluation->employe?->initiales ?? '??' }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ $evaluation->employe?->nom_complet ?? 'Employé inconnu' }}
                            </p>
                            <p class="text-[10px] text-slate-400 truncate font-mono">
                                {{ $evaluation->employe?->matricule }}
                            </p>
                        </div>
                    </div>

                    {{-- Score + Appréciation --}}
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5 text-center">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Score</p>
                            <p class="text-xl font-extrabold {{ $scoreColor }} leading-none">
                                {{ $score > 0 ? number_format($score, 0) : '—' }}
                                @if($score > 0)
                                    <span class="text-[10px] text-slate-400">/100</span>
                                @endif
                            </p>
                        </div>
                        <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-xl p-2.5 flex flex-col justify-center">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-1">Appréciation</p>
                            <p class="text-[11px] font-bold {{ $c['text'] }} leading-tight truncate">
                                {{ $evaluation->niveauAppreciation?->nom ?? '—' }}
                            </p>
                        </div>
                    </div>

                    {{-- Date --}}
                    <div class="flex items-center gap-1.5 mb-4 text-xs text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-semibold text-slate-700">
                            {{ $evaluation->date_evaluation?->format('d/m/Y') ?? '—' }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1.5 mt-auto">
                        <a href="{{ route('gestion.evaluations.show', $evaluation) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Voir
                        </a>
                        <a href="{{ route('gestion.evaluations.edit', $evaluation) }}"
                            class="flex-1 inline-flex items-center justify-center gap-1 text-[10px] font-semibold {{ $c['text'] }} {{ $c['bg'] }} hover:brightness-95 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                        <form action="{{ route('gestion.evaluations.destroy', $evaluation) }}" method="POST"
                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette évaluation ?');"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-9 h-9 inline-flex items-center justify-center rounded-lg text-red-500 bg-red-50 hover:bg-red-100 transition"
                                title="Supprimer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <p class="text-slate-500 font-medium mb-1">Aucune évaluation trouvée</p>
                <p class="text-slate-400 text-sm mb-4">Commencez par créer une évaluation.</p>
                <a href="{{ route('gestion.evaluations.create') }}"
                    class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle évaluation
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $evaluations->links() }}
    </div>
</div>

@endsection