@extends('layouts.dashboard')

@section('title', 'Modifier — ' . $offre->titre)

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.offres.index') }}" class="hover:text-purple-600 transition">Offres</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Modifier</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Modifier l'offre</h1>
            <p class="text-purple-100 text-sm mt-1">{{ $offre->titre }}</p>
        </div>

        <form action="{{ route('gestion.offres.update', $offre) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">
                    <ul class="text-xs list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Titre du poste *</label>
                <input type="text" name="titre" value="{{ old('titre', $offre->titre) }}" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Type de contrat *</label>
                    <select name="type_contrat" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                        @foreach(['CDI', 'CDD', 'Stage', 'Freelance', 'Intérim'] as $type)
                            <option value="{{ $type }}" {{ old('type_contrat', $offre->type_contrat) === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Lieu *</label>
                    <input type="text" name="lieu" value="{{ old('lieu', $offre->lieu) }}" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Description *</label>
                <textarea name="description" rows="4" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">{{ old('description', $offre->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Missions</label>
                <textarea name="missions" rows="3"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">{{ old('missions', $offre->missions) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Profil recherché</label>
                <textarea name="profil_recherche" rows="3"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">{{ old('profil_recherche', $offre->profil_recherche) }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Expérience</label>
                    <input type="text" name="experience" value="{{ old('experience', $offre->experience) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Niveau d'étude</label>
                    <input type="text" name="niveau_etude" value="{{ old('niveau_etude', $offre->niveau_etude) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Salaire</label>
                    <input type="text" name="salaire" value="{{ old('salaire', $offre->salaire) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Email de contact *</label>
                    <input type="email" name="email_contact" value="{{ old('email_contact', $offre->email_contact) }}" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline', $offre->deadline?->format('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Statut *</label>
                <select name="statut" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                    <option value="brouillon" {{ old('statut', $offre->statut) === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="publiee"   {{ old('statut', $offre->statut) === 'publiee'   ? 'selected' : '' }}>Publiée</option>
                    <option value="archivee"  {{ old('statut', $offre->statut) === 'archivee'  ? 'selected' : '' }}>Archivée</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.offres.index') }}"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

@endsection