{{-- ============================================================
     SECTION : PARAMÈTRES DES NOTIFICATIONS
     Fichier : partials/notifications.blade.php
============================================================ --}}

<section id="notifications" class="space-y-6">

    {{-- Présentation --}}
    <div>
        <h2 class="text-lg font-semibold text-slate-800">
            Notifications
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Configurez les canaux de notification et les alertes
            envoyées aux utilisateurs.
        </p>
    </div>

    {{-- Formulaire indépendant des paramètres Notifications --}}
    <form
        action="{{ route('admin.parametres.update') }}"
        method="POST"
        class="space-y-6"
    >
        @csrf

        {{-- Identifiant utilisé par le contrôleur pour reconnaître ce formulaire --}}
        <input
            type="hidden"
            name="notifications_form"
            value="1"
        >

        {{-- Canaux de notification --}}
        <div class="border border-slate-200 rounded-xl p-5 space-y-5">

            <div>
                <h3 class="font-semibold text-slate-800">
                    Canaux de notification
                </h3>

                <p class="text-xs text-slate-500 mt-1">
                    Choisissez les moyens de communication disponibles.
                </p>
            </div>

            {{-- Application --}}
            <div class="flex items-center justify-between gap-4 py-3 border-slate-100">

                <div>
                    <p class="text-sm font-medium text-slate-700">
                        Notifications dans l'application
                    </p>

                    <p class="text-xs text-slate-500">
                        Alertes affichées directement dans le SGRH.
                    </p>
                </div>

                <input
                    type="checkbox"
                    name="notifications[application]"
                    value="1"
                    @checked($notificationsCanaux['application'] ?? true)
                    class="w-4 h-4 rounded text-blue-600"
                >

            </div>

            {{-- E-mail --}}
            {{-- <div class="flex items-center justify-between gap-4 py-3 border-b border-slate-100">

                <div>
                    <p class="text-sm font-medium text-slate-700">
                        Notifications par e-mail
                    </p>

                    <p class="text-xs text-slate-500">
                        Envoi de messages par courrier électronique.
                    </p>
                </div>

                <input
                    type="checkbox"
                    name="notifications[email]"
                    value="1"
                    @checked($notificationsCanaux['email'] ?? false)
                    class="w-4 h-4 rounded text-blue-600"
                >

            </div> --}}

            {{-- SMS --}}
            {{-- <div class="flex items-center justify-between gap-4 py-3">

                <div>
                    <p class="text-sm font-medium text-slate-700">
                        Notifications par SMS
                    </p>

                    <p class="text-xs text-slate-500">
                        Envoi d'alertes par téléphone via un fournisseur SMS.
                    </p>
                </div>

                <input
                    type="checkbox"
                    name="notifications[sms]"
                    value="1"
                    @checked($notificationsCanaux['sms'] ?? false)
                    class="w-4 h-4 rounded text-blue-600"
                >

            </div> --}}

        </div>

        {{-- Notifications par module --}}
        <div class="border border-slate-200 rounded-xl p-5 space-y-5">

            <div>
                <h3 class="font-semibold text-slate-800">
                    Notifications par module
                </h3>

                <p class="text-xs text-slate-500 mt-1">
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
                    // 'recrutement'  => 'Offres d’emploi',
                ];
            @endphp

            @foreach ($modulesNotifications as $cle => $libelle)

                <div class="flex items-center justify-between gap-4 py-3 border-b border-slate-100 last:border-0">

                    <label
                        for="notification_{{ $cle }}"
                        class="text-sm font-medium text-slate-700"
                    >
                        {{ $libelle }}
                    </label>

                    <input
                        type="checkbox"
                        id="notification_{{ $cle }}"
                        name="notifications[modules][{{ $cle }}]"
                        value="1"
                        @checked($notificationsModules[$cle] ?? true)
                        class="w-4 h-4 rounded text-blue-600"
                    >

                </div>

            @endforeach

        </div>

        {{-- Information technique --}}
        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">

            <p class="text-sm font-medium text-blue-800">
                Information
            </p>

            <p class="text-xs text-blue-700 mt-1">
                Les notifications internes utilisent la table notifications
                existante. L'envoi des e-mails et des SMS nécessitera
                la configuration et la vérification de leurs services respectifs.
            </p>

        </div>

        {{-- Bouton d'enregistrement --}}
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition"
            >
                Enregistrer les notifications
            </button>
        </div>

    </form>

</section>