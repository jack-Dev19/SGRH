@extends('layouts.dashboard')

@section('title', 'Contrat ' . $contrat->numero)

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- ============================================= --}}
    {{-- FIL D'ARIANE --}}
    {{-- ============================================= --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.contrats.index') }}" class="hover:text-purple-600 transition">Contrats</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">{{ $contrat->numero }}</span>
    </div>

    {{-- ============================================= --}}
    {{-- MESSAGES FLASH --}}
    {{-- ============================================= --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ============================================= --}}
    {{-- CARTE PRINCIPALE --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        {{-- EN-TÊTE DÉGRADÉ --}}
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center gap-5">

                {{-- Icône --}}
                <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/40 shrink-0 mx-auto md:mx-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                {{-- Infos --}}
                <div class="flex-1 text-center md:text-left">
                    <p class="text-purple-100 text-xs uppercase tracking-wider mb-1">Contrat</p>
                    <h1 class="text-2xl font-extrabold font-mono">{{ $contrat->numero }}</h1>
                    <p class="text-purple-100 text-sm mt-1">
                        {{ $contrat->type }}
                        @if($contrat->employe)
                            · {{ $contrat->employe->nom_complet }}
                        @endif
                    </p>
                </div>

                {{-- Badge statut --}}
                <div class="flex gap-2 justify-center md:justify-end">
                    @if($contrat->statut === 'actif')
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-emerald-100 text-emerald-700">Actif</span>
                    @elseif($contrat->statut === 'expire')
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-yellow-100 text-yellow-700">Expiré</span>
                    @else
                        <span class="text-xs font-bold px-4 py-2 rounded-full bg-red-100 text-red-700">Résilié</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- CORPS --}}
        <div class="p-8">

            {{-- ============================================= --}}
            {{-- DOCUMENTS PDF + DOSSIER PERSONNEL          --}}
            {{-- ============================================= --}}
            <div class="bg-gradient-to-br from-purple-50 to-white border-2 border-purple-100 rounded-2xl p-6 mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Documents PDF & Dossier personnel</h3>
                </div>

                <p class="text-xs text-slate-500 mb-4">
                    Téléchargez le contrat, générez la fiche de paie ou accédez au
                    dossier personnel complet de
                    <strong class="text-purple-600">
                        {{ $contrat->employe?->nom_complet ?? 'l\'employé' }}
                    </strong>.
                </p>

                <div class="flex flex-wrap gap-2">
                    {{-- Télécharger contrat --}}
                    <a href="{{ route('gestion.contrats.download', $contrat->id) }}"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger le contrat
                    </a>

                    {{-- Fiche de paie --}}
                    @if(Route::has('gestion.contrats.fiche-paie'))
                        <a href="{{ route('gestion.contrats.fiche-paie', $contrat->id) }}"
                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Fiche de paie (mois en cours)
                        </a>
                    @endif

                    {{-- Dossier personnel --}}
                    @if($contrat->employe_id && Route::has('gestion.employes.documents.index'))
                        <a href="{{ route('gestion.employes.documents.index', $contrat->employe_id) }}"
                            class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            Dossier personnel
                        </a>
                    @endif
                </div>

                {{-- Formulaire fiche de paie mois spécifique --}}
                @if(Route::has('gestion.contrats.fiche-paie'))
                    <form method="GET"
                        action="{{ route('gestion.contrats.fiche-paie', $contrat->id) }}"
                        class="mt-4 pt-4 border-t border-purple-100"
                        id="formFichePaie">

                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                            Ou générer une fiche de paie pour un mois spécifique :
                        </p>

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-3 flex items-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-xs">
                                    @foreach($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-wrap items-center gap-2">
                            <select name="mois" id="selectMois"
                                class="px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('mois', now()->month) == $m ? 'selected' : '' }}>
                                        {{ ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'][$m - 1] }}
                                    </option>
                                @endfor
                            </select>

                            <input type="number" name="annee" id="inputAnnee"
                                value="{{ old('annee', now()->year) }}"
                                min="2000" max="2100"
                                class="w-28 px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">

                            <button type="submit" id="btnGenerer"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-5 py-2 rounded-xl transition shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Générer
                            </button>
                        </div>

                        <p class="text-[10px] text-slate-400 mt-2">
                            Le mois et l'année doivent être compris entre
                            <strong>Janvier 2000</strong> et <strong>Décembre 2100</strong>.
                        </p>
                    </form>
                @endif
            </div>

            {{-- ============================================= --}}
            {{-- INFORMATIONS EMPLOYÉ --}}
            {{-- ============================================= --}}
            @if($contrat->employe)
                <div class="bg-slate-50 rounded-xl p-4 mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé concerné</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ $contrat->employe->initiales }}
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900">{{ $contrat->employe->nom_complet }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $contrat->employe->matricule }}
                                @if($contrat->employe->poste)
                                    · {{ $contrat->employe->poste->nom }}
                                @endif
                                @if($contrat->employe->departement)
                                    · {{ $contrat->employe->departement->nom }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('gestion.employes.show', $contrat->employe) }}"
                            class="text-xs font-bold text-purple-600 hover:text-purple-700 transition">
                            Voir la fiche →
                        </a>
                    </div>
                </div>
            @endif

            {{-- ============================================= --}}
            {{-- DÉTAILS CONTRAT --}}
            {{-- ============================================= --}}
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wide mb-4">Détails du contrat</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Type de contrat</p>
                    <p class="text-sm font-medium text-slate-800">{{ $contrat->type }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Salaire</p>
                    <p class="text-sm font-bold text-slate-800">
                        {{ number_format($contrat->salaire, 0, ',', ' ') }} {{ $contrat->devise }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de début</p>
                    <p class="text-sm text-slate-800">{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Date de fin</p>
                    <p class="text-sm text-slate-800">
                        {{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}
                    </p>
                </div>
                @if($contrat->periode_essai)
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Période d'essai</p>
                        <p class="text-sm text-slate-800">
                            {{ $contrat->periode_essai }} {{ $contrat->unite_periode_essai ?? 'mois' }}
                        </p>
                    </div>
                @endif
                @if($contrat->description)
                    <div class="md:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Description</p>
                        <p class="text-sm text-slate-800">{{ $contrat->description }}</p>
                    </div>
                @endif
            </div>

            {{-- ============================================= --}}
            {{-- ACTIONS BAS DE PAGE --}}
            {{-- ============================================= --}}
            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.contrats.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-purple-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Retour à la liste
                </a>

                <div class="flex items-center gap-3">
                    <form action="{{ route('gestion.contrats.destroy', $contrat) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            onclick="return confirm('Supprimer ce contrat ?');"
                            class="inline-flex items-center gap-2 text-red-600 bg-red-50 hover:bg-red-100 text-sm font-bold px-5 py-2.5 rounded-xl transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                    </form>

                    <a href="{{ route('gestion.contrats.edit', $contrat) }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- SCRIPT VALIDATION FICHE PAIE                --}}
{{-- ============================================= --}}
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