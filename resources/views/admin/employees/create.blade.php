@extends('layouts.dashboard')

@section('title', 'Nouvel employé')
@section('page-title', 'Nouvel employé')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- En-tête --}}
    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour à la liste
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Ajouter un nouvel employé</h2>
        <p class="text-slate-500 text-sm mt-1">Remplissez les informations ci-dessous.</p>
    </div>

    {{-- Erreurs de validation --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6">
            <p class="font-bold mb-2">Veuillez corriger les erreurs :</p>
            <ul class="text-sm list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulaire --}}
    <form action="{{ route('admin.employees.store') }}" method="POST" class="bg-white rounded-2xl border border-purple-100 p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Matricule --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Matricule <span class="text-purple-600">*</span>
                </label>
                <input type="text" name="matricule" value="{{ old('matricule', 'EMP-' . str_pad(\App\Models\Employee::count() + 1, 3, '0', STR_PAD_LEFT)) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Sexe --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Sexe <span class="text-purple-600">*</span>
                </label>
                <select name="sexe" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">Choisir</option>
                    <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

            {{-- Prénom --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Prénom <span class="text-purple-600">*</span>
                </label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Nom --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Nom <span class="text-purple-600">*</span>
                </label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Email <span class="text-purple-600">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Téléphone --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Téléphone
                </label>
                <input type="text" name="telephone" value="{{ old('telephone') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Département --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Département <span class="text-purple-600">*</span>
                </label>
                <select name="departement_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="">Choisir un département</option>
                    @foreach($departements as $dept)
                        <option value="{{ $dept->id }}" {{ old('departement_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Salaire --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Salaire de base (MAD) <span class="text-purple-600">*</span>
                </label>
                <input type="number" name="salaire_base" value="{{ old('salaire_base') }}" min="0" step="100" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Date d'embauche --}}
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">
                    Date d'embauche <span class="text-purple-600">*</span>
                </label>
                <input type="date" name="date_embauche" value="{{ old('date_embauche', now()->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            {{-- Statut actif --}}
            <div class="md:col-span-2 flex items-center gap-3 bg-purple-50 rounded-xl p-4">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-slate-300 rounded cursor-pointer">
                <label for="actif" class="text-sm text-slate-700 cursor-pointer">
                    <span class="font-medium">Marquer comme actif</span>
                    <span class="text-slate-500 block text-xs">L'employé pourra se connecter et être compté dans les effectifs.</span>
                </label>
            </div>

        </div>

        {{-- Boutons --}}
        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.employees.index') }}"
                class="text-sm font-medium text-slate-600 hover:text-slate-800 px-5 py-3 rounded-xl transition">
                Annuler
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer
            </button>
        </div>

    </form>

</div>

@endsection