
{{-- ============================================================
     SECTION : PARAMÈTRES GÉNÉRAUX
     Fichier : partials/general.blade.php
============================================================ --}}

<section id="general" class="space-y-6">

    {{-- Présentation --}}
    <div>
        <h2 class="text-lg font-semibold text-slate-800">
            Paramètres généraux
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Gérez les informations de votre entreprise et les préférences
            générales de votre application.
        </p>
    </div>

    {{-- Formulaire d'enregistrement --}}
    <form
    action="{{ route('admin.parametres.update') }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-6"
    >
        @csrf

        {{-- Informations de l'entreprise --}}
        <div class="border border-slate-200 rounded-xl p-5 space-y-5">

            <div>
                <h3 class="font-semibold text-slate-800">
                    Informations de l'entreprise
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Informations utilisées dans les différentes parties du SGRH.
                </p>
            </div>

            {{-- Nom de l'entreprise --}}
            <div>
                <label for="nom_entreprise"
                       class="block text-sm font-medium text-slate-700 mb-1">
                    Nom de l'entreprise
                </label>

                <input
                    type="text"
                    id="nom_entreprise"
                    name="nom_entreprise"
                    value="{{ old('nom_entreprise', $parametresGeneraux['nom_entreprise'] ?? '') }}"
                    placeholder="Saisir le nom de l'entreprise"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-500"
                >

                @error('nom_entreprise')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Logo de l'entreprise --}}
            <div>
                <label for="logo_entreprise"
                       class="block text-sm font-medium text-slate-700 mb-1">
                    Logo de l'entreprise
                </label>
            
                {{-- Aperçu du logo déjà enregistré --}}
                <div class="mb-3">
                    @if (!empty($parametresGeneraux['logo_entreprise']))
                        <img
                            id="logoPreview"
                            src="{{ asset('storage/' . $parametresGeneraux['logo_entreprise']) }}"
                            alt="Logo de l'entreprise"
                            class="h-24 w-auto max-w-xs object-contain rounded-lg border border-slate-200 p-2"
                        >
                    @else
                        <img
                            id="logoPreview"
                            src=""
                            alt="Aperçu du logo"
                            class="hidden h-24 w-auto max-w-xs object-contain rounded-lg border border-slate-200 p-2"
                        >
                        <p id="logoPlaceholder" class="text-xs text-slate-400">
                            Aucun logo enregistré pour le moment.
                        </p>
                    @endif
                </div>
            
                {{-- Sélection d'un nouveau logo --}}
                <input
                    type="file"
                    id="logo_entreprise"
                    name="logo_entreprise"
                    accept="image/png,image/jpeg,image/webp"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                >
            
                <p class="text-xs text-slate-400 mt-1">
                    Formats acceptés : PNG, JPG et WEBP. Taille maximale : 2 Mo.
                </p>
            
                @error('logo_entreprise')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


        </div>

        {{-- Préférences régionales --}}
        <div class="border border-slate-200 rounded-xl p-5 space-y-5">

            <div>
                <h3 class="font-semibold text-slate-800">
                    Préférences régionales
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Définissez les formats utilisés dans l'application.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Fuseau horaire --}}
                <div>
                    <label for="fuseau_horaire"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Fuseau horaire
                    </label>

                    <select
                        id="fuseau_horaire"
                        name="fuseau_horaire"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
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
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Langue --}}
                <div>
                    <label for="langue"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Langue
                    </label>

                    <select
                        id="langue"
                        name="langue"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
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
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Devise --}}
                <div>
                    <label for="devise"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Devise
                    </label>

                    <select
                        id="devise"
                        name="devise"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
                    >
                        @foreach ([
                            'MGA' => 'Ariary (MGA)',
                            'EUR' => 'Euro (EUR)',
                            'USD' => 'Dollar américain (USD)',
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
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Format de date --}}
                <div>
                    <label for="format_date"
                           class="block text-sm font-medium text-slate-700 mb-1">
                        Format de date
                    </label>

                    <select
                        id="format_date"
                        name="format_date"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
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
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Bouton temporaire du formulaire Général --}}
        <div class="flex justify-end">
            <button
                type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"
            >
                Enregistrer les paramètres généraux
            </button>
        </div>

    </form>
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
