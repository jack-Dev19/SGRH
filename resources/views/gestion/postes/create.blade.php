@extends('layouts.dashboard')

@section('title', 'Nouveau poste')

@section('content')

<div class="max-w-6xl mx-auto py-4">

    {{-- Fil d'Ariane --}}
    <a href="{{ route('gestion.postes.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux postes
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- CARTE ILLUSTRATION --}}
        <div class="relative bg-purple-600 rounded-[2rem] shadow-2xl shadow-purple-500/30 overflow-hidden min-h-[640px] flex flex-col lg:sticky lg:top-24">
            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Nouveau Poste</h2>
                <p class="text-sm text-purple-200 uppercase tracking-widest">Créer une fonction</p>
            </div>

            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center w-full">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">Créer un poste</p>
                    <p class="text-purple-100 text-sm leading-relaxed max-w-xs mx-auto mb-6">
                        Définissez une nouvelle fonction au sein de votre organisation.
                    </p>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.3"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#d8b4fe" opacity="0.5"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-purple-500/20 overflow-hidden min-h-[640px] flex flex-col">
            <form action="{{ route('gestion.postes.store') }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf

                <div class="text-center mb-6">
                    <h2 class="text-3xl font-extrabold text-purple-700 uppercase tracking-wider mb-2">Créer</h2>
                    <p class="text-sm text-purple-400 uppercase tracking-widest">Un poste</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-2xl mb-5">
                        @foreach ($errors->all() as $error) <p>• {{ $error }}</p> @endforeach
                    </div>
                @endif

                                
                <div class="space-y-4">
                
                    {{-- Nom du poste : obligatoire --}}
                    <div>
                        <label for="nom" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nom du poste <span class="text-red-500">*</span>
                        </label>
                    
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                        
                            <input
                                type="text"
                                id="nom"
                                name="nom"
                                value="{{ old('nom') }}"
                                required
                                maxlength="100"
                                placeholder="Ex. : Développeur web"
                                aria-describedby="nom-error"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition @error('nom') border border-red-500 @enderror"
                            >
                        </div>
                    
                        @error('nom')
                            <p id="nom-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                
                    {{-- Département : obligatoire --}}
                    <div>
                        <label for="departement_id" class="block text-sm font-semibold text-slate-700 mb-2">
                            Département <span class="text-red-500">*</span>
                        </label>
                    
                        <select
                            id="departement_id"
                            name="departement_id"
                            required
                            aria-describedby="departement_id-error"
                            class="w-full px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition @error('departement_id') border border-red-500 @enderror"
                        >
                            <option value="">— Sélectionner un département —</option>
                    
                            @foreach ($departements as $dept)
                                <option value="{{ $dept->id }}" @selected(old('departement_id') == $dept->id)>
                                    {{ $dept->nom }}
                                </option>
                            @endforeach
                        </select>
                    
                        @error('departement_id')
                            <p id="departement_id-error" class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                
                    {{-- Niveau du poste : facultatif --}}
                    <div>
                        <label for="niveau" class="block text-sm font-semibold text-slate-700 mb-2">
                            Niveau du poste
                        </label>
                    
                        <input
                            type="text"
                            id="niveau"
                            name="niveau"
                            value="{{ old('niveau') }}"
                            maxlength="30"
                            placeholder="Ex. : Junior, Confirmé, Senior"
                            class="w-full px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition"
                        >
                    
                        @error('niveau')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                
                    {{-- Fourchette salariale --}}
                    <div>
                        <p class="text-sm font-semibold text-slate-700 mb-2">Fourchette salariale</p>
                    
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="salaire_min" class="block text-sm text-slate-600 mb-2">
                                    Salaire minimum (Ar)
                                </label>
                            
                                <input
                                    type="number"
                                    id="salaire_min"
                                    name="salaire_min"
                                    min="0"
                                    step="1000"
                                    value="{{ old('salaire_min') }}"
                                    placeholder="Ex. : 500000"
                                    class="w-full px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition"
                                >
                            
                                @error('salaire_min')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        
                            <div>
                                <label for="salaire_max" class="block text-sm text-slate-600 mb-2">
                                    Salaire maximum (Ar)
                                </label>
                            
                                <input
                                    type="number"
                                    id="salaire_max"
                                    name="salaire_max"
                                    min="0"
                                    step="1000"
                                    value="{{ old('salaire_max') }}"
                                    placeholder="Ex. : 1200000"
                                    class="w-full px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition"
                                >
                            
                                @error('salaire_max')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                
                    {{-- Description : facultative --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                            Description du poste
                        </label>
                    
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Décrivez les missions et responsabilités du poste..."
                            class="w-full px-4 py-3.5 rounded-2xl bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition resize-y"
                        >{{ old('description') }}</textarea>
                    
                        @error('description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                
                    {{-- Statut : menu personnalisé --}}
                    <div>
                        <span id="statut-label" class="block text-sm font-semibold text-slate-700 mb-2">
                            Statut <span class="text-red-500">*</span>
                        </span>
                    
                        <div class="relative" data-dropdown="statut">
                            <input
                                type="hidden"
                                name="statut"
                                id="input-statut"
                                value="{{ old('statut', 'actif') }}"
                            >
                        
                            <button
                                type="button"
                                id="dropdown-statut-btn"
                                aria-labelledby="statut-label dropdown-statut-label"
                                aria-haspopup="listbox"
                                aria-expanded="false"
                                class="w-full inline-flex items-center justify-between gap-2 px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition"
                            >
                                <span id="dropdown-statut-label" class="truncate text-left">
                                    {{ old('statut', 'actif') === 'inactif' ? 'Inactif' : 'Actif' }}
                                </span>
                            
                                <svg id="dropdown-statut-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                        
                            <div id="dropdown-statut-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50">
                                <button type="button" onclick="selectDropdown('statut', 'actif', 'Actif')" class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    Actif
                                </button>
                            
                                <button type="button" onclick="selectDropdown('statut', 'inactif', 'Inactif')" class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition border-t border-slate-100">
                                    Inactif
                                </button>
                            </div>
                        </div>
                    
                        @error('statut')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                
                </div>


                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold uppercase tracking-wider hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300/50 transition shadow-lg shadow-purple-500/30 transform hover:-translate-y-0.5">
                    Enregistrer le poste
                </button>
            </form>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.6"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#9333ea" opacity="0.8"/></svg>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    window.selectDropdown = function(type, value, label) {
        document.getElementById('input-' + type).value = value;
        document.getElementById('dropdown-' + type + '-label').textContent = label;
        document.getElementById('dropdown-' + type + '-menu').classList.add('hidden');
        document.getElementById('dropdown-' + type + '-chevron').style.transform = 'rotate(0deg)';
    };
    window.toggleDropdown = function(type) {
        const menu = document.getElementById('dropdown-' + type + '-menu');
        const chevron = document.getElementById('dropdown-' + type + '-chevron');
        const isHidden = menu.classList.contains('hidden');
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        if (isHidden) { menu.classList.remove('hidden'); chevron.style.transform = 'rotate(180deg)'; }
    };
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('dropdown-statut-btn');
        if (btn) btn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown('statut'); });
        document.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        });
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.addEventListener('click', e => e.stopPropagation()));
    });
</script>
@endpush