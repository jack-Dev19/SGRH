@extends('layouts.dashboard')

@section('title', 'Ajouter un document')

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8" x-data="{
    employeOpen: false,
    categorieOpen: false,
    moisOpen: false,
    selectedEmploye: '{{ old('employe_id', $selectedEmployeId ?? '') }}',
    selectedCategorie: '{{ old('categorie', 'photo_identite') }}',
    selectedMois: '{{ old('mois') }}',
    employesList: {{ Js::from($employes->map(fn($e) => ['id' => (string)$e->id, 'nom' => $e->nom_complet . ' (' . $e->matricule . ')'])->values()) }},
    categoriesList: [
        { id: 'photo_identite', nom: 'Photo d\'identité' },
        { id: 'photo_cin', nom: 'Photo CIN' },
        { id: 'contrat', nom: 'Contrat' },
        { id: 'fiche_paie', nom: 'Fiche de paie' },
        { id: 'diplome', nom: 'Diplôme' },
        { id: 'attestation', nom: 'Attestation' },
        { id: 'autre', nom: 'Autre' }
    ],
    moisList: [
        { id: '', nom: '—' }, { id: '1', nom: 'Janvier' }, { id: '2', nom: 'Février' }, { id: '3', nom: 'Mars' }, 
        { id: '4', nom: 'Avril' }, { id: '5', nom: 'Mai' }, { id: '6', nom: 'Juin' }, { id: '7', nom: 'Juillet' }, 
        { id: '8', nom: 'Août' }, { id: '9', nom: 'Septembre' }, { id: '10', nom: 'Octobre' }, { id: '11', nom: 'Novembre' }, { id: '12', nom: 'Décembre' }
    ],
    get selectedEmployeName() {
        if (!this.selectedEmploye) return '';
        const e = this.employesList.find(e => e.id == this.selectedEmploye);
        return e ? e.nom : '';
    },
    get selectedCategorieName() {
        const c = this.categoriesList.find(c => c.id === this.selectedCategorie);
        return c ? c.nom : '';
    }
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.documents.index') }}" class="hover:text-purple-700 transition-colors">Documents</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold">Nouveau</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">
        
        {{-- En-tête --}}
        <div class="px-8 pt-10 pb-6 text-center">
            <h1 class="text-3xl font-extrabold text-purple-700 tracking-wide uppercase">Ajouter</h1>
            <p class="text-sm font-semibold text-slate-400 tracking-widest uppercase mt-1">Un document</p>
            <p class="text-xs text-purple-500 mt-3 bg-purple-50 inline-block px-3 py-1 rounded-full border border-purple-100">
                Photo, CIN, contrat, fiche de paie…
            </p>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('gestion.documents.store') }}" method="POST" enctype="multipart/form-data" class="px-8 pb-10 relative z-10">
            @csrf
            <input type="hidden" name="employe_id" x-model="selectedEmploye">
            <input type="hidden" name="categorie" x-model="selectedCategorie">
            <input type="hidden" name="mois" x-model="selectedMois">

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
                {{-- SECTION 1 --}}
                <div>
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">1</span>
                        Détails du document
                    </h2>

                    <div class="space-y-3">
                        {{-- Employé --}}
                        <div class="relative" @click.away="employeOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <button type="button" @click="employeOpen = !employeOpen" class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="selectedEmployeName || '— Sélectionner un employé * —'" :class="!selectedEmployeName ? 'text-purple-300' : 'font-medium'"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none"><svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="employeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
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

                        {{-- Catégorie --}}
                        <div class="relative" @click.away="categorieOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            </div>
                            <button type="button" @click="categorieOpen = !categorieOpen" class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="selectedCategorieName || '— Catégorie * —'" class="font-medium"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none"><svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="categorieOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
                            </button>
                            <div x-show="categorieOpen" x-transition class="absolute z-40 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Catégories</p></div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="cat in categoriesList" :key="cat.id">
                                        <li @click="selectedCategorie = cat.id; categorieOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedCategorie === cat.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="cat.nom"></span>
                                            <div :class="selectedCategorie === cat.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedCategorie === cat.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Titre --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg></div>
                            <input type="text" name="titre" value="{{ old('titre') }}" required placeholder="Titre du document *" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">2</span>
                        Fichier
                    </h2>
                    <div class="space-y-3">
                        <input type="file" name="fichier" required accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm text-slate-500 file:mr-4 file:py-3.5 file:px-5 file:rounded-full file:border-0 file:bg-purple-600 file:text-white file:font-bold file:text-xs file:cursor-pointer hover:file:bg-purple-700 border-2 border-purple-200 rounded-full bg-white transition-all cursor-pointer focus:outline-none focus:ring-4 focus:ring-purple-500/20">
                        <p class="text-[11px] text-purple-400 pl-4">Formats : PDF, JPG, PNG, DOC, XLS. Taille max : 10 Mo.</p>
                    </div>
                </div>

                {{-- SECTION 3 --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">3</span>
                        Période & Détails
                    </h2>
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            {{-- Mois --}}
                            <div class="relative" @click.away="moisOpen = false">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <button type="button" @click="moisOpen = !moisOpen" class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                    <span x-text="moisList.find(m => m.id === selectedMois)?.nom || '— Mois (optionnel) —'" :class="!selectedMois ? 'text-purple-300' : 'font-medium'"></span>
                                    <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none"><svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="moisOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg></span>
                                </button>
                                <div x-show="moisOpen" x-transition class="absolute z-30 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" style="display: none;">
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50"><p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Mois</p></div>
                                    <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                        <template x-for="m in moisList" :key="m.id">
                                            <li @click="selectedMois = m.id; moisOpen = false" class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150" :class="selectedMois === m.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                                <span class="text-sm font-semibold" x-text="m.nom"></span>
                                                <div :class="selectedMois === m.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors"><svg x-show="selectedMois === m.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            {{-- Année --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                                <input type="number" name="annee" value="{{ old('annee') }}" min="2000" max="2100" placeholder="Année (optionnel)" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                        </div>
                        {{-- Description --}}
                        <div class="relative">
                            <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none"><svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg></div>
                            <textarea name="description" rows="3" placeholder="Description (optionnel)…" class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-3xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-6 pb-2 space-y-3">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold py-4 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5 outline-none focus:ring-4 focus:ring-purple-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        ENREGISTRER LE DOCUMENT
                    </button>
                    <a href="{{ route('gestion.documents.index') }}" class="block w-full text-center text-xs font-bold text-purple-400 hover:text-purple-600 py-2 transition-colors">Annuler et retourner à la liste</a>
                </div>
            </div>
        </form>

        {{-- Vagues --}}
        <div class="absolute bottom-0 left-0 right-0 pointer-events-none overflow-hidden h-40">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="w-full h-full">
                <path fill="rgba(167, 139, 250, 0.4)" d="M0,100 C150,150 350,50 500,100 L500,150 L0,150 Z"></path>
                <path fill="rgba(139, 92, 246, 0.8)" d="M0,120 C150,170 350,70 500,120 L500,150 L0,150 Z"></path>
                <path fill="#7C3AED" d="M0,140 C150,190 350,90 500,140 L500,150 L0,150 Z"></path>
            </svg>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; margin: 8px 0; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #d8b4fe; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #a855f7; }
</style>

@endsection