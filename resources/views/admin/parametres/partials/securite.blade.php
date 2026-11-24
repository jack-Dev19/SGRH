
{{-- ============================================================
     SECTION : PARAMÈTRES DE SÉCURITÉ
============================================================ --}}

<section id="securite" class="space-y-8">

    {{-- EN-TÊTE --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900">
            Sécurité du système
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Configurez la sécurité des connexions et les exigences des mots de passe.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.parametres.update') }}" class="space-y-8">
        @csrf

        <input type="hidden" name="securite_form" value="1">

        {{-- SECTION 1 : VERROUILLAGE --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Sécurité de connexion
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Définissez les limites de tentatives avant le blocage temporaire.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                <div>
                    <label for="tentatives_max" class="block text-sm font-medium text-gray-700">
                        Nombre maximal de tentatives
                    </label>

                    <input
                        type="number"
                        id="tentatives_max"
                        name="tentatives_max"
                        min="1"
                        max="20"
                        value="{{ old('tentatives_max', $securite['tentatives_max'] ?? 5) }}"
                        required
                        class="mt-2 block h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >

                    <p class="mt-2 text-xs text-gray-500">
                        Nombre d'échecs autorisés avant le verrouillage.
                    </p>

                    @error('tentatives_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duree_verrouillage" class="block text-sm font-medium text-gray-700">
                        Durée du verrouillage (minutes)
                    </label>

                    <input
                        type="number"
                        id="duree_verrouillage"
                        name="duree_verrouillage"
                        min="1"
                        max="1440"
                        value="{{ old('duree_verrouillage', $securite['duree_verrouillage'] ?? 15) }}"
                        required
                        class="mt-2 block h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                    >

                    <p class="mt-2 text-xs text-gray-500">
                        Temps pendant lequel la connexion est bloquée.
                    </p>

                    @error('duree_verrouillage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- SECTION 2 : POLITIQUE DES MOTS DE PASSE --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Politique des mots de passe
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Définissez les exigences minimales pour les nouveaux mots de passe.
                </p>
            </div>

            {{-- LONGUEUR MINIMALE --}}
            <div class="max-w-md">
                <label for="longueur_min" class="block text-sm font-medium text-gray-700">
                    Longueur minimale du mot de passe
                </label>

                <input
                    type="number"
                    id="longueur_min"
                    name="longueur_min"
                    min="8"
                    max="64"
                    value="{{ old('longueur_min', $securite['longueur_min'] ?? 8) }}"
                    required
                    class="mt-2 block h-12 w-full rounded-xl border border-gray-300 bg-white px-4 text-base text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
                >

                <p class="mt-2 text-xs text-gray-500">
                    Minimum 8 caractères et maximum 64 caractères.
                </p>

                @error('longueur_min')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-gray-100 pt-5 space-y-4">

                <p class="text-sm font-medium text-gray-700">
                    Exigences de complexité
                </p>

                {{-- MAJUSCULE --}}
                <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50">
                    <span>
                        <span class="block text-sm font-medium text-gray-800">
                            Lettre majuscule
                        </span>
                        <span class="block text-xs text-gray-500">
                            Au moins une lettre majuscule (A-Z).
                        </span>
                    </span>
                    <input type="checkbox" name="exiger_majuscule" value="1"
                        @checked(old('exiger_majuscule', $securite['exiger_majuscule'] ?? true))
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                {{-- MINUSCULE --}}
                <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50">
                    <span>
                        <span class="block text-sm font-medium text-gray-800">
                            Lettre minuscule
                        </span>
                        <span class="block text-xs text-gray-500">
                            Au moins une lettre minuscule (a-z).
                        </span>
                    </span>
                    <input type="checkbox" name="exiger_minuscule" value="1"
                        @checked(old('exiger_minuscule', $securite['exiger_minuscule'] ?? true))
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                {{-- CHIFFRE --}}
                <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50">
                    <span>
                        <span class="block text-sm font-medium text-gray-800">
                            Chiffre obligatoire
                        </span>
                        <span class="block text-xs text-gray-500">
                            Au moins un chiffre (0-9).
                        </span>
                    </span>
                    <input type="checkbox" name="exiger_chiffre" value="1"
                        @checked(old('exiger_chiffre', $securite['exiger_chiffre'] ?? true))
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

                {{-- CARACTÈRE SPÉCIAL --}}
                <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50">
                    <span>
                        <span class="block text-sm font-medium text-gray-800">
                            Caractère spécial obligatoire
                        </span>
                        <span class="block text-xs text-gray-500">
                            Au moins un caractère spécial (@, #, !, $, etc.).
                        </span>
                    </span>
                    <input type="checkbox" name="exiger_special" value="1"
                        @checked(old('exiger_special', $securite['exiger_special'] ?? true))
                        class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </label>

            </div>
        </div>

        {{-- ENREGISTREMENT --}}
        <div class="flex justify-end border-t border-gray-200 pt-5">
            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Enregistrer les paramètres
            </button>
        </div>

    </form>

</section>
