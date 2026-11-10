@extends('layouts.dashboard')

@section('title', 'Demande de congé')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.conges.index') }}" class="hover:text-purple-700 transition-colors">Congés</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">Demande</span>
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
            'en_attente' => ['label' => 'En attente', 'bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'ring' => 'ring-yellow-200', 'dot' => 'bg-yellow-500'],
            'approuve'   => ['label' => 'Approuvé',   'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'ring' => 'ring-emerald-200', 'dot' => 'bg-emerald-500'],
            'refuse'     => ['label' => 'Refusé',     'bg' => 'bg-red-50',     'text' => 'text-red-700',     'ring' => 'ring-red-200',     'dot' => 'bg-red-500'],
        ];
        $s = $statuts[$conge->statut] ?? ['label' => $conge->statut, 'bg' => 'bg-slate-50', 'text' => 'text-slate-700', 'ring' => 'ring-slate-200', 'dot' => 'bg-slate-400'];
    @endphp

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Avatar --}}
            <div class="mx-auto w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center text-white font-extrabold text-2xl">
                    {{ $conge->employe?->initiales ?? '??' }}
                </div>
            </div>

            <p class="mt-4 text-[10px] font-bold text-purple-400 uppercase tracking-widest">Demande de congé</p>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $conge->employe?->nom_complet ?? 'Employé inconnu' }}</h1>
            <p class="text-sm text-slate-500 font-mono">{{ $conge->employe?->matricule }}</p>

            {{-- Badge Statut --}}
            <div class="mt-3">
                <span class="inline-flex items-center gap-1.5 rounded-full {{ $s['bg'] }} px-3 py-1 text-xs font-bold {{ $s['text'] }} ring-1 ring-inset {{ $s['ring'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $s['dot'] }} @if($conge->statut === 'en_attente') animate-pulse @endif"></span>
                    {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section Détails --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Détails du congé</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                {{-- Type --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Type</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $conge->typeConge?->nom ?? '—' }}</p>
                </div>

                {{-- Durée --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Durée</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $conge->nombre_jours }} jour(s)</p>
                </div>

                {{-- Date début --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date de début</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $conge->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Date fin --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date de fin</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $conge->date_fin?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Motif --}}
                @if($conge->motif)
                    <div class="md:col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Motif</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $conge->motif }}</p>
                    </div>
                @endif
            </div>

            {{-- Section Validation --}}
            @if($conge->valide_par && $conge->valideur)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Validation</h3>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <p class="text-sm text-slate-700">
                        Validé par <strong class="text-slate-900">{{ $conge->valideur->name }}</strong>
                        le {{ $conge->date_validation?->format('d/m/Y à H:i') ?? '—' }}
                    </p>
                    @if($conge->commentaire_validation)
                        <p class="text-xs text-slate-500 mt-2 italic">"{{ $conge->commentaire_validation }}"</p>
                    @endif
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                <a href="{{ route('gestion.conges.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    @if($conge->statut === 'en_attente')
                        <form action="{{ route('gestion.conges.refuser', $conge) }}" method="POST" class="flex-1 md:flex-initial">
                            @csrf
                            <button type="submit" onclick="return confirm('Refuser ce congé ?');"
                                class="w-full inline-flex items-center justify-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 text-sm font-bold px-5 py-3 rounded-full transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Refuser
                            </button>
                        </form>

                        <form action="{{ route('gestion.conges.approuver', $conge) }}" method="POST" class="flex-1 md:flex-initial">
                            @csrf
                            <button type="submit" onclick="return confirm('Approuver ce congé ?');"
                                class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-5 py-3 rounded-full shadow-lg shadow-emerald-600/30 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Approuver
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Actions secondaires (Modifier / Supprimer) --}}
            <div class="mt-3 flex items-center justify-center md:justify-end gap-4">
                <a href="{{ route('gestion.conges.edit', $conge) }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 transition-colors">
                    Modifier la demande
                </a>
                <span class="text-slate-300">·</span>
                <form action="{{ route('gestion.conges.destroy', $conge) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Supprimer cette demande ?');" class="text-xs font-bold text-red-500 hover:text-red-600 transition-colors">
                        Supprimer la demande
                    </button>
                </form>
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