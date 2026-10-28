@extends('layouts.dashboard')

@section('title', $departement->nom)

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.departements.index') }}" class="hover:text-purple-700 transition-colors">Départements</a>
        <span>/</span>
        <span class="text-purple-900 font-bold truncate">{{ $departement->nom }}</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Icône département --}}
            <div class="mx-auto w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight">{{ $departement->nom }}</h1>
            <p class="text-sm text-slate-500 mt-1 max-w-lg mx-auto">{{ $departement->description ?: 'Aucune description disponible.' }}</p>

            {{-- Badge Statut --}}
            <div class="mt-4 flex items-center justify-center gap-2 flex-wrap">
                @if($departement->statut == 1)
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
            
            {{-- Section Statistiques --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Statistiques</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-8">
                {{-- Employés --}}
                <div class="rounded-2xl bg-blue-50 border border-blue-100 p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-blue-700 leading-none">{{ $departement->employes_count }}</p>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mt-1">Employés</p>
                    </div>
                </div>

                {{-- Postes --}}
                <div class="rounded-2xl bg-purple-50 border border-purple-100 p-4 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-purple-700 leading-none">{{ $departement->postes_count ?? 0 }}</p>
                        <p class="text-[10px] font-bold text-purple-500 uppercase tracking-wider mt-1">Postes</p>
                    </div>
                </div>

                {{-- Responsable --}}
                <div class="md:col-span-1 rounded-2xl bg-slate-50 border border-slate-100 p-4 flex items-center gap-4">
                    @if($departement->responsable)
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr($departement->responsable->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $departement->responsable->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Responsable</p>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                            <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm italic text-slate-400">Non assigné</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Responsable</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section Employés --}}
            @if($departement->employes->isNotEmpty())
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                        <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Membres de l'équipe</h3>
                    </div>
                    <span class="text-xs font-bold text-purple-500 bg-purple-50 px-2.5 py-1 rounded-full">
                        {{ $departement->employes->count() }}
                    </span>
                </div>

                <div class="space-y-2 mb-8">
                    @foreach($departement->employes as $emp)
                        <div class="flex items-center gap-3 p-3 bg-purple-50/40 rounded-2xl border border-purple-100/50 hover:bg-purple-50 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                {{ $emp->initiales }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $emp->nom_complet }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $emp->poste?->nom ?? 'Poste non défini' }}</p>
                            </div>
                            @if($emp->matricule)
                                <span class="text-[10px] font-mono font-medium text-slate-500 bg-white px-2 py-1 rounded-md ring-1 ring-inset ring-slate-200 hidden sm:inline-block shrink-0">
                                    {{ $emp->matricule }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                
                <a href="{{ route('gestion.departements.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    <a href="{{ route('gestion.departements.edit', $departement) }}"
                        class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier le département
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