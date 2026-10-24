@extends('layouts.dashboard')

@section('title', 'Ajouter un document')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.documents.index') }}" class="hover:text-purple-600 transition">Documents</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">Nouveau document</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Ajouter un document</h1>
            <p class="text-purple-100 text-sm mt-1">
                Photo, CIN, contrat, fiche de paie ou autre pièce justificative.
            </p>
        </div>

        <form action="{{ route('gestion.documents.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 space-y-5">
            @csrf

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

            {{-- Employé --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Employé <span class="text-red-500">*</span>
                </label>
                <select name="employe_id" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    <option value="">Sélectionner un employé…</option>
                    @foreach($employes as $emp)
                        <option value="{{ $emp->id }}"
                            {{ old('employe_id', $selectedEmployeId ?? '') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->nom_complet }} ({{ $emp->matricule }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Catégorie --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select name="categorie" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                    <option value="photo_identite" {{ old('categorie') === 'photo_identite' ? 'selected' : '' }}>Photo d'identité</option>
                    <option value="photo_cin"      {{ old('categorie') === 'photo_cin'      ? 'selected' : '' }}>Photo CIN</option>
                    <option value="contrat"        {{ old('categorie') === 'contrat'        ? 'selected' : '' }}>Contrat</option>
                    <option value="fiche_paie"     {{ old('categorie') === 'fiche_paie'     ? 'selected' : '' }}>Fiche de paie</option>
                    <option value="diplome"        {{ old('categorie') === 'diplome'        ? 'selected' : '' }}>Diplôme</option>
                    <option value="attestation"    {{ old('categorie') === 'attestation'    ? 'selected' : '' }}>Attestation</option>
                    <option value="autre"          {{ old('categorie') === 'autre'          ? 'selected' : '' }}>Autre</option>
                </select>
            </div>

            {{-- Titre --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex : CIN recto-verso, Contrat signé 2025…"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
            </div>

            {{-- Fichier --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Fichier <span class="text-red-500">*</span>
                    <span class="text-slate-400 font-normal normal-case">(max 10 Mo — PDF, JPG, PNG, DOC…)</span>
                </label>
                <input type="file" name="fichier" required
                    accept="image/*,application/pdf,.doc,.docx,.xls,.xlsx"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:font-bold file:text-xs file:cursor-pointer hover:file:bg-purple-700">
            </div>

            {{-- Mois / Année --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Mois <span class="text-slate-400 font-normal">(optionnel)</span>
                    </label>
                    <select name="mois"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                        <option value="">—</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('mois') == $m ? 'selected' : '' }}>
                                {{ ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'][$m - 1] }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                        Année <span class="text-slate-400 font-normal">(optionnel)</span>
                    </label>
                    <input type="number" name="annee" value="{{ old('annee') }}"
                        min="2000" max="2100" placeholder="2025"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">
                    Description <span class="text-slate-400 font-normal">(optionnel)</span>
                </label>
                <textarea name="description" rows="3"
                    placeholder="Notes complémentaires…"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition">{{ old('description') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.documents.index') }}"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection