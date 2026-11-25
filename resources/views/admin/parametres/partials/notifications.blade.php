{{-- ============================================================
     SECTION : PARAMÈTRES DES NOTIFICATIONS
============================================================ --}}

<section id="notifications" class="space-y-8">

    {{-- EN-TÊTE --}}
    <div>
        <h2 class="text-lg font-semibold text-gray-900">
            Notifications
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Configurez les canaux de notification et les alertes envoyées aux utilisateurs.
        </p>
    </div>

    <form
        action="{{ route('admin.parametres.update') }}"
        method="POST"
        class="space-y-8"
    >
        @csrf

        <input type="hidden" name="notifications_form" value="1">

        {{-- SECTION 1 : CANAUX --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Canaux de notification
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Choisissez les moyens de communication disponibles.
                </p>
            </div>

            {{-- APPLICATION --}}
            <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50 transition">
                <span>
                    <span class="block text-sm font-medium text-gray-800">
                        Notifications dans l'application
                    </span>
                    <span class="block mt-1 text-xs text-gray-500">
                        Alertes affichées directement dans le SGRH.
                    </span>
                </span>

                <input
                    type="checkbox"
                    name="notifications[application]"
                    value="1"
                    @checked($notificationsCanaux['application'] ?? true)
                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
            </label>

            {{-- E-MAIL ET SMS CONSERVÉS DÉSACTIVÉS --}}
            {{--
            <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4">
                <span>
                    <span class="block text-sm font-medium text-gray-800">
                        Notifications par e-mail
                    </span>
                    <span class="block mt-1 text-xs text-gray-500">
                        Envoi de messages par courrier électronique.
                    </span>
                </span>
                <input type="checkbox" name="notifications[email]" value="1"
                    @checked($notificationsCanaux['email'] ?? false)
                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            </label>

            <label class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4">
                <span>
                    <span class="block text-sm font-medium text-gray-800">
                        Notifications par SMS
                    </span>
                    <span class="block mt-1 text-xs text-gray-500">
                        Envoi d'alertes par téléphone via un fournisseur SMS.
                    </span>
                </span>
                <input type="checkbox" name="notifications[sms]" value="1"
                    @checked($notificationsCanaux['sms'] ?? false)
                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            </label>
            --}}

        </div>

        {{-- SECTION 2 : MODULES --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 space-y-5">

            <div>
                <h3 class="text-base font-semibold text-gray-800">
                    Notifications par module
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Activez les alertes pour les événements importants.
                </p>
            </div>

            @php
                $modulesNotifications = [
                    'conges'       => 'Gestion des congés',
                    'absences'     => 'Gestion des absences',
                    'presence'     => 'Présences',
                    'paie'         => 'Gestion de la paie',
                    'performance'  => 'Évaluations du personnel',
                    'contrats'     => 'Gestion des contrats',
                ];
            @endphp

            <div class="space-y-3">

                @foreach ($modulesNotifications as $cle => $libelle)

                    <label
                        for="notification_{{ $cle }}"
                        class="flex items-center justify-between gap-4 rounded-xl border border-gray-200 p-4 cursor-pointer hover:bg-gray-50 transition"
                    >
                        <span class="text-sm font-medium text-gray-800">
                            {{ $libelle }}
                        </span>

                        <input
                            type="checkbox"
                            id="notification_{{ $cle }}"
                            name="notifications[modules][{{ $cle }}]"
                            value="1"
                            @checked($notificationsModules[$cle] ?? true)
                            class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                        >
                    </label>

                @endforeach

            </div>
        </div>

        {{-- INFORMATION --}}
        {{-- <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5">
            <p class="text-sm font-semibold text-indigo-900">
                Information
            </p>
            <p class="mt-1 text-sm text-indigo-800">
                Les notifications internes utilisent la table notifications existante.
                L'envoi des e-mails et des SMS nécessitera la configuration
                de leurs services respectifs.
            </p>
        </div> --}}

        {{-- ENREGISTREMENT --}}
        <div class="flex justify-end border-gray-200 pt-5">
            <button
                type="submit"
                class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Enregistrer les notifications
            </button>
        </div>

    </form>

</section>