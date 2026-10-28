@extends('layouts.dashboard')

@section('title', 'Modifier évaluation')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8" x-data="{
    employeOpen: false,
    typeOpen: false,
    niveauOpen: false,
    statutOpen: false,
    selectedEmploye: '{{ old('employe_id', $evaluation->employe_id) }}',
    selectedType: '{{ old('type', $evaluation->type) }}',
    selectedNiveau: '{{ old('niveau_appreciation_id', $evaluation->niveau_appreciation_id) }}',
    selectedStatut: '{{ old('statut', $evaluation->statut) }}',
    employesList: {{ Js::from($employes->map(fn($e) => ['id' => (string)$e->id, 'nom' => $e->nom_complet . ' (' . $e->matricule . ')'])->values()) }},
    niveauxList: {{ Js::from($niveaux->map(fn($n) => ['id' => (string)$n->id, 'nom' => $n->nom])->values()) }},
    typesList: [
        { id: 'annuelle', nom: 'Annuelle' },
        { id: 'trimestrielle', nom: 'Trimestrielle' },
        { id: 'probatoire', nom: 'Probatoire' },
        { id: 'ponctuelle', nom: 'Ponctuelle' }
    ],
    statutsList: [
        { id: 'en_cours', nom: 'En cours' },
        { id: 'terminee', nom: 'Terminée' },
        { id: 'annulee', nom: 'Annulée' }
    ],
    get selectedEmployeName() {
        if (!this.selectedEmploye) return '';
        const e = this.employesList.find(e => e.id == this.selectedEmploye);
        return e ? e.nom : '';
    }
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.evaluations.index') }}" class="hover:text-purple-700 transition-colors">Évaluations</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold">Modifier</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">
        
        {{-- En-tête --}}
        <div class="px-8 pt-10 pb-6 text-center">
            <h1 class="text-3xl font-extrabold text-purple-700 tracking-wide uppercase">Modifier</h1>
            <p class="text-sm font-semibold text-slate-400 tracking-widest uppercase mt-1">L'évaluation</p>
            <p class="text-xs text-purple-500 mt-3 bg-purple-50 inline-block px-3 py-1 rounded-full border border-purple-100">
                {{ $evaluation->titre }} · {{ $evaluation->employe?->nom_complet ?? 'Employé' }}
            </p>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('gestion.evaluations.update', $evaluation) }}" method="POST" class="px-8 pb-10 relative z-10">
            @csrf
            @method('PUT')

            {{-- Champs cachés pour la soumission --}}
            <input type="hidden" name="employe_id" x-model="selectedEmploye">
            <input type="hidden" name="type" x-model="selectedType">
            <input type="hidden" name="niveau_appreciation_id" x-model="selectedNiveau">
            <input type="hidden" name="statut" x-model="selectedStatut">

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-100 text-red-600 rounded-2xl p-4 text-sm">
                    <p class="font-bold mb-1">Erreurs :</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                
                {{-- SECTION 1 : INFORMATIONS PRINCIPALES --}}
                <div>
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">1</span>
                        Informations principales
                    </h2>

                    <div class="space-y-3">
                        
                        {{-- ⭐ EMPLOYÉ DROPDOWN PRO ⭐ --}}
                        <div class="relative" @click.away="employeOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            
                            <button type="button" @click="employeOpen = !employeOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="selectedEmployeName || '— Sélectionner un employé * —'" :class="!selectedEmployeName ? 'text-purple-300' : 'font-medium'"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="employeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            <div x-show="employeOpen" x-transition class="absolute z-50 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Employés</p></div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="emp in employesList" :key="emp.id">
                                        <li @click="selectedEmploye = emp.id; employeOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedEmploye == emp.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="emp.nom"></span>
                                            <div :class="selectedEmploye == emp.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedEmploye == emp.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Titre --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <input type="text" name="titre" required value="{{ old('titre', $evaluation->titre) }}" placeholder="Titre *"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>

                        {{-- Type + Période --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            
                            {{-- ⭐ TYPE DROPDOWN PRO ⭐ --}}
                            <div class="relative" @click.away="typeOpen = false">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                </div>
                                
                                <button type="button" @click="typeOpen = !typeOpen" 
                                    class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                    <span x-text="typesList.find(t => t.id === selectedType)?.nom || 'Type *'" class="font-medium"></span>
                                    <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="typeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </button>

                                <div x-show="typeOpen" x-transition class="absolute z-40 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Type d'évaluation</p></div>
                                    <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                        <template x-for="t in typesList" :key="t.id">
                                            <li @click="selectedType = t.id; typeOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedType === t.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                                <span class="text-sm font-semibold" x-text="t.nom"></span>
                                                <div :class="selectedType === t.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedType === t.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            {{-- Période --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="text" name="periode" value="{{ old('periode', $evaluation->periode) }}" placeholder="Période"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 : PÉRIODE & SCORE --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">2</span>
                        Période & Score
                    </h2>

                    <div class="space-y-3">
                        {{-- Dates --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <input type="date" name="date_debut" value="{{ old('date_debut', $evaluation->date_debut?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <input type="date" name="date_fin" value="{{ old('date_fin', $evaluation->date_fin?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <input type="date" name="date_evaluation" required value="{{ old('date_evaluation', $evaluation->date_evaluation?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            </div>
                        </div>

                        {{-- Score + Niveau --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></div>
                                <input type="number" name="score_global" min="0" max="100" step="0.01" value="{{ old('score_global', $evaluation->score_global) }}" placeholder="Score /100"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                            
                            {{-- ⭐ NIVEAU DROPDOWN PRO ⭐ --}}
                            <div class="relative" @click.away="niveauOpen = false">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                </div>
                                
                                <button type="button" @click="niveauOpen = !niveauOpen" 
                                    class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                    <span x-text="niveauxList.find(n => n.id === selectedNiveau)?.nom || '— Niveau —'" :class="!selectedNiveau ? 'text-purple-300' : 'font-medium'"></span>
                                    <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="niveauOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </button>

                                <div x-show="niveauOpen" x-transition class="absolute z-30 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Niveau d'appréciation</p></div>
                                    <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                        <li @click="selectedNiveau = ''; niveauOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="!selectedNiveau ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold">Aucun</span>
                                            <div :class="!selectedNiveau ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="!selectedNiveau" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                        </li>
                                        <template x-for="n in niveauxList" :key="n.id">
                                            <li @click="selectedNiveau = n.id; niveauOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedNiveau === n.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                                <span class="text-sm font-semibold" x-text="n.nom"></span>
                                                <div :class="selectedNiveau === n.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedNiveau === n.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Appréciation --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <input type="text" name="appreciation" value="{{ old('appreciation', $evaluation->appreciation) }}" placeholder="Appréciation"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>
                    </div>
                </div>

                {{-- SECTION 3 : STATUT & COMMENTAIRES --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">3</span>
                        Statut & Commentaires
                    </h2>

                    <div class="space-y-3">
                        
                        {{-- ⭐ STATUT DROPDOWN PRO ⭐ --}}
                        <div class="relative" @click.away="statutOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            
                            <button type="button" @click="statutOpen = !statutOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="statutsList.find(s => s.id === selectedStatut)?.nom || 'Statut *'" class="font-medium"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="statutOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            <div x-show="statutOpen" x-transition class="absolute z-30 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Statut de l'évaluation</p></div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="s in statutsList" :key="s.id">
                                        <li @click="selectedStatut = s.id; statutOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedStatut === s.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="s.nom"></span>
                                            <div :class="selectedStatut === s.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedStatut === s.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Commentaire --}}
                        <div class="relative">
                            <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <textarea name="commentaire" rows="2" placeholder="Commentaire (optionnel)…"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-3xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none resize-none">{{ old('commentaire', $evaluation->commentaire) }}</textarea>
                        </div>

                        {{-- Recommandation --}}
                        <div class="relative">
                            <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <textarea name="recommandation" rows="2" placeholder="Recommandation (optionnel)…"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-3xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none resize-none">{{ old('recommandation', $evaluation->recommandation) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-6 pb-2 space-y-3">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold py-4 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5 outline-none focus:ring-4 focus:ring-purple-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        ENREGISTRER LES MODIFICATIONS
                    </button>

                    <div class="flex items-center justify-between px-4 pt-2 border-t border-purple-100/50">
                        <a href="{{ route('gestion.evaluations.index') }}" class="text-xs font-bold text-purple-400 hover:text-purple-600 py-2 transition-colors">
                            Annuler
                        </a>
                        <button type="button"
                            onclick="if(confirm('⚠️ Supprimer définitivement cette évaluation ?')) { document.getElementById('delete-form').submit(); }"
                            class="text-xs font-bold text-red-400 hover:text-red-600 py-2 transition-colors">
                            Supprimer l'évaluation
                        </button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Vagues décoratives --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-40">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                <path fill="rgba(167, 139, 250, 0.4)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                <path fill="rgba(139, 92, 246, 0.8)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                <path fill="#7C3AED" d="M0,140 C150,190 350,90 500,140 L500,150 L0,150 Z"></path>
            </svg>
        </div>
    </div>

    {{-- Formulaire de suppression caché --}}
    <form id="delete-form" action="{{ route('gestion.evaluations.destroy', $evaluation) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d8b4fe; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #a855f7; }
</style>

@endsection