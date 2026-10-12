<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        $departements = [
            ['nom' => 'Commercial',    'couleur' => '#1e3a8a'],
            ['nom' => 'Informatique',  'couleur' => '#2563eb'],
            ['nom' => 'Marketing',     'couleur' => '#7c3aed'],
            ['nom' => 'Comptabilité',  'couleur' => '#10b981'],
            ['nom' => 'RH',            'couleur' => '#f59e0b'],
            ['nom' => 'Direction',     'couleur' => '#ef4444'],
        ];

        foreach ($departements as $d) {
            Departement::create($d);
        }
    }
}