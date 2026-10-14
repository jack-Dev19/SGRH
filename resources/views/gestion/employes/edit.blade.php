@extends('layouts.dashboard')

@section('title', 'Modifier ' . $employe->nom_complet)

@section('content')

<div class="max-w-6xl mx-auto py-4">

    {{-- Fil d'Ariane --}}
    <a href="{{ route('gestion.employes.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux employés
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- ============================================= --}}
        {{-- CARTE BLEU → VIOLET (aperçu) --}}
        {{-- ============================================= --}}
        <div class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 rounded-[2rem] shadow-2xl shadow-blue-500/30 overflow-hidden min-h-[700px] flex flex-col lg:sticky lg:top-24">

            {{-- Badge matricule --}}
            <div class="absolute top-6 right-6 z-20 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm border border-white/30">
                <span class="text-xs font-bold text-white tracking-wider">{{ $employe->matricule }}</span>
            </div>

            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Modifier</h2>
                <p class="text-sm text-blue-100 uppercase tracking-widest">Mise à jour</p>
            </div>

            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center w-full">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">Modifier l'employé</p>
                    <p class="text-blue-100 text-sm leading-relaxed max-w-xs mx-auto mb-6">
                        Mettez à jour les informations de <span class="font-bold text-white">{{ $employe->nom_complet }}</span>.
                    </p>

                    <div class="space-y-2 max-w-xs mx-auto text-left">
                        <div class="flex items-center gap-2 text-xs text-blue-100">
                            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Informations personnelles</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-blue-100">
                            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Département & poste</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-blue-100">
                            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span>Date d'embauche & statut</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#dbeafe" opacity="0.3"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#a5b4fc" opacity="0.5"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#818cf8" opacity="0.7"/></svg>
            </div>
        </div>

        {{-- ============================================= --}}
        {{-- FORMULAIRE --}}
        {{-- ============================================= --}}
        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-blue-500/20 overflow-hidden min-h-[700px] flex flex-col">
            <form action="{{ route('gestion.employes.update', $employe) }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf
                @method('PUT')

                <div class="text-center mb-6">
                    <h2 class="text-3xl font-extrabold text-blue-700 uppercase tracking-wider mb-2">Modifier</h2>
                    <p class="text-sm text-blue-400 uppercase tracking-widest">Les informations</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-2xl mb-5">
                        @foreach ($errors->all() as $error) <p>• {{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="space-y-3">

                    {{-- Matricule --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        </div>
                        <input type="text" name="matricule" required value="{{ old('matricule', $employe->matricule) }}" placeholder="Matricule"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                    </div>

                    {{-- Prénom + Nom --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="prenom" required value="{{ old('prenom', $employe->prenom) }}" placeholder="Prénom"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input type="text" name="nom" required value="{{ old('nom', $employe->nom) }}" placeholder="Nom"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Sexe --}}
                    <div class="relative" data-dropdown="sexe">
                        <input type="hidden" name="sexe" id="input-sexe" value="{{ old('sexe', $employe->sexe) }}">
                        <button type="button" id="dropdown-sexe-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span id="dropdown-sexe-label" class="truncate text-left">
                                {{ old('sexe', $employe->sexe) ?: 'Sexe' }}
                            </span>
                            <svg id="dropdown-sexe-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-sexe-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50">
                            <button type="button" onclick="selectDropdown('sexe', 'Homme', 'Homme')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                Homme
                            </button>
                            <button type="button" onclick="selectDropdown('sexe', 'Femme', 'Femme')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-pink-50 hover:text-pink-700 transition flex items-center gap-3 border-t border-slate-100">
                                <div class="w-7 h-7 rounded-lg bg-pink-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                Femme
                            </button>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" name="email" required value="{{ old('email', $employe->email) }}" placeholder="Email"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                    </div>

                    {{-- Téléphone --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <input type="text" name="telephone" value="{{ old('telephone', $employe->telephone) }}" placeholder="Téléphone"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                    </div>

                    {{-- Département --}}
                    <div class="relative" data-dropdown="departement_id">
                        <input type="hidden" name="departement_id" id="input-departement_id" value="{{ old('departement_id', $employe->departement_id) }}">
                        <button type="button" id="dropdown-departement_id-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span id="dropdown-departement_id-label" class="truncate text-left">
                                @php $deptSel = $departements->firstWhere('id', old('departement_id', $employe->departement_id)); @endphp
                                {{ $deptSel ? $deptSel->nom : 'Sélectionner un département' }}
                            </span>
                            <svg id="dropdown-departement_id-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-departement_id-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 max-h-64 overflow-y-auto">
                            <button type="button" onclick="selectDropdown('departement_id', '', 'Sélectionner un département')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-500 hover:bg-slate-50 transition">Aucun</button>
                            @foreach($departements as $dept)
                                <button type="button" onclick="selectDropdown('departement_id', '{{ $dept->id }}', '{{ $dept->nom }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-3 border-t border-slate-100">
                                    <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <span class="font-medium">{{ $dept->nom }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Poste --}}
                    <div class="relative" data-dropdown="poste_id">
                        <input type="hidden" name="poste_id" id="input-poste_id" value="{{ old('poste_id', $employe->poste_id) }}">
                        <button type="button" id="dropdown-poste_id-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <span id="dropdown-poste_id-label" class="truncate text-left">
                                @php $posteSel = $postes->firstWhere('id', old('poste_id', $employe->poste_id)); @endphp
                                {{ $posteSel ? $posteSel->nom : 'Sélectionner un poste' }}
                            </span>
                            <svg id="dropdown-poste_id-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-poste_id-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50 max-h-64 overflow-y-auto">
                            <button type="button" onclick="selectDropdown('poste_id', '', 'Sélectionner un poste')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-500 hover:bg-slate-50 transition">Aucun</button>
                            @foreach($postes as $poste)
                                <button type="button" onclick="selectDropdown('poste_id', '{{ $poste->id }}', '{{ $poste->nom }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition flex items-center gap-3 border-t border-slate-100">
                                    <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="font-medium">{{ $poste->nom }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Adresse --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <input type="text" name="adresse" value="{{ old('adresse', $employe->adresse) }}" placeholder="Adresse"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_naissance"
                                value="{{ old('date_naissance', $employe->date_naissance?->format('Y-m-d')) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_embauche"
                                value="{{ old('date_embauche', $employe->date_embauche?->format('Y-m-d')) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Statut --}}
                    <div class="relative" data-dropdown="statut">
                        <input type="hidden" name="statut" id="input-statut" value="{{ old('statut', $employe->statut) }}">
                        <button type="button" id="dropdown-statut-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-blue-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-blue-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-blue-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span id="dropdown-statut-label" class="truncate text-left">
                                {{ old('statut', $employe->statut) === 'inactif' ? 'Inactif' : 'Actif' }}
                            </span>
                            <svg id="dropdown-statut-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-statut-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50">
                            <button type="button" onclick="selectDropdown('statut', 'actif', 'Actif')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Actif
                            </button>
                            <button type="button" onclick="selectDropdown('statut', 'inactif', 'Inactif')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition border-t border-slate-100 flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                                Inactif
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-bold uppercase tracking-wider hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-blue-300/50 transition shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5">
                    Enregistrer les modifications
                </button>

                <div class="text-center mt-4">
                    <button type="button"
                        onclick="if(confirm('⚠️ Supprimer définitivement cet employé ?')) { document.getElementById('delete-form').submit(); }"
                        class="text-xs text-red-500 hover:text-red-700 hover:underline transition">
                        Supprimer cet employé
                    </button>
                </div>
            </form>

            <div class="absolute bottom-0 left-0 right-0 h-40 pointer-events-none">
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,60 C150,130 350,0 500,60 L500,150 L0,150 Z" fill="#dbeafe" opacity="0.6"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,90 C150,150 350,30 500,90 L500,150 L0,150 Z" fill="#a5b4fc" opacity="0.7"/></svg>
                <svg class="absolute bottom-0 left-0 w-full h-full" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M0,120 C150,180 350,60 500,120 L500,150 L0,150 Z" fill="#6366f1" opacity="0.8"/></svg>
            </div>
        </div>
    </div>

    {{-- Formulaire de suppression caché --}}
    <form id="delete-form" action="{{ route('gestion.employes.destroy', $employe) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

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
        ['sexe', 'departement_id', 'poste_id', 'statut'].forEach(type => {
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