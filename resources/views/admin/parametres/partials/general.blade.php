
{{-- ============================================================
     SECTION : PARAMÈTRES GÉNÉRAUX
     Fichier : partials/general.blade.php
============================================================ --}}

<section id="general" class="space-y-8">

    {{-- PRÉSENTATION --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900">
            Paramètres généraux
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Gérez les informations de votre entreprise et les préférences
            générales de votre application.
        </p>
    </div>

    {{-- FORMULAIRE D'ENREGISTREMENT --}}
    <form
        action="{{ route('admin.parametres.update') }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-8"
    >
        @csrf

        {{-- INFORMATIONS DE L'ENTREPRISE --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Informations de l'entreprise
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Informations utilisées dans les différentes parties du SGRH.
                </p>
            </div>

            {{-- NOM DE L'ENTREPRISE --}}
            <div>
                <label
                    for="nom_entreprise"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Nom de l'entreprise
                </label>

                <input
                    type="text"
                    id="nom_entreprise"
                    name="nom_entreprise"
                    value="{{ old('nom_entreprise', $parametresGeneraux['nom_entreprise'] ?? '') }}"
                    placeholder="Saisir le nom de l'entreprise"
                    required
                    class="h-12 w-full rounded-xl border border-gray-300 px-4 text-base focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('nom_entreprise')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- LOGO DE L'ENTREPRISE --}}
            <div>
                <label
                    for="logo_entreprise"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    Logo de l'entreprise
                </label>

                {{-- APERÇU DU LOGO --}}
                <div class="mb-4">

                    @if (!empty($parametresGeneraux['logo_entreprise']))
                        <img
                            id="logoPreview"
                            src="{{ asset('storage/' . $parametresGeneraux['logo_entreprise']) }}"
                            alt="Logo de l'entreprise"
                            class="h-24 w-auto max-w-xs rounded-xl border border-gray-200 p-3 object-contain"
                        >
                    @else
                        <img
                            id="logoPreview"
                            src=""
                            alt="Aperçu du logo"
                            class="hidden h-24 w-auto max-w-xs rounded-xl border border-gray-200 p-3 object-contain"
                        >

                        <p id="logoPlaceholder" class="text-sm text-gray-400">
                            Aucun logo enregistré pour le moment.
                        </p>
                    @endif

                </div>

                {{-- SÉLECTION D'UN NOUVEAU LOGO --}}
                <input
                    type="file"
                    id="logo_entreprise"
                    name="logo_entreprise"
                    accept="image/png,image/jpeg,image/webp"
                    class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"
                >

                <p class="mt-2 text-xs text-gray-500">
                    Formats acceptés : PNG, JPG et WEBP. Taille maximale : 2 Mo.
                </p>

                @error('logo_entreprise')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- PRÉFÉRENCES RÉGIONALES --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Préférences régionales
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Définissez les formats utilisés dans l'application.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- FUSEAU HORAIRE --}}
                <div>
                    <label
                        for="fuseau_horaire"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Fuseau horaire
                    </label>

                    <select
                        id="fuseau_horaire"
                        name="fuseau_horaire"
                        required
                        class="h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ([
                            'Indian/Antananarivo' => 'Madagascar (UTC+3)',
                            'Africa/Nairobi' => 'Nairobi (UTC+3)',
                            'Europe/Paris' => 'Paris',
                            'UTC' => 'UTC',
                        ] as $valeur => $libelle)
                            <option
                                value="{{ $valeur }}"
                                @selected(old('fuseau_horaire', $parametresGeneraux['fuseau_horaire'] ?? 'Indian/Antananarivo') === $valeur)
                            >
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>

                    @error('fuseau_horaire')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- LANGUE --}}
                <div>
                    <label
                        for="langue"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Langue
                    </label>

                    <select
                        id="langue"
                        name="langue"
                        required
                        class="h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ([
                            'fr' => 'Français',
                            // 'mg' => 'Malagasy',
                            // 'en' => 'Anglais',
                        ] as $valeur => $libelle)
                            <option
                                value="{{ $valeur }}"
                                @selected(old('langue', $parametresGeneraux['langue'] ?? 'fr') === $valeur)
                            >
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>

                    @error('langue')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DEVISE --}}
                <div>
                    <label
                        for="devise"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Devise
                    </label>

                    <select
                        id="devise"
                        name="devise"
                        required
                        class="h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ([
                            'MGA' => 'Ariary (MGA)',
                            // 'EUR' => 'Euro (EUR)',
                            // 'USD' => 'Dollar américain (USD)',
                        ] as $valeur => $libelle)
                            <option
                                value="{{ $valeur }}"
                                @selected(old('devise', $parametresGeneraux['devise'] ?? 'MGA') === $valeur)
                            >
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>

                    @error('devise')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FORMAT DE DATE --}}
                <div>
                    <label
                        for="format_date"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Format de date
                    </label>

                    <select
                        id="format_date"
                        name="format_date"
                        required
                        class="h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        @foreach ([
                            'd/m/Y' => 'JJ/MM/AAAA',
                            'Y-m-d' => 'AAAA-MM-JJ',
                            'm/d/Y' => 'MM/JJ/AAAA',
                        ] as $valeur => $libelle)
                            <option
                                value="{{ $valeur }}"
                                @selected(old('format_date', $parametresGeneraux['format_date'] ?? 'd/m/Y') === $valeur)
                            >
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>

                    @error('format_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- ENREGISTREMENT --}}
        <div class="flex justify-end border-gray-200 pt-5">
            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Enregistrer les paramètres généraux
            </button>
        </div>

    </form>

    {{-- PRÉVISUALISATION DU NOUVEAU LOGO --}}
    <script>
        // Prévisualiser le nouveau logo avant son enregistrement
        document.getElementById('logo_entreprise')?.addEventListener('change', function (event) {
            const fichier = event.target.files[0];

            if (!fichier) return;

            const apercu = document.getElementById('logoPreview');
            const placeholder = document.getElementById('logoPlaceholder');

            apercu.src = URL.createObjectURL(fichier);
            apercu.classList.remove('hidden');

            if (placeholder) {
                placeholder.classList.add('hidden');
            }
        });
    </script>

</section>