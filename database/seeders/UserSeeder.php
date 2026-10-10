<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nom', 'admin')->first();
        $rhRole = Role::where('nom', 'rh')->first();
        $chefRole = Role::where('nom', 'chef')->first();
        $employeRole = Role::where('nom', 'employe')->first();

        // Admin
        User::create([
            'nom' => 'Admin SGRH',
            'email' => 'admin@sgrh.ma',
            'matricule' => 'ADMIN001',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        // RH
        User::create([
            'nom' => 'Nadia Tazi',
            'email' => 'rh@sgrh.ma',
            'matricule' => 'RH001',
            'password' => Hash::make('passwordRh'),
            'role_id' => $rhRole->id,
        ]);

        // Chef
        User::create([
            'nom' => 'Mehdi Larbi',
            'email' => 'chef@sgrh.ma',
            'matricule' => 'CHEF001',
            'password' => Hash::make('passwordChef'),
            'role_id' => $chefRole->id,
        ]);

        // Employé
        User::create([
            'nom' => 'Ahmed Mansouri',
            'email' => 'employe@sgrh.ma',
            'matricule' => 'EMP001',
            'password' => Hash::make('passwordEmployee'),
            'role_id' => $employeRole->id,
        ]);
    }
}