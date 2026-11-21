<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class CalculCongeService
{
    /**
     * Calcule le nombre de jours ouvrés entre deux dates.
     * Exclut les week-ends et les jours fériés chômés actifs.
     */
    public function calculerJoursOuvres(
        Carbon $debut,
        Carbon $fin
    ): int {
        $joursFeries = DB::table('jours_feries')
            ->whereBetween('date', [
                $debut->toDateString(),
                $fin->toDateString(),
            ])
            ->where('est_chome', true)
            ->where('statut', 'actif')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->flip();

        $nombreJours = 0;

        foreach (CarbonPeriod::create(
            $debut->copy()->startOfDay(),
            $fin->copy()->startOfDay()
        ) as $date) {
            if ($date->isWeekend()) {
                continue;
            }

            if ($joursFeries->has($date->toDateString())) {
                continue;
            }

            $nombreJours++;
        }

        return $nombreJours;
    }
}