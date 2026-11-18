<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ConversionDeviseService
{
    /**
     * Devise de référence utilisée dans la base de données.
     */
    private const DEVISE_BASE = 'MGA';

    /**
     * Devises prises en charge.
     */
    private const DEVISES_AUTORISEES = ['MGA', 'EUR', 'USD'];

    /**
     * Récupérer le taux de conversion depuis MGA.
     *
     * Exemple :
     * 1 MGA = 0.00019824 EUR
     */
    public function obtenirTaux(string $devise): float
    {
        $devise = strtoupper($devise);

        // Vérifier que la devise est autorisée.
        if (!in_array($devise, self::DEVISES_AUTORISEES, true)) {
            throw new RuntimeException(
                "La devise {$devise} n'est pas prise en charge."
            );
        }

        // MGA vers MGA : aucun changement de valeur.
        if ($devise === self::DEVISE_BASE) {
            return 1.0;
        }

        // Récupérer les taux enregistrés durablement.
        $tauxEnCache = Cache::get('devises.taux_mga');

        // Vérifier si une actualisation est nécessaire.
        if (!Cache::has('devises.taux_mga_actualisation')) {
            try {
                $response = Http::timeout(10)->get(
                    'https://api.frankfurter.dev/v2/rates',
                    [
                        'base' => self::DEVISE_BASE,
                        'quotes' => 'EUR,USD',
                    ]
                );

                $response->throw();

                $donnees = $response->json();

                $nouveauxTaux = [];

                foreach ($donnees as $ligne) {
                    if (
                        isset($ligne['quote'], $ligne['rate']) &&
                        in_array($ligne['quote'], ['EUR', 'USD'], true)
                    ) {
                        $nouveauxTaux[$ligne['quote']] =
                            (float) $ligne['rate'];
                    }
                }

                // Vérifier que les deux taux sont disponibles.
                if (
                    !isset($nouveauxTaux['EUR']) ||
                    !isset($nouveauxTaux['USD'])
                ) {
                    throw new RuntimeException(
                        'Les taux de conversion reçus sont incomplets.'
                    );
                }

                // Conserver durablement les derniers taux valides.
                $tauxEnCache = [
                    'date' => $donnees[0]['date'] ?? null,
                    'taux' => $nouveauxTaux,
                ];

                Cache::forever(
                    'devises.taux_mga',
                    $tauxEnCache
                );

                // Ne pas rappeler l'API pendant 24 heures.
                Cache::put(
                    'devises.taux_mga_actualisation',
                    true,
                    now()->addHours(24)
                );

            } catch (\Throwable $exception) {
                // Utiliser les derniers taux disponibles si l'API échoue.
                $tauxEnCache = Cache::get('devises.taux_mga');

                if (!$tauxEnCache) {
                    throw new RuntimeException(
                        'Impossible de récupérer les taux de change et aucun taux en cache n’est disponible.',
                        0,
                        $exception
                    );
                }
            }
        }

        // Vérifier la disponibilité du taux demandé.
        if (!isset($tauxEnCache['taux'][$devise])) {
            throw new RuntimeException(
                "Aucun taux disponible pour {$devise}."
            );
        }

        return (float) $tauxEnCache['taux'][$devise];
    }

    /**
     * Convertir un montant depuis MGA vers une autre devise.
     */
    public function convertir(float $montant, string $devise): float
    {
        return $montant * $this->obtenirTaux($devise);
    }
        /**
     * Convertir un montant d'une devise vers une autre.
     *
     * Exemple :
     * 10 USD vers EUR.
     *
     * La conversion passe par la devise de référence MGA.
     */
    public function convertirEntreDevises(
        float $montant,
        string $deviseSource,
        string $deviseDestination
    ): float {
        $deviseSource = strtoupper($deviseSource);
        $deviseDestination = strtoupper($deviseDestination);

        // Vérifier que les deux devises sont autorisées.
        if (
            !in_array($deviseSource, self::DEVISES_AUTORISEES, true) ||
            !in_array($deviseDestination, self::DEVISES_AUTORISEES, true)
        ) {
            throw new RuntimeException('Devise non prise en charge.');
        }

        // Aucune conversion si les devises sont identiques.
        if ($deviseSource === $deviseDestination) {
            return $montant;
        }

        // Convertir d'abord le montant source vers MGA.
        $montantEnMGA = $deviseSource === self::DEVISE_BASE
            ? $montant
            : $montant / $this->obtenirTaux($deviseSource);

        // Puis convertir MGA vers la devise destination.
        return $this->convertir($montantEnMGA, $deviseDestination);
    }
}
