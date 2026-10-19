<?php

namespace App\Console\Commands;

use App\Services\AbsenceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

/**
 * Commande : détecter les absences automatiquement.
 * ------------------------------------------------
 * Usage :
 *   php artisan sgrh:detecter-absences                → aujourd'hui
 *   php artisan sgrh:detecter-absences --date=2026-10-14  → date spécifique
 *   php artisan sgrh:detecter-absences --plage=2026-10-01,2026-10-15
 */
class DetecterAbsences extends Command
{
    /**
     * Signature de la commande.
     */
    protected $signature = 'sgrh:detecter-absences
                            {--date= : Date spécifique (Y-m-d)}
                            {--plage= : Plage de dates (YYYY-MM-DD,YYYY-MM-DD)}';

    /**
     * Description.
     */
    protected $description = 'Détecte automatiquement les absences des employés sans pointage';

    public function handle(AbsenceService $service): int
    {
        $this->info('🔍 Détection des absences...');
        $this->newLine();

        // -------------------------------------------------------------
        // Cas 1 : Plage de dates
        // -------------------------------------------------------------
        if ($plage = $this->option('plage')) {
            [$debut, $fin] = explode(',', $plage);

            if (!$debut || !$fin) {
                $this->error('Format de plage invalide. Utilisez : --plage=2026-10-01,2026-10-15');
                return self::FAILURE;
            }

            $this->info("📅 Plage : du {$debut} au {$fin}");

            $resultat = $service->detecterPlage($debut, $fin);

            $this->table(
                ['Date', 'Absences créées', 'Ignorés'],
                collect($resultat['jours'])->map(fn($j) => [$j['date'], $j['crees'], $j['ignores']])
            );

            $this->newLine();
            $this->info("✅ Total : {$resultat['crees']} créée(s), {$resultat['ignores']} ignorée(s).");

            return self::SUCCESS;
        }

        // -------------------------------------------------------------
        // Cas 2 : Date spécifique ou aujourd'hui
        // -------------------------------------------------------------
        $date = $this->option('date') ?? now()->toDateString();

        try {
            Carbon::parse($date);
        } catch (\Exception $e) {
            $this->error("Date invalide : {$date}");
            return self::FAILURE;
        }

        $this->info("📅 Date : {$date}");
        $this->newLine();

        $resultat = $service->detecterAbsences($date);

        // Affichage
        if ($resultat['crees'] === 0 && $resultat['ignores'] === 0) {
            $this->warn('⚠️  Aucun employé à traiter (jour non ouvré ?)');
            if (isset($resultat['details']['message'])) {
                $this->line('   ' . $resultat['details']['message']);
            }
            return self::SUCCESS;
        }

        $this->info("📊 Résultat :");
        $this->line("   • Absences créées  : {$resultat['crees']}");
        $this->line("   • Employés ignorés : {$resultat['ignores']}");
        $this->newLine();

        // Détails (si demandé)
        if ($this->option('verbose') && !empty($resultat['details'])) {
            $this->info('Détails :');
            foreach ($resultat['details'] as $detail) {
                $this->line("   → {$detail}");
            }
        }

        $this->info('✅ Détection terminée.');

        return self::SUCCESS;
    }
}