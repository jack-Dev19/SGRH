
{{-- ============================================================
     SECTION : PARAMÈTRES DES CONGÉS
============================================================ --}}

<section
    id="conges"
    class="space-y-8"
    x-data="{
        modalTypeConge: @js($errors->any()),
        modeTypeConge: 'ajout',
        typeConge: {
            id: '',
            nom: '',
            code: '',
            duree_max: '',
            description: '',
            necessite_justificatif: false,
            statut: 'actif'
        },

        modalJourFerie: false,
        modeJourFerie: 'ajout',

        jourFerie: {
            id: '',
            nom: '',
            date: '',
            description: '',
            est_chome: true,
            statut: 'actif'
        },
        ouvrirAjoutJourFerie() {
            this.modeJourFerie = 'ajout';

            this.jourFerie = {
                id: '',
                nom: '',
                date: '',
                description: '',
                est_chome: true,
                statut: 'actif'
            };

            this.modalJourFerie = true;
        },

        ouvrirModificationJourFerie(jour) {
            this.modeJourFerie = 'modification';

            this.jourFerie = {
                id: jour.id ?? '',
                nom: jour.nom ?? '',
                date: jour.date ?? '',
                description: jour.description ?? '',
                est_chome: Boolean(jour.est_chome),
                statut: jour.statut ?? 'actif'
            };

            this.modalJourFerie = true;
        },

        ouvrirAjoutTypeConge() {
            this.modeTypeConge = 'ajout';
            this.typeConge = {
                id: '',
                nom: '',
                code: '',
                duree_max: '',
                description: '',
                necessite_justificatif: false,
                statut: 'actif'
            };
            this.modalTypeConge = true;
        },
        ouvrirModificationTypeConge(type) {
            this.modeTypeConge = 'modification';

            this.typeConge = {
                id: type.id ?? '',
                nom: type.nom ?? '',
                code: type.code ?? '',
                duree_max: type.duree_max ?? '',
                description: type.description ?? '',
                necessite_justificatif: Boolean(type.necessite_justificatif),
                statut: type.statut ?? 'actif'
            };

            this.modalTypeConge = true;
        }
    }"
    @ouvrir-modal-type-conge.window="ouvrirAjoutTypeConge()"
    @modifier-type-conge.window="ouvrirModificationTypeConge($event.detail)"
    @ouvrir-modal-jour-ferie.window="ouvrirAjoutJourFerie()"
    @modifier-jour-ferie.window="ouvrirModificationJourFerie($event.detail)"
>

    {{-- MESSAGES DE RETOUR --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Veuillez corriger les erreurs suivantes :</p>

            <ul class="mt-2 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- EN-TÊTE --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900">
            Gestion des congés
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Consultez les types de congés et les jours fériés
            configurés dans le système.
        </p>
    </div>

    {{-- ========================================================
         SECTION 1 : TYPES DE CONGÉS
    ========================================================= --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Types de congés
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Les différentes catégories de congés disponibles.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700">
                    {{ $typesConges->count() }} type(s)
                </span>
            
                <button
                    type="button"
                    @click="$dispatch('ouvrir-modal-type-conge')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition"
                >
                    <span>+</span>
                    Ajouter
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nom</th>
                        <th class="px-4 py-3 font-semibold">Code</th>
                        <th class="px-4 py-3 font-semibold">Durée maximale</th>
                        <th class="px-4 py-3 font-semibold">Justificatif</th>
                        <th class="px-4 py-3 font-semibold">Statut</th>
                        <th class="px-4 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($typesConges as $type)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $type->nom }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    {{ $type->code }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $type->duree_max !== null
                                    ? $type->duree_max . ' jour(s)'
                                    : 'Non définie' }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($type->necessite_justificatif)
                                    <span class="text-amber-700 font-medium">
                                        Obligatoire
                                    </span>
                                @else
                                    <span class="text-gray-500">
                                        Non obligatoire
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if ($type->statut === 'actif')
                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        Actif
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        {{ ucfirst($type->statut) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        @click="ouvrirModificationTypeConge(@js($type))"
                                        class="rounded-lg px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50"
                                    >
                                        Modifier
                                    </button>
                                
                                    <form
                                        action="{{ route('admin.parametres.types-conges.destroy', $type->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Voulez-vous vraiment supprimer ce type de congé ?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                
                                        <button
                                            type="submit"
                                            class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50"
                                        >
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                Aucun type de congé enregistré.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================================================
         SECTION 2 : JOURS FÉRIÉS
    ========================================================= --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Jours fériés
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Les jours fériés pris en compte dans le calcul
                    des jours ouvrés.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700">
                    {{ $joursFeries->count() }} jour(s)
                </span>
            
                <button
                    type="button"
                    @click="$dispatch('ouvrir-modal-jour-ferie')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition"
                >
                    <span>+</span>
                    Ajouter
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nom</th>
                        <th class="px-4 py-3 font-semibold">Date</th>
                        <th class="px-4 py-3 font-semibold">Jour chômé</th>
                        <th class="px-4 py-3 font-semibold">Statut</th>
                        <th class="px-4 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($joursFeries as $jour)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="px-4 py-3 font-medium text-gray-800">
                                {{ $jour->nom }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">
                                {{ $jour->date->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($jour->est_chome)
                                    <span class="text-green-700 font-medium">
                                        Oui
                                    </span>
                                @else
                                    <span class="text-gray-500">
                                        Non
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                @if ($jour->statut === 'actif')
                                    <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        Actif
                                    </span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        {{ ucfirst($jour->statut) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                {{-- MODIFIER --}}
                                <button
                                    type="button"
                                    @click="$dispatch('modifier-jour-ferie', @js([
                                        'id' => $jour->id,
                                        'nom' => $jour->nom,
                                        'date' => $jour->date->format('Y-m-d'),
                                        'description' => $jour->description,
                                        'est_chome' => $jour->est_chome,
                                        'statut' => $jour->statut,
                                    ]))"
                                    class="rounded-lg px-3 py-1.5 text-xs font-medium text-indigo-600 hover:bg-indigo-50"
                                >
                                    Modifier
                                </button>

                                {{-- SUPPRIMER --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.parametres.jours-feries.destroy', $jour->id) }}"
                                    class="inline-block"
                                    onsubmit="return confirm('Voulez-vous vraiment supprimer ce jour férié ?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50"
                                    >
                                        Supprimer
                                    </button>
                                </form>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                Aucun jour férié enregistré.
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>
    </div>


    {{-- MODALE : AJOUTER UN TYPE DE CONGÉ --}}
    <div
        x-show="modalTypeConge"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @click.self="modalTypeConge = false"
        @keydown.escape.window="modalTypeConge = false"
    >
        <div
            x-show="modalTypeConge"
            x-transition
            class="w-full max-w-xl rounded-2xl bg-white shadow-xl"
        >
            {{-- EN-TÊTE --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        <span x-text="modeTypeConge === 'ajout'
                            ? 'Nouveau type de congé'
                            : 'Modifier le type de congé'"></span>
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Renseignez les informations du nouveau type.
                    </p>
                </div>

                <button
                    type="button"
                    @click="modalTypeConge = false"
                    class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700"
                >
                    &times;
                </button>
            </div>

            {{-- FORMULAIRE --}}
            <form
                :action="modeTypeConge === 'ajout'
                    ? '{{ route('admin.parametres.types-conges.store') }}'
                    : '{{ url('/admin/parametres/types-conges') }}/' + typeConge.id"
                method="POST"
                class="space-y-5 p-6"
            >                    
                <template x-if="modeTypeConge === 'modification'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                @csrf

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- NOM --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nom du congé *
                        </label>
                        <input
                            type="text"
                            name="nom"
                            x-model="typeConge.nom"
                            value="{{ old('nom') }}"
                            required
                            maxlength="100"
                            placeholder="Ex : Congé annuel"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('nom')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CODE --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Code *
                        </label>
                        <input
                            type="text"
                            name="code"
                            x-model="typeConge.code"
                            value="{{ old('code') }}"
                            required
                            maxlength="30"
                            placeholder="Ex : CA"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- DURÉE MAXIMALE --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Durée maximale (jours)
                        </label>
                        <input
                            type="number"
                            name="duree_max"
                            x-model="typeConge.duree_max"
                            value="{{ old('duree_max') }}"
                            min="1"
                            placeholder="Ex : 30"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        @error('duree_max')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- STATUT --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Statut *
                        </label>
                        <select
                            name="statut"
                            x-model="typeConge.statut"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="actif" @selected(old('statut', 'actif') === 'actif')>
                                Actif
                            </option>
                            <option value="inactif" @selected(old('statut') === 'inactif')>
                                Inactif
                            </option>
                        </select>
                    </div>

                </div>

                {{-- DESCRIPTION --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea
                        name="description"
                        x-model="typeConge.description"
                        rows="3"
                        placeholder="Description facultative..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>
                </div>

                {{-- JUSTIFICATIF --}}
                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        name="necessite_justificatif"
                        value="1"
                        x-model="typeConge.necessite_justificatif"
                        @checked(old('necessite_justificatif'))
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <label class="text-sm text-gray-700">
                        Un justificatif est obligatoire
                    </label>
                </div>

                {{-- BOUTONS --}}
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <button
                        type="button"
                        @click="modalTypeConge = false"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        <span x-text="modeTypeConge === 'ajout' ? 'Enregistrer' : 'Enregistrer les modifications'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODALE : AJOUTER UN JOUR FÉRIÉ --}}
    <div
        x-show="modalJourFerie"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        @click.self="modalJourFerie = false"
        @keydown.escape.window="modalJourFerie = false"
    >
        <div
            x-show="modalJourFerie"
            x-transition
            class="w-full max-w-xl rounded-2xl bg-white shadow-xl"
        >
            {{-- EN-TÊTE --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <span x-text="modeJourFerie === 'ajout'
                        ? 'Ajouter un jour férié'
                        : 'Modifier le jour férié'"></span>
                </h3>

                <button
                    type="button"
                    @click="modalJourFerie = false"
                    class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700"
                >
                    &times;
                </button>
            </div>

            {{-- FORMULAIRE --}}
            <form
                method="POST"
                x-bind:action="modeJourFerie === 'ajout'
                    ? '{{ route('admin.parametres.jours-feries.store') }}'
                    : '{{ url('/admin/parametres/jours-feries') }}/' + jourFerie.id"
                class="space-y-5 p-6"
            >
                @csrf

                <input
                    type="hidden"
                    name="_method"
                    value="PUT"
                    x-bind:disabled="modeJourFerie === 'ajout'"
                >

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Nom du jour férié *
                    </label>
                    <input
                        type="text"
                        name="nom"
                        x-model="jourFerie.nom"
                        required
                        maxlength="150"
                        placeholder="Ex : Fête nationale"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Date *
                    </label>
                    <input
                        type="date"
                        name="date"
                        x-model="jourFerie.date"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea
                        name="description"
                        x-model="jourFerie.description"
                        rows="3"
                        placeholder="Description facultative..."
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    ></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Statut *
                    </label>
                    <select
                        name="statut"
                        x-model="jourFerie.statut"
                        required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <input
                        type="checkbox"
                        name="est_chome"
                        value="1"
                        x-model="jourFerie.est_chome"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    <label class="text-sm text-gray-700">
                        Ce jour est chômé
                    </label>
                </div>

                {{-- BOUTONS --}}
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <button
                        type="button"
                        @click="modalJourFerie = false"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        <span x-text="modeJourFerie === 'ajout'
                            ? 'Enregistrer'
                            : 'Enregistrer les modifications'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>
