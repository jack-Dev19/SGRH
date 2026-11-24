{{-- ============================================================
     SECTION : PARAMÈTRES DE SÉCURITÉ
     Fichier : partials/securite.blade.php
============================================================ --}}

<section id="securite" class="space-y-6">

    <div>
        <h2 class="text-lg font-semibold text-gray-900">
            Sécurité de connexion
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Configurez les limites de tentatives de connexion et la durée de verrouillage.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.parametres.update') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="securite_form" value="1">

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
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Nombre d'échecs autorisés avant le verrouillage temporaire.
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
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <p class="mt-1 text-xs text-gray-500">
                    Durée pendant laquelle la connexion est bloquée après trop d'échecs.
                </p>

                @error('duree_verrouillage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex justify-end border-t border-gray-200 pt-4">
            <button
                type="submit"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Enregistrer les paramètres
            </button>
        </div>
    </form>

</section>