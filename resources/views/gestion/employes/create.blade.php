@extends('layouts.dashboard')

@section('title', 'Nouvel employé')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Fil d'Ariane --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('gestion.employes.index') }}" class="hover:text-purple-600">Employés</a>
        <span>/</span>
        <span class="font-semibold text-slate-700">Nouveau</span>
    </div>

    {{-- Carte --}}
    <div class="bg-white rounded-2xl border border-purple-100 overflow-hidden shadow-sm">

        <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-6 text-white">
            <h1 class="text-xl font-extrabold">Nouvel employé</h1>
            <p class="text-purple-100 text-sm mt-1">
                Le matricule sera généré automatiquement (EMP-XXX).
            </p>
        </div>

        <form action="{{ route('gestion.employes.store') }}" method="POST" class="p-6 space-y-5">
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

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- SECTION 1 : INFOS PERSONNELLES                             --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div>
                <h2 class="text-sm font-bold text-purple-600 uppercase mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-xs font-extrabold">1</span>
                    Informations personnelles
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Prénom *
                        </label>
                        <input type="text" name="prenom" value="{{ old('prenom') }}" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Nom *
                        </label>
                        <input type="text" name="nom" value="{{ old('nom') }}" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Téléphone
                        </label>
                        <input type="tel" name="telephone" value="{{ old('telephone') }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Date de naissance
                        </label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Date d'embauche
                        </label>
                        <input type="date" name="date_embauche" value="{{ old('date_embauche', now()->format('Y-m-d')) }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Adresse
                        </label>
                        <input type="text" name="adresse" value="{{ old('adresse') }}"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- SECTION 2 : AFFECTATION                                    --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            <div class="pt-4 border-t border-slate-100">
                <h2 class="text-sm font-bold text-purple-600 uppercase mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-xs font-extrabold">2</span>
                    Affectation
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Département --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Département *
                        </label>
                        <select name="departement_id" id="departement-select" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                            <option value="">— Sélectionner un département —</option>
                            @foreach($departements as $dept)
                                <option value="{{ $dept->id }}" {{ old('departement_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Poste (dynamique) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Poste *
                        </label>
                        <select name="poste_id" id="poste-select" required disabled
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none disabled:bg-slate-50 disabled:text-slate-400">
                            <option value="">— Sélectionnez d'abord un département —</option>
                        </select>
                        <p class="text-xs text-slate-400 mt-1">
                            Les postes affichés dépendent du département choisi.
                        </p>
                    </div>

                    {{-- Statut --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5">
                            Statut *
                        </label>
                        <select name="statut" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none">
                            <option value="actif" {{ old('statut', 'actif') === 'actif' ? 'selected' : '' }}>Actif</option>
                            <option value="inactif" {{ old('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                            <option value="sorti" {{ old('statut') === 'sorti' ? 'selected' : '' }}>Sorti</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('gestion.employes.index') }}"
                    class="px-5 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-800">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Créer l'employé
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const departementSelect = document.getElementById('departement-select');
        const posteSelect       = document.getElementById('poste-select');
        const postesData        = @json($postes ?? []);

        // Récupérer les anciennes valeurs (en cas d'erreur de validation)
        const oldDepartement = "{{ old('departement_id') }}";
        const oldPoste       = "{{ old('poste_id') }}";

        function filtrerPostes() {
            const deptId = departementSelect.value;

            posteSelect.innerHTML = '';
            posteSelect.disabled  = true;

            // Aucun département sélectionné
            if (!deptId) {
                posteSelect.innerHTML = '<option value="">— Sélectionnez d\'abord un département —</option>';
                return;
            }

            // Filtrer les postes du département
            const postesDuDept = postesData.filter(p => p.departement_id === deptId);

            if (postesDuDept.length === 0) {
                posteSelect.innerHTML = '<option value="">— Aucun poste disponible pour ce département —</option>';
                return;
            }

            posteSelect.disabled  = false;
            posteSelect.innerHTML = '<option value="">— Sélectionner un poste —</option>';

            postesDuDept.forEach(poste => {
                const option = document.createElement('option');
                option.value = poste.id;
                option.textContent = poste.nom;

                // Restaurer l'ancien poste si présent
                if (oldPoste && oldPoste == poste.id) {
                    option.selected = true;
                }

                posteSelect.appendChild(option);
            });
        }

        // Initial : si un département est pré-sélectionné
        if (oldDepartement) {
            departementSelect.value = oldDepartement;
        }
        filtrerPostes();

        // Au changement de département
        departementSelect.addEventListener('change', filtrerPostes);
    });
</script>
@endpush