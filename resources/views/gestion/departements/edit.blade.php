@extends('layouts.dashboard')

@section('title', 'Modifier — ' . $departement->nom)

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.departements.index') }}" class="hover:text-purple-600">Départements</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Modifier</span>
    </div>

    {{-- Carte --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Modifier le département</h1>
            <p class="text-purple-100 text-sm mt-1">{{ $departement->nom }}</p>
        </div>

        <form action="{{ route('gestion.departements.update', $departement) }}" method="POST" class="p-6 space-y-5">
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

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- SECTION 1 : INFORMATIONS DU DÉPARTEMENT                    --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div>
                <h2 class="text-sm font-bold text-blue-600 uppercase mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-extrabold">1</span>
                    Informations du département
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Nom du département *
                        </label>
                        <input type="text" name="nom" value="{{ old('nom', $departement->nom) }}" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Description
                        </label>
                        <textarea name="description" rows="2"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">{{ old('description', $departement->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                                Responsable
                            </label>
                            <select name="responsable_id"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="">— Aucun —</option>
                                @foreach($chefs as $chef)
                                    <option value="{{ $chef->id }}" {{ old('responsable_id', $departement->responsable_id) == $chef->id ? 'selected' : '' }}>
                                        {{ $chef->name }} ({{ $chef->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                                Statut
                            </label>
                            <select name="statut"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="1" {{ old('statut', $departement->statut) == 1 ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ old('statut', $departement->statut) == 0 ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- SECTION 2 : POSTES ASSOCIÉS                                --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div class="pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-bold text-blue-600 uppercase flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-extrabold">2</span>
                            Postes associés à ce département
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Les postes utilisés par des employés seront désactivés au lieu d'être supprimés.
                        </p>
                    </div>
                    <button type="button" onclick="ajouterPoste()"
                        class="inline-flex items-center gap-1.5 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg transition shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajouter un poste
                    </button>
                </div>

                {{-- Liste des postes existants --}}
                <div id="postes-container" class="space-y-2">
                    @foreach($departement->postes as $i => $poste)
                        <div class="poste-item bg-slate-50 rounded-xl p-3 flex items-center gap-2 border border-slate-200">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-xs shrink-0">
                                <span class="poste-number">{{ $i + 1 }}</span>
                            </div>
                            <input type="hidden" name="postes[{{ $i }}][id]" value="{{ $poste->id }}">
                            <input type="text" name="postes[{{ $i }}][nom]" value="{{ $poste->nom }}"
                                class="flex-1 px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">

                            {{-- Badge si poste utilisé --}}
                            @if($poste->employes()->count() > 0)
                                <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-amber-100 text-amber-700 shrink-0"
                                    title="{{ $poste->employes()->count() }} employé(s) utilisent ce poste">
                                    {{ $poste->employes()->count() }} utilis.
                                </span>
                            @endif

                            <button type="button" onclick="supprimerPoste(this)" title="Supprimer"
                                class="p-2 rounded-lg text-red-500 hover:bg-red-100 transition shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- État vide --}}
                <div id="postes-empty" class="{{ $departement->postes->isEmpty() ? '' : 'hidden' }} text-center py-8 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm text-slate-400 font-medium">Aucun poste associé</p>
                    <p class="text-xs text-slate-400 mt-1">
                        Cliquez sur "Ajouter un poste" pour commencer
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.departements.index') }}"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Template pour un nouveau poste --}}
<template id="poste-template">
    <div class="poste-item bg-slate-50 rounded-xl p-3 flex items-center gap-2 border border-slate-200">
        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs shrink-0">
            <span class="poste-number">#</span>
        </div>
        <input type="text" name="postes[__INDEX__][nom]" placeholder="Nom du poste"
            class="flex-1 px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        <button type="button" onclick="supprimerPoste(this)" title="Supprimer"
            class="p-2 rounded-lg text-red-500 hover:bg-red-100 transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</template>

@endsection

@push('scripts')
<script>
    let posteIndex = {{ $departement->postes->count() }};

    function ajouterPoste() {
        const container = document.getElementById('postes-container');
        const empty     = document.getElementById('postes-empty');
        const template  = document.getElementById('poste-template');

        empty.classList.add('hidden');

        const html = template.innerHTML.replace(/__INDEX__/g, posteIndex);
        const wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        const item = wrap.firstChild;

        container.appendChild(item);
        posteIndex++;

        item.querySelector('input').focus();
        mettreAJourNumeros();
    }

    function supprimerPoste(btn) {
        const item = btn.closest('.poste-item');
        const idInput = item.querySelector('input[type="hidden"]');

        // Si c'est un poste existant utilisé, avertir
        if (idInput) {
            const badge = item.querySelector('.bg-amber-100');
            if (badge && !confirm('Ce poste est utilisé par des employés. Il sera désactivé au lieu d\'être supprimé. Continuer ?')) {
                return;
            }
        }

        item.remove();
        mettreAJourNumeros();

        const container = document.getElementById('postes-container');
        if (container.children.length === 0) {
            document.getElementById('postes-empty').classList.remove('hidden');
        }
    }

    function mettreAJourNumeros() {
        document.querySelectorAll('.poste-item').forEach((item, index) => {
            item.querySelector('.poste-number').textContent = index + 1;
        });
    }
</script>
@endpush