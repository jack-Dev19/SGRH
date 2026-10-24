@extends('layouts.dashboard')

@section('title', 'Modifier — ' . $document->titre)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.documents.index') }}" class="hover:text-purple-600 transition">Documents</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">Modifier</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Modifier le document</h1>
            <p class="text-purple-100 text-sm mt-1">{{ $document->titre }}</p>
        </div>

        <form action="{{ route('gestion.documents.update', $document) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
                    <p class="text-sm font-bold mb-2">Erreurs :</p>
                    <ul class="text-xs list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Catégorie --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select name="categorie" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    <option value="photo_identite" {{ old('categorie', $document->categorie) === 'photo_identite' ? 'selected' : '' }}>Photo d'identité</option>
                    <option value="photo_cin"      {{ old('categorie', $document->categorie) === 'photo_cin'      ? 'selected' : '' }}>Photo CIN</option>
                    <option value="contrat"        {{ old('categorie', $document->categorie) === 'contrat'        ? 'selected' : '' }}>Contrat</option>
                    <option value="fiche_paie"     {{ old('categorie', $document->categorie) === 'fiche_paie'     ? 'selected' : '' }}>Fiche de paie</option>
                    <option value="diplome"        {{ old('categorie', $document->categorie) === 'diplome'        ? 'selected' : '' }}>Diplôme</option>
                    <option value="attestation"    {{ old('categorie', $document->categorie) === 'attestation'    ? 'selected' : '' }}>Attestation</option>
                    <option value="autre"          {{ old('categorie', $document->categorie) === 'autre'          ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            {{-- Titre --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="titre" value="{{ old('titre', $document->titre) }}" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
            </div>

            {{-- Fichier actuel --}}
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Fichier actuel</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $document->fichier_nom_original }}</p>
                        <p class="text-xs text-slate-400">{{ $document->taille_humaine }}</p>
                    </div>
                    <a href="{{ route('gestion.documents.download', $document) }}"
                        class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Nouveau fichier --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Remplacer le fichier <span class="text-slate-400 font-normal normal-case">(optionnel — max 10 Mo)</span>
                </label>
                <input type="file" name="fichier"
                    accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:font-bold file:text-xs file:cursor-pointer hover:file:bg-purple-700">
            </div>

            {{-- Mois / Année --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Mois</label>
                    <select name="mois"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                        <option value="">—</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('mois', $document->mois) == $m ? 'selected' : '' }}>
                                {{ ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'][$m - 1] }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Année</label>
                    <input type="number" name="annee" value="{{ old('annee', $document->annee) }}"
                        min="2000" max="2100" placeholder="2025"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">{{ old('description', $document->description) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.employes.documents.index', $document->employe_id) }}"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

@endsection