@extends('layouts.dashboard')

@section('title', 'Contrat ' . $contrat->numero)

@section('content')

<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-purple-500 font-medium mb-6 px-2">
        <a href="{{ route('gestion.contrats.index') }}" class="hover:text-purple-700 transition-colors">Contrats</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        <span class="text-purple-900 font-bold truncate">{{ $contrat->numero }}</span>
    </div>

    {{-- Messages flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-sm font-semibold text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl bg-red-50 border border-red-100 p-4 shadow-sm">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-sm font-semibold text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Carte principale --}}
    <div class="relative bg-white rounded-[40px] shadow-[0_20px_60px_-15px_rgba(147,51,234,0.3)] overflow-hidden">

        {{-- En-tête --}}
        <div class="relative px-8 pt-10 pb-8 text-center">
            
            {{-- Icône --}}
            <div class="mx-auto w-24 h-24 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 p-1 shadow-lg shadow-purple-500/30 ring-4 ring-white">
                <div class="w-full h-full rounded-3xl bg-white/10 backdrop-blur-sm flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <h1 class="mt-4 text-2xl font-extrabold text-slate-900 tracking-tight font-mono">{{ $contrat->numero }}</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ $contrat->type }}
                @if($contrat->employe)
                    · {{ $contrat->employe->nom_complet }}
                @endif
            </p>

            {{-- Badge Statut --}}
            <div class="mt-3">
                @if($contrat->statut === 'actif')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Actif
                    </span>
                @elseif($contrat->statut === 'expire')
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-700 ring-1 ring-inset ring-yellow-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                        Expiré
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                        Résilié
                    </span>
                @endif
            </div>
        </div>

        {{-- Corps --}}
        <div class="px-8 pb-10 relative z-10">
            
            {{-- Section 1 : Documents PDF --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">1</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Documents PDF & Dossier personnel</h3>
            </div>

            <div class="rounded-3xl bg-gradient-to-br from-purple-50 to-white border border-purple-100 p-5 mb-8">
                <p class="text-xs text-slate-500 mb-4">
                    Téléchargez le contrat, générez la fiche de paie ou accédez au dossier personnel complet de
                    <strong class="text-purple-600">{{ $contrat->employe?->nom_complet ?? 'l\'employé' }}</strong>.
                </p>

                {{-- Boutons d'action --}}
                <div class="flex flex-wrap gap-2 mb-5">
                    {{-- Télécharger contrat --}}
                    <a href="{{ route('gestion.contrats.download', $contrat->id) }}"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-4 py-2.5 rounded-full transition shadow-md shadow-purple-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Télécharger le contrat
                    </a>

                    {{-- Fiche de paie --}}
                    @if(Route::has('gestion.contrats.fiche-paie'))
                        <a href="{{ route('gestion.contrats.fiche-paie', $contrat->id) }}"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-4 py-2.5 rounded-full transition shadow-md shadow-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Fiche de paie (mois en cours)
                        </a>
                    @endif

                    {{-- Dossier personnel --}}
                    @if($contrat->employe_id && Route::has('gestion.employes.documents.index'))
                        <a href="{{ route('gestion.employes.documents.index', $contrat->employe_id) }}"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-2.5 rounded-full transition shadow-md shadow-indigo-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            Dossier personnel
                        </a>
                    @endif
                </div>

                {{-- Formulaire fiche de paie spécifique --}}
                @if(Route::has('gestion.contrats.fiche-paie'))
                    <form method="GET" action="{{ route('gestion.contrats.fiche-paie', $contrat->id) }}"
                        class="pt-4 border-t border-purple-100/50" id="formFichePaie">

                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-3">Générer une fiche de paie pour un mois spécifique</p>

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-3 flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div class="text-xs">@foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach</div>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-2">
                            <select name="mois" id="selectMois"
                                class="px-3 py-2 text-sm bg-white border-2 border-purple-200 text-purple-900 rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('mois', now()->month) == $m ? 'selected' : '' }}>
                                        {{ ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'][$m - 1] }}
                                    </option>
                                @endfor
                            </select>

                            <input type="number" name="annee" id="inputAnnee"
                                value="{{ old('annee', now()->year) }}" min="2000" max="2100"
                                class="w-28 px-3 py-2 text-sm bg-white border-2 border-purple-200 text-purple-900 rounded-full focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 outline-none transition">

                            <button type="submit" id="btnGenerer"
                                class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-4 py-2 rounded-full transition shadow-md shadow-purple-500/20">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Générer
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            {{-- Section 2 : Informations employé --}}
            @if($contrat->employe)
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">2</span>
                    <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Employé concerné</h3>
                </div>

                <div class="rounded-3xl bg-slate-50 border border-slate-200 p-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ $contrat->employe->initiales }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 truncate">{{ $contrat->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $contrat->employe->matricule }}
                                @if($contrat->employe->poste) · {{ $contrat->employe->poste->nom }} @endif
                                @if($contrat->employe->departement) · {{ $contrat->employe->departement->nom }} @endif
                            </p>
                        </div>
                        <a href="{{ route('gestion.employes.show', $contrat->employe) }}"
                            class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:text-purple-700 bg-white px-3 py-2 rounded-full shadow-sm transition">
                            Voir
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @endif

            {{-- Section 3 : Détails du contrat --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-purple-100 text-purple-600 text-[10px] font-bold">3</span>
                <h3 class="text-xs font-bold text-purple-400 uppercase tracking-widest">Détails du contrat</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
                {{-- Type --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Type de contrat</p></div>
                    <p class="text-sm font-semibold text-slate-800">{{ $contrat->type }}</p>
                </div>

                {{-- Salaire --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Salaire</p></div>
                    <p class="text-sm font-bold text-slate-800">{{ number_format($contrat->salaire, 0, ',', ' ') }} {{ $contrat->devise }}</p>
                </div>

                {{-- Date début --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date de début</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>

                {{-- Date fin --}}
                <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                    <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date de fin</p></div>
                    <p class="text-sm font-medium text-slate-800">{{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}</p>
                </div>

                {{-- Période d'essai --}}
                @if($contrat->periode_essai)
                    <div class="rounded-2xl bg-purple-50/50 border border-purple-100/50 p-4">
                        <div class="flex items-center gap-2 mb-1"><svg class="h-3.5 w-3.5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Période d'essai</p></div>
                        <p class="text-sm font-medium text-slate-800">{{ $contrat->periode_essai }} {{ $contrat->unite_periode_essai ?? 'mois' }}</p>
                    </div>
                @endif

                {{-- Description --}}
                @if($contrat->description)
                    <div class="md:col-span-2 rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <div class="flex items-center gap-2 mb-2"><svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg><p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Description</p></div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $contrat->description }}</p>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-purple-100/50 flex flex-col md:flex-row items-center justify-between gap-3">
                <a href="{{ route('gestion.contrats.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-purple-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-2 w-full md:w-auto">
                    {{-- Supprimer --}}
                    <form action="{{ route('gestion.contrats.destroy', $contrat) }}" method="POST" class="flex-1 md:flex-initial" onsubmit="return confirm('Supprimer ce contrat ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 border border-red-100 text-sm font-bold px-5 py-3 rounded-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Supprimer
                        </button>
                    </form>

                    {{-- Modifier --}}
                    <a href="{{ route('gestion.contrats.edit', $contrat) }}" class="flex-1 md:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white text-sm font-bold px-6 py-3 rounded-full shadow-[0_10px_20px_-10px_rgba(147,51,234,0.6)] transition-all hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Modifier
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

{{-- Script validation fiche paie --}}
@if(Route::has('gestion.contrats.fiche-paie'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form       = document.getElementById('formFichePaie');
    const selectMois = document.getElementById('selectMois');
    const inputAnnee = document.getElementById('inputAnnee');
    const btnGenerer = document.getElementById('btnGenerer');

    if (!form) return;

    function validerFormulaire() {
        const mois  = parseInt(selectMois.value);
        const annee = parseInt(inputAnnee.value);
        const erreurs = [];

        if (isNaN(mois) || mois < 1 || mois > 12) {
            erreurs.push('Le mois doit être compris entre 1 et 12.');
            selectMois.classList.add('border-red-400', 'bg-red-50');
        } else {
            selectMois.classList.remove('border-red-400', 'bg-red-50');
        }

        if (isNaN(annee) || annee < 2000 || annee > 2100) {
            erreurs.push('L\'année doit être comprise entre 2000 et 2100.');
            inputAnnee.classList.add('border-red-400', 'bg-red-50');
        } else {
            inputAnnee.classList.remove('border-red-400', 'bg-red-50');
        }

        return erreurs;
    }

    form.addEventListener('submit', function (e) {
        const erreurs = validerFormulaire();
        if (erreurs.length > 0) {
            e.preventDefault();
            afficherErreur(erreurs.join(' '));
        } else {
            btnGenerer.disabled = true;
            btnGenerer.innerHTML = 'Génération…';
        }
    });

    function afficherErreur(message) {
        const old = form.querySelector('.js-erreur-js');
        if (old) old.remove();

        const div = document.createElement('div');
        div.className = 'js-erreur-js bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-3 text-xs';
        div.textContent = message;

        const fieldRow = form.querySelector('.flex.flex-wrap');
        fieldRow.parentNode.insertBefore(div, fieldRow);
    }

    selectMois.addEventListener('change', validerFormulaire);
    inputAnnee.addEventListener('input', validerFormulaire);
});
</script>
@endif

@endsection