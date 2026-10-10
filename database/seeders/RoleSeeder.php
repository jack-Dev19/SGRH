<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nom' => 'admin', 'description' => 'Administrateur système', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'rh', 'description' => 'Responsable RH', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'chef', 'description' => 'Chef de département', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'employe', 'description' => 'Employé', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}