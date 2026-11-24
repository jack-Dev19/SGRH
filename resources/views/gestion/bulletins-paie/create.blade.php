@extends('layouts.dashboard')

@section('title', 'Générer un bulletin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-6">

    <div class="mb-7">
        <a href="{{ route('gestion.bulletins-paie.index') }}"
           class="text-sm font-semibold text-slate-500 hover:text-slate-900">
            ← Retour aux bulletins
        </a>

        <h1 class="mt-4 text-3xl font-extrabold text-slate-900">
            Générer un bulletin de paie
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Le bulletin sera créé en brouillon et ne sera pas encore visible par l'employé.
        </p>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('gestion.bulletins-paie.store') }}"
          class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">

        @csrf

        <div>
            <label for="contrat_id" class="mb-2 block text-sm font-bold text-slate-700">
                Contrat de l'employé *
            </label>

            <select name="contrat_id" id="contrat_id" required
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:ring-2 focus:ring-slate-800">
                <option value="">Sélectionner un contrat</option>

                @foreach($contrats as $contrat)
                    <option value="{{ $contrat->id }}"
                        @selected(old('contrat_id') == $contrat->id)>
                        {{ $contrat->employe?->nom_complet }}
                        — {{ $contrat->employe?->matricule }}
                        — {{ $contrat->numero }}
                        ({{ $contrat->type }})
                    </option>
                @endforeach
            </select>

            @if($contrats->isEmpty())
                <p class="mt-2 text-sm text-amber-600">
                    Aucun contrat actif disponible.
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="mois" class="mb-2 block text-sm font-bold text-slate-700">
                    Mois *
                </label>

                <select name="mois" id="mois" required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
                    <option value="">Choisir le mois</option>
                    @foreach([
                        1 => 'Janvier', 2 => 'Février', 3 => 'Mars',
                        4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre',
                        10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                    ] as $numero => $nom)
                        <option value="{{ $numero }}" @selected(old('mois') == $numero)>
                            {{ $nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="annee" class="mb-2 block text-sm font-bold text-slate-700">
                    Année *
                </label>

                <input type="number" name="annee" id="annee"
                       min="2020" max="2100" required
                       value="{{ old('annee', now()->year) }}"
                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
            </div>
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
            <p class="text-sm font-bold text-amber-800">Attention</p>
            <p class="mt-1 text-sm text-amber-700">
                Vérifiez le contrat et la période avant de générer le bulletin.
                La publication sera effectuée séparément après vérification.
            </p>
        </div>

        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-3">
            <a href="{{ route('gestion.bulletins-paie.index') }}"
               class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-bold text-slate-600 hover:bg-slate-50">
                Annuler
            </a>

            <button type="submit" @disabled($contrats->isEmpty())
                    class="rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white hover:bg-slate-800 disabled:opacity-50">
                Générer le brouillon
            </button>
        </div>
    </form>
</div>
@endsection