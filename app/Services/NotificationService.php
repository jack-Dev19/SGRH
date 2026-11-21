<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Parametre;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Récupérer les paramètres des notifications.
     */
    private function obtenirParametres(string $cle, array $defaut): array
    {
        $valeur = Parametre::obtenir('notifications', $cle, $defaut);

        if (is_string($valeur)) {
            $valeur = json_decode($valeur, true);
        }

        return is_array($valeur) ? $valeur : $defaut;
    }

    /**
     * Vérifier si un module autorise les notifications.
     */
    public function moduleActive(string $module): bool
    {
        $modules = $this->obtenirParametres('modules', [
            'conges' => true,
            'absences' => true,
            'presence' => true,
            'paie' => true,
            'performance' => true,
            'recrutement' => true,
        ]);

        return (bool) ($modules[$module] ?? false);
    }

    /**
     * Vérifier si un canal de notification est activé.
     */
    public function canalActive(string $canal): bool
    {
        $canaux = $this->obtenirParametres('canaux', [
            'application' => true,
            'email' => false,
            'sms' => false,
        ]);

        return (bool) ($canaux[$canal] ?? false);
    }

    /**
     * Créer une notification pour un utilisateur.
     *
     * Retourne null si le module ou le canal est désactivé.
     */
    public function envoyer(
        User $destinataire,
        string $titre,
        string $message,
        string $type = 'info',
        ?string $url = null,
        string $module = 'conges',
        ?string $demandeCongeId = null
    ): ?Notification {
        if (!$destinataire->estActif()) {
            return null;
        }

        if (!$this->moduleActive($module)) {
            return null;
        }

        if (!$this->canalActive('application')) {
            return null;
        }

        return $destinataire->notifications()->create([
            'demande_conge_id' => $demandeCongeId,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'lue' => false,
            'date_lecture' => null,
            'url' => $url,
        ]);
    }

    /**
     * Envoyer une notification circulaire à plusieurs utilisateurs.
     *
     * Retourne les notifications effectivement créées.
     */
    public function envoyerAPlusieurs(
        iterable $destinataires,
        string $titre,
        string $message,
        string $type = 'info',
        ?string $url = null,
        string $module = 'conges',
        ?string $demandeCongeId = null
    ): Collection {
        $notifications = new Collection();

        if (!$this->moduleActive($module)) {
            return $notifications;
        }

        if (!$this->canalActive('application')) {
            return $notifications;
        }

        foreach ($destinataires as $destinataire) {
            if (!$destinataire instanceof User) {
                continue;
            }

            $notification = $this->envoyer(
                $destinataire,
                $titre,
                $message,
                $type,
                $url,
                $module,
                $demandeCongeId
            );

            if ($notification) {
                $notifications->push($notification);
            }
        }

        return $notifications;
    }

    /**
     * Récupérer les utilisateurs actifs appartenant à certains rôles.
     */
    public function utilisateursParRoles(array $roles): Collection
    {
        return User::where('statut', true)
            ->whereHas('role', function ($query) use ($roles) {
                $query->whereIn('nom', $roles);
            })
            ->get();
    }

    /**
     * Mettre à jour une notification existante et la rendre non lue.
     */
    public function mettreAJour(
        Notification $notification,
        string $titre,
        string $message,
        string $type = 'info',
        ?string $url = null
    ): bool {
        return $notification->update([
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'lue' => false,
            'date_lecture' => null,
        ]);
    }

    /**
     * Journaliser un problème lié à une notification.
     */
    public function journaliserErreur(string $message, array $contexte = []): void
    {
        Log::warning($message, $contexte);
    }


    /**
     * Créer ou actualiser une notification liée à une demande de congé.
     *
     * Évite de créer plusieurs notifications pour le même
     * utilisateur et la même demande.
     */
    public function envoyerOuActualiser(
        User $destinataire,
        string $titre,
        string $message,
        string $type = 'info',
        ?string $url = null,
        string $module = 'conges',
        ?string $demandeCongeId = null
    ): ?Notification {
        if (!$demandeCongeId) {
            return $this->envoyer(
                $destinataire,
                $titre,
                $message,
                $type,
                $url,
                $module
            );
        }

        if (!$destinataire->estActif()
            || !$this->moduleActive($module)
            || !$this->canalActive('application')) {
            return null;
        }

        $notification = $destinataire->notifications()
            ->where('demande_conge_id', $demandeCongeId)
            ->first();

        if ($notification) {
            $this->mettreAJour(
                $notification,
                $titre,
                $message,
                $type,
                $url
            );

            return $notification->fresh();
        }

        return $this->envoyer(
            $destinataire,
            $titre,
            $message,
            $type,
            $url,
            $module,
            $demandeCongeId
        );
    }
}
