@extends('layouts.dashboard')

@section('title', 'Modifier ' . $employe->nom_complet)

@section('content')

<div class="max-w-3xl mx-auto px-4 py-8" x-data="{
    deptOpen: false,
    posteOpen: false,
    statutOpen: false,
    sexeOpen: false,
    selectedDept: '{{ old('departement_id', $employe->departement_id) }}',
    selectedPoste: '{{ old('poste_id', $employe->poste_id) }}',
    selectedStatut: '{{ old('statut', $employe->statut) }}',
    selectedSexe: '{{ old('sexe', $employe->sexe) }}',
    departements: {{ Js::from($departements->map(fn($d) => ['id' => (string)$d->id, 'nom' => $d->nom])->values()) }},
    postes: {{ Js::from($postes->map(fn($p) => ['id' => (string)$p->id, 'nom' => $p->nom])->values()) }},
    get selectedDeptName() {
        if (!this.selectedDept) return '';
        const d = this.departements.find(d => d.id == this.selectedDept);
        return d ? d.nom : '';
    },
    get selectedPosteName() {
        if (!this.selectedPoste) return '';
        const p = this.postes.find(p => p.id == this.selectedPoste);
        return p ? p.nom : '';
    },
    sexeOptions: [
        { id: 'Homme', nom: 'Homme' },
        { id: 'Femme', nom: 'Femme' }
    ],
    statutOptions: [
        { id: 'actif', nom: 'Actif' },
        { id: 'inactif', nom: 'Inactif' }
    ]
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.employes.index') }}" class="hover:text-purple-700 transition-colors">Employés</a>
        <span>/</span>
        <span class="text-purple-900 font-bold">Modifier</span>
    </div>

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">
        
        {{-- En-tête --}}
        <div class="px-8 pt-10 pb-6 text-center">
            <h1 class="text-3xl font-extrabold text-purple-700 tracking-wide uppercase">Modifier</h1>
            <p class="text-sm font-semibold text-slate-400 tracking-widest uppercase mt-1">Mise à jour</p>
            <p class="text-xs text-purple-500 mt-3 bg-purple-50 inline-block px-3 py-1 rounded-full border border-purple-100">
                {{ $employe->matricule }}
            </p>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('gestion.employes.update', $employe) }}" method="POST" class="px-8 pb-10 relative z-10">
            @csrf
            @method('PUT')

            {{-- Champs cachés pour la soumission --}}
            <input type="hidden" name="sexe" x-model="selectedSexe">
            <input type="hidden" name="departement_id" x-model="selectedDept">
            <input type="hidden" name="poste_id" x-model="selectedPoste">
            <input type="hidden" name="statut" x-model="selectedStatut">

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-100 text-red-600 rounded-2xl p-4 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                
                {{-- SECTION 1 : INFOS PERSONNELLES --}}
                <div>
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">1</span>
                        Informations personnelles
                    </h2>

                    <div class="space-y-3">
                        
                        {{-- MATRICULE DÉSACTIVÉ --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                            </div>
                            <input type="hidden" name="matricule" value="{{ old('matricule', $employe->matricule) }}">
                            <input type="text" disabled value="{{ $employe->matricule }}"
                                class="w-full pl-12 pr-12 py-3.5 bg-slate-100 border-2 border-dashed border-slate-300 text-slate-500 font-mono font-bold text-sm rounded-full cursor-not-allowed">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 pl-4 italic -mt-1">Matricule non modifiable</p>

                        {{-- Prénom & Nom --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input type="text" name="prenom" required value="{{ old('prenom', $employe->prenom) }}" placeholder="Prénom *"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                <input type="text" name="nom" required value="{{ old('nom', $employe->nom) }}" placeholder="Nom *"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                        </div>

                        {{-- SEXE DROPDOWN PRO --}}
                        <div class="relative" @click.away="sexeOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            
                            <button type="button" @click="sexeOpen = !sexeOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="sexeOptions.find(s => s.id === selectedSexe)?.nom || '— Sexe —'" :class="!selectedSexe ? 'text-purple-300' : 'font-medium'"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="sexeOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            {{-- Menu Déroulant Sexe --}}
                            <div x-show="sexeOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute z-50 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" 
                                 style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50">
                                    <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Sexe</p>
                                </div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="opt in sexeOptions" :key="opt.id">
                                        <li @click="selectedSexe = opt.id; sexeOpen = false"
                                            class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150"
                                            :class="selectedSexe === opt.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="opt.nom"></span>
                                            <div :class="selectedSexe === opt.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                                <svg x-show="selectedSexe === opt.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="email" name="email" required value="{{ old('email', $employe->email) }}" placeholder="Adresse email *"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>

                        {{-- Téléphone --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <input type="text" name="telephone" value="{{ old('telephone', $employe->telephone) }}" placeholder="Numéro de téléphone"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>

                        {{-- Dates --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="date_naissance" value="{{ old('date_naissance', $employe->date_naissance?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <input type="date" name="date_embauche" value="{{ old('date_embauche', $employe->date_embauche?->format('Y-m-d')) }}"
                                    class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                            </div>
                        </div>

                        {{-- Adresse --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <input type="text" name="adresse" value="{{ old('adresse', $employe->adresse) }}" placeholder="Adresse complète"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>
                    </div>
                </div>

                {{-- SECTION 2 : AFFECTATION --}}
                <div class="pt-2">
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">2</span>
                        Affectation
                    </h2>

                    <div class="space-y-3">
                        
                        {{-- DÉPARTEMENT DROPDOWN PRO --}}
                        <div class="relative" @click.away="deptOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                            </div>
                            
                            <button type="button" @click="deptOpen = !deptOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="selectedDeptName || '— Sélectionner un département * —'" :class="!selectedDeptName ? 'text-purple-300' : 'font-medium'"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="deptOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            <div x-show="deptOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute z-50 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" 
                                 style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50">
                                    <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Départements</p>
                                </div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="dept in departements" :key="dept.id">
                                        <li @click="selectedDept = dept.id; deptOpen = false"
                                            class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150"
                                            :class="selectedDept == dept.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="dept.nom"></span>
                                            <div :class="selectedDept == dept.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                                <svg x-show="selectedDept == dept.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="departements.length === 0" class="px-4 py-8 text-purple-300 italic text-center text-xs">Aucun département disponible</li>
                                </ul>
                            </div>
                        </div>
                        @error('departement_id')
                            <p class="text-red-500 text-xs pl-4 -mt-1">{{ $message }}</p>
                        @enderror

                        {{-- POSTE DROPDOWN PRO --}}
                        <div class="relative" @click.away="posteOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            
                            <button type="button" @click="posteOpen = !posteOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="selectedPosteName || '— Sélectionner un poste * —'" :class="!selectedPosteName ? 'text-purple-300' : 'font-medium'"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="posteOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            <div x-show="posteOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute z-40 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" 
                                 style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50">
                                    <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Postes disponibles</p>
                                </div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="poste in postes" :key="poste.id">
                                        <li @click="selectedPoste = poste.id; posteOpen = false"
                                            class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150"
                                            :class="selectedPoste == poste.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="poste.nom"></span>
                                            <div :class="selectedPoste == poste.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                                <svg x-show="selectedPoste == poste.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="postes.length === 0" class="px-4 py-8 text-purple-300 italic text-center text-xs">Aucun poste disponible</li>
                                </ul>
                            </div>
                        </div>
                        @error('poste_id')
                            <p class="text-red-500 text-xs pl-4 -mt-1">{{ $message }}</p>
                        @enderror

                        {{-- STATUT DROPDOWN PRO --}}
                        <div class="relative mt-2" @click.away="statutOpen = false">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>

                            <button type="button" @click="statutOpen = !statutOpen" 
                                class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                <span x-text="statutOptions.find(s => s.id === selectedStatut)?.nom || '— Sélectionner un statut * —'" class="font-medium"></span>
                                <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="statutOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </button>

                            <div x-show="statutOpen" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                 class="absolute z-30 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" 
                                 style="display: none;">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50">
                                    <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Statut de l'employé</p>
                                </div>
                                <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="opt in statutOptions" :key="opt.id">
                                        <li @click="selectedStatut = opt.id; statutOpen = false"
                                            class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150"
                                            :class="selectedStatut === opt.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold" x-text="opt.nom"></span>
                                            <div :class="selectedStatut === opt.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                                <svg x-show="selectedStatut === opt.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-6 pb-2 space-y-4">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold py-4 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5 outline-none focus:ring-4 focus:ring-purple-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        ENREGISTRER LES MODIFICATIONS
                    </button>
                    
                    <div class="flex items-center justify-between px-4 pt-2 border-t border-purple-100/50">
                        <a href="{{ route('gestion.employes.index') }}"
                            class="text-xs font-bold text-purple-400 hover:text-purple-600 py-2 transition-colors">
                            Annuler
                        </a>
                        <button type="button"
                            onclick="if(confirm('⚠️ Supprimer définitivement cet employé ?')) { document.getElementById('delete-form').submit(); }"
                            class="text-xs font-bold text-red-400 hover:text-red-600 py-2 transition-colors">
                            Supprimer l'employé
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
    <form id="delete-form" action="{{ route('gestion.employes.destroy', $employe) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

</div>

@endsection

{{-- CSS personnalisé pour la scrollbar --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
        margin: 8px 0;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #d8b4fe;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #a855f7;
    }
</style>