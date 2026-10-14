@extends('layouts.dashboard')

@section('title', 'Nouveau contrat')

@section('content')

<div class="max-w-6xl mx-auto py-4">

    {{-- Fil d'Ariane --}}
    <a href="{{ route('gestion.contrats.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
        </svg>
        Retour aux contrats
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

        {{-- CARTE ILLUSTRATION --}}
        <div class="relative bg-purple-600 rounded-[2rem] shadow-2xl shadow-purple-500/30 overflow-hidden min-h-[700px] flex flex-col lg:sticky lg:top-24">
            <div class="relative z-10 px-10 pt-12 text-center">
                <h2 class="text-3xl font-extrabold text-white uppercase tracking-wider mb-2">Nouveau Contrat</h2>
                <p class="text-sm text-purple-200 uppercase tracking-widest">Créer un engagement</p>
            </div>

            <div class="relative z-10 flex-1 px-10 py-12 flex items-center justify-center">
                <div class="text-center w-full">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-white font-bold text-lg mb-2">Créer un contrat</p>
                    <p class="text-purple-100 text-sm leading-relaxed max-w-xs mx-auto mb-6">
                        Enregistrez un nouvel engagement pour un employé.
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
        <div class="relative bg-white rounded-[2rem] shadow-2xl shadow-purple-500/20 overflow-hidden min-h-[700px] flex flex-col">
            <form action="{{ route('gestion.contrats.store') }}" method="POST" class="relative z-10 px-8 pt-10 pb-32 flex-1">
                @csrf

                <div class="text-center mb-6">
                    <h2 class="text-3xl font-extrabold text-purple-700 uppercase tracking-wider mb-2">Créer</h2>
                    <p class="text-sm text-purple-400 uppercase tracking-widest">Un contrat</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-2xl mb-5">
                        @foreach ($errors->all() as $error) <p>• {{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="space-y-3">

                    {{-- Employé --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <select name="employe_id" required
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition appearance-none">
                            <option value="">— Sélectionner un employé —</option>
                            @foreach($employes as $emp)
                                <option value="{{ $emp->id }}" {{ old('employe_id') === $emp->id ? 'selected' : '' }}>
                                    {{ $emp->nom_complet }} ({{ $emp->matricule }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Numéro --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                        </div>
                        <input type="text" name="numero" required
                            value="{{ old('numero', 'CTR-' . str_pad(\App\Models\Contrat::count() + 1, 4, '0', STR_PAD_LEFT)) }}"
                            placeholder="Numéro de contrat"
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition font-mono">
                    </div>

                    {{-- Type --}}
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <select name="type" required
                            class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition appearance-none">
                            <option value="">— Type de contrat —</option>
                            @foreach(['CDI', 'CDD', 'Stage', 'Intérim', 'Freelance'] as $type)
                                <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dates début/fin --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_debut" required value="{{ old('date_debut', now()->format('Y-m-d')) }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <input type="date" name="date_fin" value="{{ old('date_fin') }}"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Salaire + Devise --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="relative col-span-2">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="number" name="salaire" min="0" step="1000" required value="{{ old('salaire') }}" placeholder="Salaire brut"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <input type="text" name="devise" required value="{{ old('devise', 'MGA') }}" placeholder="MGA"
                                class="w-full px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 font-bold text-center focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                    </div>

                    {{-- Période d'essai --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input type="number" name="periode_essai" min="0" value="{{ old('periode_essai') }}" placeholder="Période d'essai"
                                class="w-full pl-12 pr-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                        </div>
                        <div class="relative">
                            <select name="unite_periode_essai"
                                class="w-full px-4 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition appearance-none">
                                <option value="mois" {{ old('unite_periode_essai') === 'mois' ? 'selected' : '' }}>Mois</option>
                                <option value="jours" {{ old('unite_periode_essai') === 'jours' ? 'selected' : '' }}>Jours</option>
                                <option value="semaines" {{ old('unite_periode_essai') === 'semaines' ? 'selected' : '' }}>Semaines</option>
                            </select>
                        </div>
                    </div>

                    {{-- Statut --}}
                    <div class="relative" data-dropdown="statut">
                        <input type="hidden" name="statut" id="input-statut" value="{{ old('statut', 'actif') }}">
                        <button type="button" id="dropdown-statut-btn"
                            class="w-full inline-flex items-center justify-between gap-2 pl-12 pr-10 py-3.5 rounded-full bg-purple-100/70 text-sm text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span id="dropdown-statut-label" class="truncate text-left">
                                @php
                                    $statutLabels = ['actif' => 'Actif', 'expire' => 'Expiré', 'resilie' => 'Résilié'];
                                    echo $statutLabels[old('statut', 'actif')] ?? 'Actif';
                                @endphp
                            </span>
                            <svg id="dropdown-statut-chevron" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600 transition-transform duration-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="dropdown-statut-menu" class="hidden absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden z-50">
                            <button type="button" onclick="selectDropdown('statut', 'actif', 'Actif')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Actif
                            </button>
                            <button type="button" onclick="selectDropdown('statut', 'expire', 'Expiré')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-yellow-50 hover:text-yellow-700 transition border-t border-slate-100 flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                                Expiré
                            </button>
                            <button type="button" onclick="selectDropdown('statut', 'resilie', 'Résilié')"
                                class="w-full text-left px-4 py-3 text-sm text-slate-700 hover:bg-red-50 hover:text-red-700 transition border-t border-slate-100 flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                Résilié
                            </button>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="relative">
                        <div class="absolute left-4 top-3.5 text-purple-600 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h7"/></svg>
                        </div>
                        <textarea name="description" rows="2" placeholder="Description (optionnel)..."
                            class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-purple-100/70 text-sm text-slate-700 placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-purple-300/40 focus:bg-white transition resize-none">{{ old('description') }}</textarea>
                    </div>

                </div>

                <button type="submit"
                    class="w-full mt-6 py-3.5 rounded-full bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold uppercase tracking-wider hover:from-purple-700 hover:to-purple-800 focus:outline-none focus:ring-4 focus:ring-purple-300/50 transition shadow-lg shadow-purple-500/30 transform hover:-translate-y-0.5">
                    Enregistrer le contrat
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