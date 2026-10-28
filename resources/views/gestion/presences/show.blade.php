@extends('layouts.dashboard')

@section('title', 'Présence du ' . $presence->date?->format('d/m/Y'))

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.presences.index') }}" class="hover:text-purple-700 transition-colors">Présences</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">{{ $presence->date?->format('d/m/Y') }}</span>
    </div>

    @php
        $statuts = [
            'present' => ['label' => 'Présent', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-200', 'dot' => 'bg-emerald-500'],
            'absent'  => ['label' => 'Absent',  'bg' => 'bg-red-50',     'text' => 'text-red-700',     'ring' => 'ring-red-200',     'dot' => 'bg-red-500'],
            'retard'  => ['label' => 'Retard',  'bg' => 'bg-orange-50',  'text' => 'text-orange-700',  'ring' => 'ring-orange-200',  'dot' => 'bg-orange-500'],
            'conge'   => ['label' => 'Congé',   'bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'ring' => 'ring-blue-200',    'dot' => 'bg-blue-500'],
        ];
        $s = $statuts[$presence->statut] ?? ['label' => $presence->statut, 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'ring' => 'ring-slate-200', 'dot' => 'bg-slate-400'];
    @endphp

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Icône --}}
            <div class="mx-auto w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-[10px] font-bold text-purple-400 uppercase tracking-widest">Présence du</p>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $presence->date?->format('d/m/Y') ?? '—' }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $presence->employe?->nom_complet ?? 'Employé inconnu' }}</p>

            {{-- Badge Statut --}}
            <div class="mt-3">
                <span class="inline-flex items-center gap-1.5 rounded-full {{ $s['bg'] }} px-3 py-1 text-xs font-bold {{ $s['text'] }} ring-1 ring-inset {{ $s['ring'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $s['dot'] }} @if($presence->statut === 'present') animate-pulse @endif"></span>
                    {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section Employé --}}
            @if($presence->employe)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Employé</h3>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ $presence->employe->initiales }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $presence->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $presence->employe->matricule }}
                                @if($presence->employe->poste) · {{ $presence->employe->poste->nom }} @endif
                            </p>
                        </div>
                        <a href="{{ route('gestion.employes.show', $presence->employe) }}"
                            class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:text-purple-700 bg-white px-3 py-2 rounded-full shadow-sm transition">
                            Voir
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endif

            {{-- Section Détails --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Détails de la présence</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                {{-- Heure entrée --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Heure d'entrée</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $presence->heure_entree ? substr($presence->heure_entree, 0, 5) : '—' }}</p>
                </div>

                {{-- Heure sortie --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Heure de sortie</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $presence->heure_sortie ? substr($presence->heure_sortie, 0, 5) : '—' }}</p>
                </div>

                {{-- Heures travaillées --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Heures travaillées</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $presence->heures_travaillees ? number_format($presence->heures_travaillees, 1) . ' h' : '—' }}</p>
                </div>

                {{-- Retard --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Retard</p></div>
                    <p class="text-sm font-bold {{ $presence->retard_minutes > 0 ? 'text-orange-600' : 'text-slate-800' }}">
                        {{ $presence->retard_minutes > 0 ? $presence->retard_minutes . ' minutes' : 'Aucun' }}
                    </p>
                </div>

                {{-- Commentaire --}}
                @if($presence->commentaire)
                    <div class="md:col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Commentaire</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $presence->commentaire }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                <a href="{{ route('gestion.presences.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Retour à la liste
                </a>

                <a href="{{ route('gestion.presences.edit', $presence) }}"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier la présence
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