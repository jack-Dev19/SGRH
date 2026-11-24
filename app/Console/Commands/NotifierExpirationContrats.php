<?php

namespace App\Console\Commands;

use App\Models\Contrat;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class NotifierExpirationContrats extends Command
{
    protected $signature = 'notifier:contrats-expiration';

    protected $description = 'Notifier les employés de l’approche ou de l’expiration de leur contrat';

    public function handle(NotificationService $notificationService): int
    {
        $this->notifierContratsPourDate(
            now()->addDays(30)->toDateString(),
            'Contrat bientôt expiré',
            'Votre contrat de travail « %s » expire dans 30 jours, le %s.'
        );

        $this->notifierContratsPourDate(
            now()->toDateString(),
            'Contrat arrivé à expiration',
            'Votre contrat de travail « %s » arrive à expiration aujourd’hui, le %s.'
        );

        $this->info('Vérification des expirations de contrats terminée.');

        return self::SUCCESS;
    }

    private function notifierContratsPourDate(
        string $date,
        string $titre,
        string $message
    ): void {
        $contrats = Contrat::with('employe.user')
            ->where('statut', 'actif')
            ->whereDate('date_fin', $date)
            ->get();

        foreach ($contrats as $contrat) {
            $utilisateur = $contrat->employe?->user;

            if (!$utilisateur) {
                continue;
            }

            $messageNotification = sprintf(
                $message,
                $contrat->numero,
                $contrat->date_fin->format('d/m/Y')
            );

            // Évite d'envoyer plusieurs fois la même alerte le même jour.
            $notificationExiste = Notification::query()
                ->where('user_id', $utilisateur->id)
                ->where('titre', $titre)
                ->where('message', $messageNotification)
                ->whereDate('created_at', now()->toDateString())
                ->exists();

            if ($notificationExiste) {
                continue;
            }

            $notificationService->envoyer(
                $utilisateur,
                $titre,
                $messageNotification,
                'warning',
                null,
                'contrats'
            );
        }
    }
}