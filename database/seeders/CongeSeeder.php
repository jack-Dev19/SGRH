<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conge;
use App\Models\Employee;

class CongeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['annuel', 'maladie', 'maternite', 'exceptionnel'];
        $statuts = ['en_attente', 'approuve', 'refuse'];

        // Créer 30 congés
        for ($i = 0; $i < 30; $i++) {
            $debut = now()->subDays(rand(1, 200));
            
            Conge::create([
                'employee_id' => Employee::inRandomOrder()->first()->id,
                'type'        => $types[array_rand($types)],
                'date_debut'  => $debut,
                'date_fin'    => (clone $debut)->addDays(rand(2, 10)),
                'motif'       => 'Demande de congé',
                'statut'      => $statuts[array_rand($statuts)],
            ]);
        }
    }
}