@extends('layouts.dashboard')

@section('title', 'Nouvelle offre d\'emploi')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.offres.index') }}" class="hover:text-purple-600 transition">Offres</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Nouvelle offre</span>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Créer une offre d'emploi</h1>
            <p class="text-purple-100 text-sm mt-1">L'offre sera publiée sur la page d'accueil.</p>
        </div>

        <form action="{{ route('gestion.offres.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

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
                <input type="text" name="titre" value="{{ old('titre') }}" required
                    placeholder="Ex : Développeur Full Stack"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Type de contrat *</label>
                    <select name="type_contrat" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                        @foreach(['CDI', 'CDD', 'Stage', 'Freelance', 'Intérim'] as $type)
                            <option value="{{ $type }}" {{ old('type_contrat') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Lieu *</label>
                    <input type="text" name="lieu" value="{{ old('lieu', 'Casablanca') }}" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Description *</label>
                <textarea name="description" rows="4" required
                    placeholder="Décrivez le poste en quelques lignes…"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Missions (optionnel)</label>
                <textarea name="missions" rows="3"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">{{ old('missions') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Profil recherché (optionnel)</label>
                <textarea name="profil_recherche" rows="3"
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">{{ old('profil_recherche') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Expérience</label>
                    <input type="text" name="experience" value="{{ old('experience') }}"
                        placeholder="Ex : 3+ ans"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Niveau d'étude</label>
                    <input type="text" name="niveau_etude" value="{{ old('niveau_etude') }}"
                        placeholder="Ex : Bac+5"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Salaire</label>
                    <input type="text" name="salaire" value="{{ old('salaire') }}"
                        placeholder="Ex : 8000-12000 MAD"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Email de contact *</label>
                    <input type="email" name="email_contact" value="{{ old('email_contact', 'rh@digitalagency.ma') }}" required
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Deadline</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">Statut *</label>
                <select name="statut" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl">
                    <option value="brouillon" {{ old('statut') === 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="publiee"   {{ old('statut') === 'publiee'   ? 'selected' : '' }}>Publiée (visible sur le site)</option>
                    <option value="archivee"  {{ old('statut') === 'archivee'  ? 'selected' : '' }}>Archivée</option>
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
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

@endsection