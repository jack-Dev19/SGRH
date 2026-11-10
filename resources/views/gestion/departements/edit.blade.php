@extends('layouts.dashboard')

@section('title', 'Modifier — ' . $departement->nom)

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8" x-data="{
    respOpen: false,
    statutOpen: false,
    selectedResp: '{{ old('responsable_id', $departement->responsable_id) }}',
    selectedStatut: '{{ old('statut', $departement->statut) }}',
    chefsList: {{ Js::from($chefs->map(fn($c) => ['id' => (string)$c->id, 'nom' => $c->name . ' (' . $c->email . ')'])->values()) }},
    statutsList: [
        { id: '1', nom: 'Actif' },
        { id: '0', nom: 'Inactif' }
    ],
    get selectedRespName() {
        if (!this.selectedResp) return '';
        const c = this.chefsList.find(c => c.id == this.selectedResp);
        return c ? c.nom : '';
    }
}">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.departements.index') }}" class="hover:text-purple-700 transition-colors">Départements</a>
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
                {{ $departement->nom }}
            </p>
        </div>

        {{-- Formulaire --}}
        <form action="{{ route('gestion.departements.update', $departement) }}" method="POST" class="px-8 pb-10 relative z-10">
            @csrf
            @method('PUT')

            {{-- Champs cachés pour la soumission --}}
            <input type="hidden" name="responsable_id" x-model="selectedResp">
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
                
                {{-- SECTION 1 : INFORMATIONS DU DÉPARTEMENT --}}
                <div>
                    <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">1</span>
                        Informations du département
                    </h2>

                    <div class="space-y-3">
                        {{-- Nom --}}
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                            </div>
                            <input type="text" name="nom" value="{{ old('nom', $departement->nom) }}" required placeholder="Nom du département *"
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none">
                        </div>

                        {{-- Description --}}
                        <div class="relative">
                            <div class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <textarea name="description" rows="3" placeholder="Description du département..."
                                class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-purple-200 text-purple-900 text-sm rounded-3xl focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 placeholder-purple-300 transition-all outline-none resize-none">{{ old('description', $departement->description) }}</textarea>
                        </div>

                        {{-- Responsable + Statut (DROPDOWN PRO) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            
                            {{-- RESPONSABLE DROPDOWN PRO --}}
                            <div class="relative" @click.away="respOpen = false">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                                
                                <button type="button" @click="respOpen = !respOpen" 
                                    class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                    <span x-text="selectedRespName || '— Responsable —'" :class="!selectedRespName ? 'text-purple-300' : 'font-medium'"></span>
                                    <span class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-purple-500 transition-transform duration-200" :class="respOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </span>
                                </button>

                                <div x-show="respOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                     class="absolute z-50 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" 
                                     style="display: none;">
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50">
                                        <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Responsables</p>
                                    </div>
                                    <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                        <li @click="selectedResp = ''; respOpen = false"
                                            class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150"
                                            :class="!selectedResp ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                            <span class="text-sm font-semibold">Aucun</span>
                                            <div :class="!selectedResp ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                                <svg x-show="!selectedResp" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </li>
                                        <template x-for="chef in chefsList" :key="chef.id">
                                            <li @click="selectedResp = chef.id; respOpen = false"
                                                class="group flex items-center justify-between px-4 py-2.5 rounded-2xl cursor-pointer transition-all duration-150"
                                                :class="selectedResp == chef.id ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'hover:bg-purple-50 text-purple-900'">
                                                <span class="text-sm font-semibold" x-text="chef.nom"></span>
                                                <div :class="selectedResp == chef.id ? 'bg-white/20' : 'bg-transparent group-hover:bg-purple-100'" class="w-6 h-6 rounded-full flex items-center justify-center transition-colors">
                                                    <svg x-show="selectedResp == chef.id" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            {{-- STATUT DROPDOWN PRO --}}
                            <div class="relative" @click.away="statutOpen = false">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>

                                <button type="button" @click="statutOpen = !statutOpen" 
                                    class="relative w-full pl-12 pr-10 py-3.5 bg-white border-2 border-purple-200 text-left text-purple-900 text-sm rounded-full focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all hover:border-purple-300">
                                    <span x-text="statutsList.find(s => s.id === selectedStatut)?.nom || '— Statut —'" class="font-medium"></span>
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
                                     class="absolute z-40 mt-2 w-full bg-white rounded-3xl shadow-[0_25px_60px_-15px_rgba(147,51,234,0.5)] ring-1 ring-purple-100 overflow-hidden" 
                                     style="display: none;">
                                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-5 py-3 border-b border-purple-100/50">
                                        <p class="text-[10px] font-black text-purple-400 uppercase tracking-widest">Statut du département</p>
                                    </div>
                                    <ul class="max-h-64 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                        <template x-for="opt in statutsList" :key="opt.id">
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
                </div>

                {{-- SECTION 2 : POSTES ASSOCIÉS --}}
                <div class="pt-2">
                    <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
                        <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px]">2</span>
                            Postes associés
                        </h2>
                        <button type="button" onclick="ajouterPoste()"
                            class="inline-flex items-center gap-1.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-full transition shadow-md shadow-emerald-500/20 hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Ajouter un poste
                        </button>
                    </div>
                    <p class="text-[11px] text-purple-400 mb-3">
                        Les postes utilisés par des employés seront désactivés au lieu d'être supprimés.
                    </p>

                    {{-- Liste des postes existants --}}
                    <div id="postes-container" class="space-y-2">
                        @foreach($departement->postes as $i => $poste)
                            <div class="poste-item bg-white rounded-full p-1.5 flex items-center gap-2 border-2 border-purple-200 shadow-sm hover:border-purple-300 transition-colors">
                                <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs shrink-0">
                                    <span class="poste-number">{{ $i + 1 }}</span>
                                </div>
                                <input type="hidden" name="postes[{{ $i }}][id]" value="{{ $poste->id }}">
                                <input type="text" name="postes[{{ $i }}][nom]" value="{{ $poste->nom }}"
                                    class="flex-1 px-2 py-2 text-sm bg-transparent text-purple-900 focus:outline-none">

                                {{-- Badge si poste utilisé --}}
                                @if($poste->employes()->count() > 0)
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 shrink-0 ring-1 ring-inset ring-amber-200"
                                        title="{{ $poste->employes()->count() }} employé(s) utilisent ce poste">
                                        {{ $poste->employes()->count() }} utilis.
                                    </span>
                                @endif

                                <button type="button" onclick="supprimerPoste(this)" title="Supprimer"
                                    class="p-2 rounded-full text-red-500 hover:bg-red-50 transition shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    {{-- État vide --}}
                    <div id="postes-empty" class="{{ $departement->postes->isEmpty() ? '' : 'hidden' }} text-center py-8 bg-purple-50/50 rounded-3xl border-2 border-dashed border-purple-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-purple-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-purple-400 font-medium">Aucun poste associé</p>
                        <p class="text-xs text-purple-300 mt-1">Cliquez sur "Ajouter un poste" pour commencer</p>
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
                    
                    <a href="{{ route('gestion.departements.index') }}"
                        class="block w-full text-center text-xs font-bold text-purple-400 hover:text-purple-600 py-2 transition-colors">
                        Annuler et retourner à la liste
                    </a>
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
</div>

{{-- Template pour un nouveau poste --}}
<template id="poste-template">
    <div class="poste-item bg-white rounded-full p-1.5 flex items-center gap-2 border-2 border-purple-200 shadow-sm hover:border-purple-300 transition-colors">
        <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs shrink-0">
            <span class="poste-number">#</span>
        </div>
        <input type="text" name="postes[__INDEX__][nom]" placeholder="Nom du poste"
            class="flex-1 px-2 py-2 text-sm bg-transparent text-purple-900 placeholder-purple-300 focus:outline-none">
        <button type="button" onclick="supprimerPoste(this)" title="Supprimer"
            class="p-2 rounded-full text-red-500 hover:bg-red-50 transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</template>

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

@endsection

@push('scripts')
<script>
    let posteIndex = {{ $departement->postes->count() }};

    function ajouterPoste() {
        const container = document.getElementById('postes-container');
        const empty     = document.getElementById('postes-empty');
        const template  = document.getElementById('poste-template');

        empty.classList.add('hidden');

        const html = template.innerHTML.replace(/__INDEX__/g, posteIndex);
        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        const item = wrap.firstChild;

        container.appendChild(item);
        posteIndex++;

        item.querySelector('input').focus();
        mettreAJourNumeros();
    }

    function supprimerPoste(btn) {
        const item = btn.closest('.poste-item');
        const idInput = item.querySelector('input[type="hidden"]');

        // Si c'est un poste existant utilisé, avertir
        if (idInput) {
            const badge = item.querySelector('.bg-amber-100');
            if (badge && !confirm('Ce poste est utilisé par des employés. Il sera désactivé au lieu d\'être supprimé. Continuer ?')) {
                return;
            }
        }

        item.remove();
        mettreAJourNumeros();

        const container = document.getElementById('postes-container');
        if (container.children.length === 0) {
            document.getElementById('postes-empty').classList.remove('hidden');
        }
    }

    function mettreAJourNumeros() {
        document.querySelectorAll('.poste-item').forEach((item, index) => {
            item.querySelector('.poste-number').textContent = index + 1;
        });
    }
</script>
@endpush