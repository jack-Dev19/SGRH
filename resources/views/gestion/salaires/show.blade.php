@extends('layouts.dashboard')

@section('title', 'Salaire')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.salaires.index') }}" class="hover:text-purple-700 transition-colors">Salaires</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">{{ number_format($salaire->salaire_base, 0, ',', ' ') }} MGA</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Icône --}}
            <div class="mx-auto w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <p class="mt-4 text-[10px] font-bold text-purple-400 uppercase tracking-widest">Salaire de base</p>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($salaire->salaire_base, 0, ',', ' ') }} <span class="text-lg text-slate-500 font-bold">MGA</span></h1>
            <p class="text-sm text-slate-500 mt-1">{{ $salaire->employe?->nom_complet ?? 'Employé inconnu' }}</p>

            {{-- Badge Statut --}}
            <div class="mt-3">
                @if($salaire->statut === 'actif')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Actif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600 ring-1 ring-inset ring-slate-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                        Inactif
                    </span>
                @endif
            </div>
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section Employé --}}
            @if($salaire->employe)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Employé</h3>
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ $salaire->employe->initiales }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $salaire->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $salaire->employe->matricule }}
                                @if($salaire->employe->poste) · {{ $salaire->employe->poste->nom }} @endif
                            </p>
                        </div>
                        <a href="{{ route('gestion.employes.show', $salaire->employe) }}"
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
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Détails du salaire</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                {{-- Date début --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date de début</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ $salaire->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Date fin --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date de fin</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $salaire->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}</p>
                </div>

                {{-- Motif --}}
                @if($salaire->motif)
                    <div class="md:col-span-2 rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                        <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Motif</p></div>
                        <p class="text-sm text-slate-700">{{ $salaire->motif }}</p>
                    </div>
                @endif

                {{-- Commentaire --}}
                @if($salaire->commentaire)
                    <div class="md:col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Commentaire</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $salaire->commentaire }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                <a href="{{ route('gestion.salaires.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Retour à la liste
                </a>

                <a href="{{ route('gestion.salaires.edit', $salaire) }}"
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Modifier le salaire
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