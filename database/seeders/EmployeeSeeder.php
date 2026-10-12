<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Créer 52 employés répartis aléatoirement dans les départements
        for ($i = 1; $i <= 52; $i++) {
            Employee::create([
                'matricule'      => 'EMP-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nom'            => $faker->lastName,
                'prenom'         => $faker->firstName,
                'sexe'           => $faker->randomElement(['M', 'F']),
                'email'          => $faker->unique()->email,
                'telephone'      => $faker->phoneNumber,
                'salaire_base'   => $faker->numberBetween(4000, 20000),
                'date_embauche'  => $faker->dateTimeBetween('-5 years', 'now'),
                'departement_id' => $faker->numberBetween(1, 6),
                'actif'          => $faker->boolean(90), // 90% actifs
            ]);
        }
    }
}