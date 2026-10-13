@extends('layouts.dashboard')

@section('title', 'Nouvel employé')
@section('page-title', 'Nouvel employé')

@section('content')

@php
    // Liste des départements (statique, ou depuis le controller)
    $departements = collect([
        (object) ['id' => 1, 'nom' => 'Informatique'],
        (object) ['id' => 2, 'nom' => 'Comptabilité'],
        (object) ['id' => 3, 'nom' => 'Ressources Humaines'],
        (object) ['id' => 4, 'nom' => 'Marketing'],
        (object) ['id' => 5, 'nom' => 'Commercial'],
        (object) ['id' => 6, 'nom' => 'Direction Générale'],
    ]);
@endphp

<div class="max-w-5xl mx-auto py-8">

    <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
        Retour aux employés
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- CARTE VIOLETTE --}}
        <div class="relative bg-purple-600 rounded-[2rem] shadow-2xl shadow-purple-500/30 overflow-hidden min-h-[700px] flex flex-col sticky top-24">
            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Nouvel Employé</h2>
                <p class="text-sm text-purple-200 uppercase tracking-widest">Ajouter un membre</p>
            </div>
            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center w-full">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">Ajouter un nouvel employé</p>
                    <p class="text-purple-100 text-sm leading-relaxed max-w-xs mx-auto mb-6">Remplissez le formulaire pour enregistrer un nouveau membre.</p>

                    <div class="space-y-2 max-w-xs mx-auto text-left">
                        <div class="flex items-center gap-2 text-xs text-purple-100">
                            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Informations personnelles</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-purple-100">
                            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Département & poste</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-purple-100">
                            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Salaire & date d'embauche</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#e9d5ff" opacity="0.3"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#d8b4fe" opacity="0.5"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#c084fc" opacity="0.7"/></svg>
            </div>
        </div>

        {{-- FORMULAIRE --}}
        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-purple-500/20 overflow-hidden min-h-[700px] flex flex-col">
            <form action="{{ route('admin.employees.store') }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf

                <div class="text-center mb-6">
                    <h2 class="text-3xl font-extrabold text-purple-700 uppercase tracking-wider mb-2">Créer</h2>
                    <p class="text-sm text-purple-400 uppercase tracking-widest">Un nouvel employé</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-2xl mb-5">
                        @foreach ($errors->all() as $error) <p>• {{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="space-y-3">
                    {{-- Matricule --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        </div>
                        <input type="text" name="matricule" required
                            value="{{ old('matricule', 'EMP-' . str_pad(\App\Models\Employee::count() + 1, 3, '0', STR_PAD_LEFT)) }}"
                            placeholder="Matricule"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                    </div>

                    {{-- Prénom + Nom --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="prenom" required value="{{ old('prenom') }}" placeholder="Prénom"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="nom" required value="{{ old('nom') }}" placeholder="Nom"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- ⭐ SEXE — DROPDOWN CUSTOM ⭐ --}}
                    <div class="relative" data-dropdown="sexe">
                        <input type="hidden" name="sexe" id="input-sexe" value="{{ old('sexe') }}">
                        <button type="button" id="dropdown-sexe-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span id="dropdown-sexe-label" class="truncate text-left">
                                @if(old('sexe') === 'M') Masculin
                                @elseif(old('sexe') === 'F') Féminin
                                @else Sexe @endif
                            </span>
                            <svg id="dropdown-sexe-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-sexe-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50">
                            <button type="button" onclick="selectDropdown('sexe', '', 'Sexe')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-500 hover:bg-slate-50 transition">Sexe</button>
                            <button type="button" onclick="selectDropdown('sexe', 'M', 'Masculin')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition border-t border-slate-100 flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                Masculin
                            </button>
                            <button type="button" onclick="selectDropdown('sexe', 'F', 'Féminin')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-pink-50 hover:text-pink-700 transition border-t border-slate-100 flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-pink-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                Féminin
                            </button>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" name="email" required value="{{ old('email') }}" placeholder="Email"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                    </div>

                    {{-- Téléphone --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="text" name="telephone" value="{{ old('telephone') }}" placeholder="Téléphone"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                    </div>

                    {{-- ⭐ DÉPARTEMENT — DROPDOWN CUSTOM ⭐ --}}
                    <div class="relative" data-dropdown="departement_id">
                        <input type="hidden" name="departement_id" id="input-departement_id" value="{{ old('departement_id') }}">
                        <button type="button" id="dropdown-departement_id-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span id="dropdown-departement_id-label" class="truncate text-left">
                                @php $deptSel = $departements->firstWhere('id', old('departement_id')); @endphp
                                {{ $deptSel ? $deptSel->nom : 'Sélectionner un département' }}
                            </span>
                            <svg id="dropdown-departement_id-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-departement_id-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 max-h-64 overflow-y-auto">
                            <button type="button" onclick="selectDropdown('departement_id', '', 'Sélectionner un département')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-500 hover:bg-slate-50 transition">Aucun</button>
                            @foreach($departements as $dept)
                                <button type="button" onclick="selectDropdown('departement_id', '{{ $dept->id }}', '{{ $dept->nom }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 transition flex items-center gap-3 border-t border-slate-100">
                                    <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <span class="font-medium">{{ $dept->nom }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Salaire + Date --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="number" name="salaire_base" min="0" step="100" required value="{{ old('salaire_base') }}" placeholder="Salaire"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_embauche" required value="{{ old('date_embauche', now()->format('Y-m-d')) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Actif --}}
                    <div class="flex items-center gap-3 bg-purple-50 rounded-full px-5 py-3">
                        <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-slate-300 rounded cursor-pointer">
                        <label for="actif" class="text-xs text-slate-700 cursor-pointer font-semibold">Marquer comme actif</label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold uppercase tracking-wider hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300/50 transition shadow-lg shadow-purple-500/30 transform hover:-translate-y-0.5">
                    Enregistrer l'employé
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
        ['sexe', 'departement_id'].forEach(type => {
            const btn = document.getElementById('dropdown-' + type + '-btn');
            if (btn) btn.addEventListener('click', e => { e.stopPropagation(); toggleDropdown(type); });
        });
        document.addEventListener('click', () => {
            document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.classList.add('hidden'));
            document.querySelectorAll('[id^="dropdown-"][id$="-chevron"]').forEach(c => c.style.transform = 'rotate(0deg)');
        });
        document.querySelectorAll('[id^="dropdown-"][id$="-menu"]').forEach(m => m.addEventListener('click', e => e.stopPropagation()));
    });
</script>
@endpush