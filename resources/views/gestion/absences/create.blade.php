@extends('layouts.dashboard')

@section('title', 'Nouvelle absence')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.absences.index') }}" class="hover:text-purple-600 transition">Absences</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="font-semibold text-slate-700">Nouvelle absence</span>
    </div>

    {{-- En-tête --}}
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-1">Nouvelle absence</h1>
        <p class="text-sm text-slate-500">Enregistrez une absence manuellement.</p>
    </div>

    {{-- Formulaire --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h2 class="text-lg font-extrabold">Informations de l'absence</h2>
            <p class="text-purple-100 text-sm mt-1">Les champs marqués d'un * sont obligatoires.</p>
        </div>

        <form action="{{ route('gestion.absences.store') }}" method="POST"
              enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            {{-- Erreurs globales --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm p-4 rounded-xl">
                    <p class="font-bold mb-2">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SECTION : Employé --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Employé concerné</h3>

                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <select name="employe_id" required
                        class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-100 transition">
                        <option value="">— Sélectionner un employé —</option>
                        @foreach($employes as $emp)
                            <option value="{{ $emp->id }}" {{ old('employe_id') === $emp->id ? 'selected' : '' }}>
                                {{ $emp->nom_complet }} ({{ $emp->matricule }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- SECTION : Période --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Période</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Date début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_debut" required
                            value="{{ old('date_debut', now()->toDateString()) }}"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Date fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_fin" required
                            value="{{ old('date_fin', now()->toDateString()) }}"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    </div>
                </div>
            </div>

            {{-- SECTION : Détails --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Détails</h3>

                <div class="space-y-4">
                    {{-- Motif --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Motif <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="motif" required maxlength="150"
                            value="{{ old('motif') }}"
                            placeholder="Ex : Maladie, Mission, Raison personnelle..."
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Description détaillée
                        </label>
                        <textarea name="description" rows="3" maxlength="500"
                            placeholder="Détails complémentaires (optionnel)..."
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none resize-none">{{ old('description') }}</textarea>
                    </div>

                    {{-- Justificatif --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                            Justificatif (PDF, JPG, PNG — max 2 Mo)
                        </label>
                        <input type="file" name="justificatif" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full px-4 py-3 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 text-sm focus:bg-white focus:border-purple-500 focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-purple-100 file:text-purple-700 file:font-semibold file:text-xs hover:file:bg-purple-200">
                        <p class="text-xs text-slate-400 mt-1">Formats acceptés : PDF, JPG, PNG. Taille maximale : 2 Mo.</p>
                    </div>
                </div>
            </div>

            {{-- SECTION : Statut initial --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">Statut initial</h3>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="en_attente" checked class="mt-1">
                        <div>
                            <p class="font-semibold text-blue-800">En attente de validation</p>
                            <p class="text-xs text-blue-600">Le statut sera mis à jour lors de la validation (Approuver / Refuser).</p>
                        </div>
                    </label>
                </div>

                <div class="mt-3 bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="justifie" {{ old('statut') === 'justifie' ? 'checked' : '' }} class="mt-1">
                        <div>
                            <p class="font-semibold text-emerald-800">Justifiée directement</p>
                            <p class="text-xs text-emerald-600">Le RH valide immédiatement l'absence (justifiée).</p>
                        </div>
                    </label>
                </div>

                <div class="mt-3 bg-red-50 border border-red-200 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="non_justifie" {{ old('statut') === 'non_justifie' ? 'checked' : '' }} class="mt-1">
                        <div>
                            <p class="font-semibold text-red-800">Non justifiée</p>
                            <p class="text-xs text-red-600">Absence sans motif valable (impact sur la paie possible).</p>
                        </div>
                    </label>
                </div>

                <div class="mt-3 bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="radio" name="statut" value="autorise" {{ old('statut') === 'autorise' ? 'checked' : '' }} class="mt-1">
                        <div>
                            <p class="font-semibold text-purple-800">Autorisée</p>
                            <p class="text-xs text-purple-600">Autorisation exceptionnelle accordée.</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Boutons --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('gestion.absences.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow-lg shadow-purple-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer l'absence
                </button>
            </div>
        </form>
    </div>

</div>

@endsection