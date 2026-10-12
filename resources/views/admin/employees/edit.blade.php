@extends('layouts.dashboard')

@section('title', 'Modifier employé')
@section('page-title', 'Modifier employé')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-purple-600 transition mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            Retour à la liste
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Modifier : {{ $employee->prenom }} {{ $employee->nom }}</h2>
        <p class="text-slate-500 text-sm mt-1">Matricule : {{ $employee->matricule }}</p>
    </div>

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

    <form action="{{ route('admin.employees.update', $employee) }}" method="POST" class="bg-white rounded-2xl border border-purple-100 p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Matricule <span class="text-purple-600">*</span></label>
                <input type="text" name="matricule" value="{{ old('matricule', $employee->matricule) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Sexe <span class="text-purple-600">*</span></label>
                <select name="sexe" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    <option value="M" {{ old('sexe', $employee->sexe) === 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('sexe', $employee->sexe) === 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Prénom <span class="text-purple-600">*</span></label>
                <input type="text" name="prenom" value="{{ old('prenom', $employee->prenom) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Nom <span class="text-purple-600">*</span></label>
                <input type="text" name="nom" value="{{ old('nom', $employee->nom) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Email <span class="text-purple-600">*</span></label>
                <input type="email" name="email" value="{{ old('email', $employee->email) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $employee->telephone) }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Département <span class="text-purple-600">*</span></label>
                <select name="departement_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    @foreach($departements as $dept)
                        <option value="{{ $dept->id }}" {{ old('departement_id', $employee->departement_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Salaire (MAD) <span class="text-purple-600">*</span></label>
                <input type="number" name="salaire_base" value="{{ old('salaire_base', $employee->salaire_base) }}" min="0" step="100" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-2">Date d'embauche <span class="text-purple-600">*</span></label>
                <input type="date" name="date_embauche" value="{{ old('date_embauche', $employee->date_embauche?->format('Y-m-d')) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            </div>

            <div class="md:col-span-2 flex items-center gap-3 bg-purple-50 rounded-xl p-4">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $employee->actif) ? 'checked' : '' }}
                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-slate-300 rounded cursor-pointer">
                <label for="actif" class="text-sm text-slate-700 cursor-pointer">
                    <span class="font-medium">Employé actif</span>
                </label>
            </div>

        </div>

        <div class="flex items-center justify-between gap-3 mt-8 pt-6 border-t border-slate-100">

            {{-- Supprimer --}}
            <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST"
                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet employé ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 px-4 py-3 rounded-xl hover:bg-red-50 transition">
                    Supprimer
                </button>
            </form>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.employees.index') }}"
                    class="text-sm font-medium text-slate-600 hover:text-slate-800 px-5 py-3 rounded-xl transition">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-3 rounded-xl transition shadow-lg shadow-purple-600/30 transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>

        </div>

    </form>

</div>

@endsection