<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Jeu de données de démonstration du SGRH — période 2025 à 2026.
 *
 * IMPORTANT :
 * - Les données sont fictives.
 * - Le seeder est réexécutable et ne supprime aucune donnée.
 * - Les identifiants UUID et les clés étrangères sont résolus à partir du schéma.
 * - Aucun enregistrement de candidat, candidature ou entretien n'est créé.
 * - La table active des offres est `offre_emplois` (et non l'ancienne `offres_emploi`).
 */
class SgrhTestDataSeeder extends Seeder
{
    private Carbon $dateLimite;

    private array $tablesCache = [];
    private array $columnsCache = [];
    private array $warnedTables = [];

    private array $roleIds = [];
    private array $departmentIds = [];
    private array $positionIds = [];
    private array $userIds = [];
    private array $employees = [];
    private array $managerByDepartment = [];
    private array $contractIds = [];
    private array $qrIds = [];
    private array $qrScanCounts = [];
    private array $qrLastScans = [];
    private array $leaveTypeIds = [];
    private string $scheduleId = '';
    private array $appreciationLevels = [];

    public function run(): void
    {
        // Date de référence demandée : données jusqu'au 14 novembre 2026 inclus.
        $this->dateLimite = Carbon::create(2026, 11, 14)->startOfDay();
        $this->scheduleId = '';

        DB::transaction(function (): void {
            $this->seedRoles();
            $this->seedDepartmentsAndPositions();
            $this->seedUsersAndEmployees();
            $this->seedContractsAndSalaries();
            $this->seedLeaveTypesAndSchedules();
            $this->seedQrCodes();
            $this->seedMonthlyHistory();
            $this->seedDocuments();
            $this->seedTrainingSkillsAndEvaluations();
            $this->seedJobOffers();
            $this->seedNotificationsAndHistory();
            $this->finalizeQrStatistics();
        });

        $this->command?->newLine();
        $this->command?->info('Jeu de données SGRH 2025–2026 créé ou mis à jour avec succès.');
        $this->command?->info('16 employés fictifs — 4 rôles — 5 départements — 80 demandes de congé prévues.');
        $this->command?->comment('Aucune donnée de candidature ou d’entretien n’a été créée.');
        $this->command?->warn('Les URL des photos sont externes (randomuser.me) et nécessitent une connexion Internet pour leur affichage.');
        if (!$this->hasColumn('users', 'photo')) {
            $this->command?->warn('La colonne users.photo est absente : exécutez les migrations incluses dans le paquet.');
        }
        if ($this->hasTable('employes')) {
            $anciensEmployes = DB::table('employes')->where('matricule', 'like', 'MAT-HIST-%')->count();
            if ($anciensEmployes > 0) {
                $this->command?->warn("{$anciensEmployes} anciens employés MAT-HIST issus d'un précédent seeder sont conservés. Le présent seeder ne les supprime pas.");
            }
        }
    }

    /** Enregistre une ligne sans doublon à partir d'une clé naturelle. */
    private function put(string $table, array $keys, array $values): ?object
    {
        if (!$this->hasTable($table)) {
            if (!isset($this->warnedTables[$table])) {
                $this->command?->warn("Table absente, ignorée : {$table}");
                $this->warnedTables[$table] = true;
            }
            return null;
        }

        foreach (array_keys($keys) as $column) {
            if (!$this->hasColumn($table, $column)) {
                throw new RuntimeException("Colonne de clé absente : {$table}.{$column}");
            }
        }

        $data = array_merge($keys, $values);
        $data = array_filter(
            $data,
            fn ($value, $column) => $this->hasColumn($table, $column),
            ARRAY_FILTER_USE_BOTH
        );

        $existingQuery = DB::table($table);
        foreach ($keys as $column => $value) {
            $existingQuery->where($column, $value);
        }
        $existing = $existingQuery->first();

        if ($existing) {
            unset($data['id'], $data['created_at']);
            if ($this->hasColumn($table, 'updated_at') && !array_key_exists('updated_at', $data)) {
                $data['updated_at'] = now();
            }
            if ($data !== []) {
                $updateQuery = DB::table($table);
                foreach ($keys as $column => $value) {
                    $updateQuery->where($column, $value);
                }
                $updateQuery->update($data);
            }
        } else {
            if ($this->hasColumn($table, 'id') && empty($data['id'])) {
                $data['id'] = (string) Str::uuid();
            }
            if ($this->hasColumn($table, 'created_at') && empty($data['created_at'])) {
                $data['created_at'] = now();
            }
            if ($this->hasColumn($table, 'updated_at') && empty($data['updated_at'])) {
                $data['updated_at'] = now();
            }
            DB::table($table)->insert($data);
        }

        $resultQuery = DB::table($table);
        foreach ($keys as $column => $value) {
            $resultQuery->where($column, $value);
        }
        return $resultQuery->first();
    }

    private function hasTable(string $table): bool
    {
        return $this->tablesCache[$table] ??= Schema::hasTable($table);
    }

    private function hasColumn(string $table, string $column): bool
    {
        $key = $table . '.' . $column;
        return $this->columnsCache[$key] ??= Schema::hasColumn($table, $column);
    }

    private function rowId(string $table, array $keys): string
    {
        $query = DB::table($table);
        foreach ($keys as $column => $value) {
            $query->where($column, $value);
        }
        $row = $query->first();
        if (!$row || !isset($row->id)) {
            throw new RuntimeException('Enregistrement introuvable dans ' . $table . ' : ' . json_encode($keys));
        }
        return (string) $row->id;
    }

    private function seedRoles(): void
    {
        $roles = [
            'Administrateur' => 'Administration et configuration générale du SGRH.',
            'Responsable RH' => 'Gestion administrative et suivi des ressources humaines.',
            'Chef de département' => 'Suivi des employés et des activités de son département.',
            'Employé' => 'Consultation de ses informations et réalisation des demandes personnelles.',
        ];

        foreach ($roles as $nom => $description) {
            $row = $this->put('roles', ['nom' => $nom], [
                'nom' => $nom,
                'description' => $description,
            ]);
            $this->roleIds[$nom] = (string) $row->id;
        }
    }

    private function employeeData(): array
    {
        return [
            [
                'matricule' => 'MAT-001', 'email' => 'admin@madatech.test', 'prenom' => 'Andry', 'nom' => 'Rakoto',
                'sexe' => 'Homme', 'naissance' => '1985-02-14', 'telephone' => '0340000001',
                'departement' => 'Direction générale', 'poste' => 'Directeur général', 'role' => 'Administrateur',
                'embauche' => '2025-01-02', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 2500000,
                'photo' => 'https://randomuser.me/api/portraits/men/11.jpg', 'adresse' => 'Analamahitsy, Antananarivo',
            ],
            [
                'matricule' => 'MAT-002', 'email' => 'rh@madatech.test', 'prenom' => 'Hanitra', 'nom' => 'Rabe',
                'sexe' => 'Femme', 'naissance' => '1990-06-22', 'telephone' => '0340000002',
                'departement' => 'Ressources humaines', 'poste' => 'Responsable RH', 'role' => 'Responsable RH',
                'embauche' => '2025-01-06', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1700000,
                'photo' => 'https://randomuser.me/api/portraits/women/11.jpg', 'adresse' => 'Ankadifotsy, Antananarivo',
            ],
            [
                'matricule' => 'MAT-003', 'email' => 'manager@madatech.test', 'prenom' => 'Hery', 'nom' => 'Randria',
                'sexe' => 'Homme', 'naissance' => '1991-11-09', 'telephone' => '0340000003',
                'departement' => 'Développement informatique', 'poste' => 'Chef de département informatique', 'role' => 'Chef de département',
                'embauche' => '2025-01-15', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1900000,
                'photo' => 'https://randomuser.me/api/portraits/men/12.jpg', 'adresse' => 'Ankorondrano, Antananarivo',
            ],
            [
                'matricule' => 'MAT-007', 'email' => 'chef.reseau@madatech.test', 'prenom' => 'Rija', 'nom' => 'Ramanantsoa',
                'sexe' => 'Homme', 'naissance' => '1989-03-18', 'telephone' => '0340000004',
                'departement' => 'Réseaux et systèmes', 'poste' => 'Chef de département réseaux', 'role' => 'Chef de département',
                'embauche' => '2025-03-03', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1650000,
                'photo' => 'https://randomuser.me/api/portraits/men/13.jpg', 'adresse' => 'Ivandry, Antananarivo',
            ],
            [
                'matricule' => 'MAT-008', 'email' => 'chef.finance@madatech.test', 'prenom' => 'Tovo', 'nom' => 'Rakotoarisoa',
                'sexe' => 'Homme', 'naissance' => '1988-05-05', 'telephone' => '0340000005',
                'departement' => 'Administration et finances', 'poste' => 'Chef comptable', 'role' => 'Chef de département',
                'embauche' => '2025-05-05', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1600000,
                'photo' => 'https://randomuser.me/api/portraits/men/14.jpg', 'adresse' => 'Ambohibao, Antananarivo',
            ],
            [
                'matricule' => 'MAT-004', 'email' => 'mialy@madatech.test', 'prenom' => 'Mialy', 'nom' => 'Rakoto',
                'sexe' => 'Femme', 'naissance' => '1998-04-12', 'telephone' => '0340000006',
                'departement' => 'Développement informatique', 'poste' => 'Développeuse web', 'role' => 'Employé',
                'embauche' => '2025-01-20', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1000000,
                'photo' => 'https://randomuser.me/api/portraits/women/12.jpg', 'adresse' => 'Andoharanofotsy, Antananarivo',
            ],
            [
                'matricule' => 'MAT-005', 'email' => 'tiana@madatech.test', 'prenom' => 'Tiana', 'nom' => 'Rasoanaivo',
                'sexe' => 'Homme', 'naissance' => '1996-08-20', 'telephone' => '0340000007',
                'departement' => 'Réseaux et systèmes', 'poste' => 'Technicien réseau', 'role' => 'Employé',
                'embauche' => '2025-04-07', 'sortie' => '2026-02-28', 'contrat' => 'CDD', 'salaire' => 850000,
                'photo' => 'https://randomuser.me/api/portraits/men/15.jpg', 'adresse' => 'Anosizato, Antananarivo',
            ],
            [
                'matricule' => 'MAT-006', 'email' => 'fara@madatech.test', 'prenom' => 'Fara', 'nom' => 'Andriamihaja',
                'sexe' => 'Femme', 'naissance' => '1995-12-03', 'telephone' => '0340000008',
                'departement' => 'Ressources humaines', 'poste' => 'Assistante RH', 'role' => 'Employé',
                'embauche' => '2025-07-01', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 900000,
                'photo' => 'https://randomuser.me/api/portraits/women/13.jpg', 'adresse' => 'Ampefiloha, Antananarivo',
            ],
            [
                'matricule' => 'MAT-009', 'email' => 'noro@madatech.test', 'prenom' => 'Noro', 'nom' => 'Randrianarisoa',
                'sexe' => 'Femme', 'naissance' => '1997-09-16', 'telephone' => '0340000009',
                'departement' => 'Développement informatique', 'poste' => 'Développeuse full-stack', 'role' => 'Employé',
                'embauche' => '2025-10-01', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1200000,
                'photo' => 'https://randomuser.me/api/portraits/women/14.jpg', 'adresse' => 'Ankadikely, Antananarivo',
            ],
            [
                'matricule' => 'MAT-010', 'email' => 'tahina@madatech.test', 'prenom' => 'Tahina', 'nom' => 'Rakotomalala',
                'sexe' => 'Homme', 'naissance' => '1994-02-11', 'telephone' => '0340000010',
                'departement' => 'Administration et finances', 'poste' => 'Comptable', 'role' => 'Employé',
                'embauche' => '2026-01-05', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1050000,
                'photo' => 'https://randomuser.me/api/portraits/men/16.jpg', 'adresse' => 'Mahamasina, Antananarivo',
            ],
            [
                'matricule' => 'MAT-011', 'email' => 'haja@madatech.test', 'prenom' => 'Haja', 'nom' => 'Raveloson',
                'sexe' => 'Homme', 'naissance' => '1993-07-19', 'telephone' => '0340000011',
                'departement' => 'Réseaux et systèmes', 'poste' => 'Technicien réseau', 'role' => 'Employé',
                'embauche' => '2026-02-02', 'sortie' => null, 'contrat' => 'CDD', 'salaire' => 900000,
                'photo' => 'https://randomuser.me/api/portraits/men/17.jpg', 'adresse' => 'Anosibe, Antananarivo',
            ],
            [
                'matricule' => 'MAT-012', 'email' => 'lalao@madatech.test', 'prenom' => 'Lalao', 'nom' => 'Rakoto',
                'sexe' => 'Femme', 'naissance' => '1999-01-23', 'telephone' => '0340000012',
                'departement' => 'Ressources humaines', 'poste' => 'Assistante RH', 'role' => 'Employé',
                'embauche' => '2026-03-02', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 950000,
                'photo' => 'https://randomuser.me/api/portraits/women/15.jpg', 'adresse' => 'Ankadimbahoaka, Antananarivo',
            ],
            [
                'matricule' => 'MAT-013', 'email' => 'solofo@madatech.test', 'prenom' => 'Solofo', 'nom' => 'Andriamampianina',
                'sexe' => 'Homme', 'naissance' => '1992-10-07', 'telephone' => '0340000013',
                'departement' => 'Développement informatique', 'poste' => 'Développeur web', 'role' => 'Employé',
                'embauche' => '2026-04-01', 'sortie' => '2026-09-30', 'contrat' => 'CDD', 'salaire' => 1100000,
                'photo' => 'https://randomuser.me/api/portraits/men/18.jpg', 'adresse' => 'Andravoahangy, Antananarivo',
            ],
            [
                'matricule' => 'MAT-014', 'email' => 'feno@madatech.test', 'prenom' => 'Feno', 'nom' => 'Rasoanaivo',
                'sexe' => 'Homme', 'naissance' => '2000-03-27', 'telephone' => '0340000014',
                'departement' => 'Développement informatique', 'poste' => 'Développeur web', 'role' => 'Employé',
                'embauche' => '2026-05-04', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 1100000,
                'photo' => 'https://randomuser.me/api/portraits/men/19.jpg', 'adresse' => 'Ambohimanarina, Antananarivo',
            ],
            [
                'matricule' => 'MAT-015', 'email' => 'hasina@madatech.test', 'prenom' => 'Hasina', 'nom' => 'Randriamihaja',
                'sexe' => 'Femme', 'naissance' => '2001-06-15', 'telephone' => '0340000015',
                'departement' => 'Administration et finances', 'poste' => 'Assistante administrative', 'role' => 'Employé',
                'embauche' => '2026-07-06', 'sortie' => null, 'contrat' => 'Stage', 'salaire' => 600000,
                'photo' => 'https://randomuser.me/api/portraits/women/16.jpg', 'adresse' => 'Tsaralalana, Antananarivo',
            ],
            [
                'matricule' => 'MAT-016', 'email' => 'miora@madatech.test', 'prenom' => 'Miora', 'nom' => 'Rakoto',
                'sexe' => 'Femme', 'naissance' => '1998-12-01', 'telephone' => '0340000016',
                'departement' => 'Réseaux et systèmes', 'poste' => 'Technicienne support', 'role' => 'Employé',
                'embauche' => '2026-09-01', 'sortie' => null, 'contrat' => 'CDI', 'salaire' => 850000,
                'photo' => 'https://randomuser.me/api/portraits/women/17.jpg', 'adresse' => 'Ambatobe, Antananarivo',
            ],
        ];
    }

    private function seedDepartmentsAndPositions(): void
    {
        $departments = [
            'Direction générale' => 'Pilotage et coordination générale de l’organisation.',
            'Ressources humaines' => 'Administration du personnel et suivi des ressources humaines.',
            'Développement informatique' => 'Conception, développement et maintenance des applications.',
            'Réseaux et systèmes' => 'Gestion des infrastructures, réseaux et assistance technique.',
            'Administration et finances' => 'Gestion administrative, comptable et financière.',
        ];

        foreach ($departments as $nom => $description) {
            $row = $this->put('departements', ['nom' => $nom], [
                'nom' => $nom,
                'description' => $description,
                'statut' => 1,
            ]);
            $this->departmentIds[$nom] = (string) $row->id;
        }

        $positions = [];
        foreach ($this->employeeData() as $employee) {
            $key = $employee['departement'] . '|' . $employee['poste'];
            if (!isset($positions[$key])) {
                $positions[$key] = [
                    'nom' => $employee['poste'],
                    'departement' => $employee['departement'],
                    'salaire' => $employee['salaire'],
                ];
            }
        }

        foreach ($positions as $position) {
            $departmentId = $this->departmentIds[$position['departement']];
            $minimum = (int) floor($position['salaire'] * 0.85);
            $maximum = (int) ceil($position['salaire'] * 1.35);
            $row = $this->put('postes', [
                'nom' => $position['nom'],
                'departement_id' => $departmentId,
            ], [
                'nom' => $position['nom'],
                'departement_id' => $departmentId,
                'description' => 'Poste de démonstration : ' . $position['nom'] . '.',
                'niveau' => str_contains(mb_strtolower($position['nom']), 'chef') || str_contains(mb_strtolower($position['nom']), 'directeur') ? 'Responsable' : 'Opérationnel',
                'salaire_min' => $minimum,
                'salaire_max' => $maximum,

                // La migration postes utilise un statut textuel :
                // les valeurs autorisées ici sont "actif" et "inactif".
                'statut' => 'actif',
            ]);
            $this->positionIds[$key = $position['departement'] . '|' . $position['nom']] = (string) $row->id;
        }
    }

    private function seedUsersAndEmployees(): void
    {
        $this->normalizeLegacyDemoRecruiter();

        foreach ($this->employeeData() as $employee) {
            $hireDate = $employee['embauche'] . ' 08:00:00';
            $active = $employee['sortie'] === null;
            $user = $this->put('users', ['email' => $employee['email']], [
                'name' => $employee['prenom'] . ' ' . $employee['nom'],
                'email' => $employee['email'],
                'matricule' => $employee['matricule'],
                'role_id' => $this->roleIds[$employee['role']],
                'password' => Hash::make('Test1234!'),
                'statut' => $active,
                'email_verified_at' => $hireDate,
                'photo' => $employee['photo'],
                'created_at' => $hireDate,
                'updated_at' => $hireDate,
            ]);
            $userId = (string) $user->id;
            $this->userIds[$employee['matricule']] = $userId;

            $departmentId = $this->departmentIds[$employee['departement']];
            $positionKey = $employee['departement'] . '|' . $employee['poste'];
            $positionId = $this->positionIds[$positionKey];

            $employeeRow = $this->put('employes', ['matricule' => $employee['matricule']], [
                'user_id' => $userId,
                'departement_id' => $departmentId,
                'poste_id' => $positionId,
                'matricule' => $employee['matricule'],
                'nom' => $employee['nom'],
                'prenom' => $employee['prenom'],
                'sexe' => $employee['sexe'],
                'date_naissance' => $employee['naissance'],
                'telephone' => $employee['telephone'],
                'email' => $employee['email'],
                'adresse' => $employee['adresse'],
                'date_embauche' => $employee['embauche'],
                'date_sortie' => $employee['sortie'],
                'statut' => $active ? 'actif' : 'sorti',
                'photo' => $employee['photo'],
                'created_at' => $hireDate,
                'updated_at' => $hireDate,
            ]);

            $employee['id'] = (string) $employeeRow->id;
            $employee['user_id'] = $userId;
            $employee['departement_id'] = $departmentId;
            $employee['poste_id'] = $positionId;
            $this->employees[$employee['matricule']] = $employee;

            if (in_array($employee['role'], ['Administrateur', 'Responsable RH', 'Chef de département'], true)) {
                $this->managerByDepartment[$employee['departement']] = $userId;
            }
        }

        // Le champ responsable_id référence users.id dans la migration finale du projet.
        foreach ($this->managerByDepartment as $department => $userId) {
            $this->put('departements', ['nom' => $department], [
                'responsable_id' => $userId,
            ]);
        }

        // Supprimer le rôle Recruteur uniquement s'il n'est plus attribué à aucun compte.
        if ($this->hasTable('roles')) {
            $legacyRole = DB::table('roles')->where('nom', 'Recruteur')->first();
            if ($legacyRole && !DB::table('users')->where('role_id', $legacyRole->id)->exists()) {
                DB::table('roles')->where('id', $legacyRole->id)->delete();
            }
        }
    }

    /** Réutilise l'ancien compte de démonstration MAT-006 sans conserver un compte Recruteur orphelin. */
    private function normalizeLegacyDemoRecruiter(): void
    {
        if (!$this->hasTable('users') || !$this->hasTable('employes')) {
            return;
        }

        $legacyUser = DB::table('users')->where('email', 'recruteur@madatech.test')->first();
        $legacyEmployee = DB::table('employes')->where('matricule', 'MAT-006')->first();

        if (!$legacyUser || !$legacyEmployee || $legacyEmployee->user_id !== $legacyUser->id) {
            return;
        }

        $emailAlreadyUsed = DB::table('users')
            ->where('email', 'fara@madatech.test')
            ->where('id', '!=', $legacyUser->id)
            ->exists();

        if (!$emailAlreadyUsed) {
            DB::table('users')->where('id', $legacyUser->id)->update([
                'email' => 'fara@madatech.test',
                'matricule' => 'MAT-006',
                'name' => 'Fara Andriamihaja',
                'updated_at' => now(),
            ]);
        }
    }

    private function seedContractsAndSalaries(): void
    {
        foreach ($this->employees as $employee) {
            $matricule = $employee['matricule'];
            $dateFinContrat = $employee['sortie'];
            if ($dateFinContrat === null && $employee['contrat'] === 'Stage') {
                $dateFinContrat = '2026-12-31';
            } elseif ($dateFinContrat === null && $employee['contrat'] === 'CDD') {
                $dateFinContrat = '2026-12-31';
            }

            $contract = $this->put('contrats', ['numero' => 'CTR-' . substr($matricule, 4)], [
                'employe_id' => $employee['id'],
                'numero' => 'CTR-' . substr($matricule, 4),
                'type' => $employee['contrat'],
                'date_debut' => $employee['embauche'],
                'date_fin' => $dateFinContrat,
                'salaire' => $employee['salaire'],
                'devise' => 'MGA',
                'periode_essai' => 3,
                'unite_periode_essai' => 'mois',
                'statut' => $employee['sortie'] ? 'termine' : 'actif',
                'description' => 'Contrat fictif utilisé pour les démonstrations du SGRH.',
                'created_at' => $employee['embauche'] . ' 08:00:00',
            ]);
            $this->contractIds[$matricule] = (string) $contract->id;

            $this->put('salaires', [
                'employe_id' => $employee['id'],
                'date_debut' => $employee['embauche'],
            ], [
                'employe_id' => $employee['id'],
                'salaire_base' => $employee['salaire'],
                'date_debut' => $employee['embauche'],
                'date_fin' => $employee['sortie'],
                'motif' => 'Salaire initial à l’embauche',
                'statut' => $employee['sortie'] ? 'termine' : 'actif',
                'commentaire' => 'Donnée salariale fictive en MGA.',
                'created_at' => $employee['embauche'] . ' 08:00:00',
            ]);
        }
    }

    private function seedLeaveTypesAndSchedules(): void
    {
        $types = [
            ['Congé annuel', 'CA', 'Congé annuel payé.', 30, false],
            ['Congé maladie', 'CM', 'Congé pour raison médicale.', 30, true],
            ['Congé exceptionnel', 'CE', 'Congé pour événement familial ou exceptionnel.', 5, true],
            ['Congé sans solde', 'CSS', 'Absence autorisée sans rémunération.', 30, false],
            ['Congé de maternité ou paternité', 'CMP', 'Congé lié à la naissance d’un enfant.', 90, true],
        ];

        foreach ($types as [$nom, $code, $description, $duree, $justificatif]) {
            $row = $this->put('types_conges', ['code' => $code], [
                'nom' => $nom,
                'code' => $code,
                'description' => $description,
                'duree_max' => $duree,
                'necessite_justificatif' => $justificatif,
                'statut' => 'actif',
            ]);
            $this->leaveTypeIds[] = (string) $row->id;
        }

        $schedule = $this->put('horaires_travail', ['nom' => 'Horaire administratif standard'], [
            'nom' => 'Horaire administratif standard',
            'description' => 'Du lundi au vendredi, avec une pause déjeuner.',
            'heure_debut' => '08:00:00',
            'heure_fin' => '17:00:00',
            'debut_pause' => '12:00:00',
            'fin_pause' => '13:00:00',
            'duree_pause' => 1.00,
            'tolerance_retard' => 10,
            'heures_prevues' => 8.00,
            'statut' => 'actif',
        ]);
        $this->scheduleId = (string) $schedule->id;

        for ($day = 1; $day <= 7; $day++) {
            $worked = $day <= 5;
            $this->put('horaires_jours', [
                'horaire_id' => $this->scheduleId,
                'jour_semaine' => $day,
            ], [
                'horaire_id' => $this->scheduleId,
                'jour_semaine' => $day,
                'est_travaille' => $worked,
                'heure_debut' => $worked ? '08:00:00' : null,
                'heure_fin' => $worked ? '17:00:00' : null,
                'debut_pause' => $worked ? '12:00:00' : null,
                'fin_pause' => $worked ? '13:00:00' : null,
                'heures_prevues' => $worked ? 8.00 : 0.00,
            ]);
        }

        foreach ($this->employees as $employee) {
            $this->put('affectations_horaires', [
                'employe_id' => $employee['id'],
                'horaire_id' => $this->scheduleId,
                'date_debut' => $employee['embauche'],
            ], [
                'employe_id' => $employee['id'],
                'horaire_id' => $this->scheduleId,
                'date_debut' => $employee['embauche'],
                'date_fin' => $employee['sortie'],
                'statut' => $employee['sortie'] ? 'terminee' : 'active',
                'motif' => 'Affectation initiale à l’horaire administratif.',
                'commentaire' => 'Affectation de démonstration.',
                'created_at' => $employee['embauche'] . ' 08:00:00',
            ]);
        }

        $holidays = [
            ['2025-01-01', 'Jour de l’An'], ['2025-05-01', 'Fête du Travail'],
            ['2025-06-26', 'Fête de l’Indépendance'], ['2025-12-25', 'Noël'],
            ['2026-01-01', 'Jour de l’An'], ['2026-05-01', 'Fête du Travail'],
            ['2026-06-26', 'Fête de l’Indépendance'],
        ];
        foreach ($holidays as [$date, $nom]) {
            $this->put('jours_feries', ['date' => $date], [
                'nom' => $nom,
                'date' => $date,
                'description' => 'Jour férié ajouté à titre de démonstration.',
                'est_chome' => true,
                'statut' => 'actif',
                'created_at' => $date . ' 08:00:00',
            ]);
        }
    }

    private function seedQrCodes(): void
    {
        foreach ($this->employees as $employee) {
            $active = $employee['sortie'] === null;
            $matricule = $employee['matricule'];
            $row = $this->put('qr_codes', ['code' => $matricule], [
                'employe_id' => $employee['id'],
                'code' => $matricule,
                'token' => $matricule,
                'contenu_qr' => $matricule,
                'actif' => $active,
                'genere_par' => $this->userIds['MAT-002'],
                'genere_le' => $employee['embauche'] . ' 08:30:00',
                'revoque_le' => $active ? null : $employee['sortie'] . ' 17:00:00',
                'revoque_par' => $active ? null : $this->userIds['MAT-002'],
                'raison_revocation' => $active ? null : 'Départ de l’employé.',
                'scan_count' => 0,
                'dernier_scan_le' => null,
                'created_at' => $employee['embauche'] . ' 08:30:00',
            ]);
            $this->qrIds[$matricule] = (string) $row->id;
            $this->qrScanCounts[$matricule] = 0;
            $this->qrLastScans[$matricule] = null;
        }
    }

    private function seedMonthlyHistory(): void
    {
        $leaveCounts2025 = [2, 4, 3, 5, 2, 4, 3, 5, 2, 4, 3, 5];
        $leaveCounts2026 = [3, 5, 2, 4, 3, 5, 2, 4, 3, 5, 2];
        $monthIndex = 0;

        for ($year = 2025; $year <= 2026; $year++) {
            $lastMonth = $year === 2025 ? 12 : 11;
            for ($month = 1; $month <= $lastMonth; $month++) {
                $monthIndex++;
                $monthLimit = $this->monthLimit($year, $month);
                if ($monthLimit->lt(Carbon::create($year, $month, 1))) {
                    continue;
                }

                $leaveCount = $year === 2025 ? $leaveCounts2025[$month - 1] : $leaveCounts2026[$month - 1];
                $this->seedLeaveRequestsForMonth($year, $month, $monthIndex, $leaveCount, $monthLimit);
                $this->seedAbsencesForMonth($year, $month, $monthIndex, $monthLimit);
                $this->seedAttendanceForMonth($year, $month, $monthIndex, $monthLimit);
                $this->seedPayrollForMonth($year, $month, $monthIndex, $monthLimit);
            }
        }
    }

    private function monthLimit(int $year, int $month): Carbon
    {
        $end = Carbon::create($year, $month, 1)->endOfMonth()->startOfDay();
        return $end->gt($this->dateLimite) ? $this->dateLimite->copy() : $end;
    }

    private function employeeActiveAt(array $employee, string $date): bool
    {
        return $employee['embauche'] <= $date
            && ($employee['sortie'] === null || $employee['sortie'] >= $date);
    }

    private function employeesActiveAt(string $date): array
    {
        return array_values(array_filter(
            $this->employees,
            fn (array $employee) => $this->employeeActiveAt($employee, $date)
        ));
    }

    private function employeesActiveInMonth(int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1)->toDateString();
        $end = $this->monthLimit($year, $month)->toDateString();
        return array_values(array_filter($this->employees, function (array $employee) use ($start, $end) {
            return $employee['embauche'] <= $end
                && ($employee['sortie'] === null || $employee['sortie'] >= $start);
        }));
    }

    private function validatorFor(array $employee): string
    {
        $managerId = $this->managerByDepartment[$employee['departement']] ?? $this->userIds['MAT-002'];
        if ($managerId === $employee['user_id']) {
            // Éviter qu'un responsable valide sa propre demande ou sa propre évaluation.
            return $employee['role'] === 'Responsable RH' ? $this->userIds['MAT-001'] : $this->userIds['MAT-002'];
        }
        return $managerId;
    }

    private function seedLeaveRequestsForMonth(int $year, int $month, int $monthIndex, int $count, Carbon $limit): void
    {
        $usedEmployees = [];
        $maxDay = $limit->day;
        $statuses = ['approuve', 'refuse', 'en_attente'];
        $motifs = [
            'Repos annuel et récupération.',
            'Rendez-vous médical.',
            'Événement familial.',
            'Démarche administrative personnelle.',
            'Repos exceptionnel demandé par l’employé.',
        ];

        for ($i = 0; $i < $count; $i++) {
            $targetDay = min($maxDay, 2 + (($monthIndex * 2 + $i * 3) % max(1, $maxDay - 1)));
            $date = Carbon::create($year, $month, $targetDay)->toDateString();
            $eligible = $this->employeesActiveAt($date);
            if ($eligible === []) {
                for ($day = 1; $day <= $maxDay && $eligible === []; $day++) {
                    $date = Carbon::create($year, $month, $day)->toDateString();
                    $eligible = $this->employeesActiveAt($date);
                }
            }
            if ($eligible === []) {
                continue;
            }

            $candidate = null;
            $startIndex = ($monthIndex + $i * 3) % count($eligible);
            for ($offset = 0; $offset < count($eligible); $offset++) {
                $try = $eligible[($startIndex + $offset) % count($eligible)];
                if (!isset($usedEmployees[$try['matricule']])) {
                    $candidate = $try;
                    break;
                }
            }
            $candidate ??= $eligible[$startIndex];
            $usedEmployees[$candidate['matricule']] = true;

            $duration = 1 + (($monthIndex + $i) % 2);
            $endDate = Carbon::parse($date)->addDays($duration - 1);
            if ($endDate->gt($limit)) {
                $endDate = $limit->copy();
            }
            if ($candidate['sortie'] !== null && $endDate->toDateString() > $candidate['sortie']) {
                $endDate = Carbon::parse($candidate['sortie']);
            }
            if ($endDate->lt(Carbon::parse($date))) {
                continue;
            }
            $actualDuration = Carbon::parse($date)->diffInDays($endDate) + 1;

            $status = $statuses[($monthIndex + $i) % count($statuses)];
            $validator = $this->validatorFor($candidate);
            $validationDate = $status === 'en_attente'
                ? null
                : Carbon::parse($date)->addDay()->min($limit)->format('Y-m-d') . ' 09:00:00';
            $typeId = $this->leaveTypeIds[($monthIndex + $i) % count($this->leaveTypeIds)];
            $justificatifPath = ($monthIndex + $i) % 4 === 0
                ? 'demo/justificatifs/conge-' . $candidate['matricule'] . '-' . $year . '-' . $month . '.txt'
                : null;
            if ($justificatifPath !== null) {
                Storage::disk('public')->put($justificatifPath, "Justificatif fictif de congé pour {$candidate['matricule']} — {$date}.\n");
            }

            $this->put('demandes_conges', [
                'employe_id' => $candidate['id'],
                'type_conge_id' => $typeId,
                'date_debut' => $date,
            ], [
                'employe_id' => $candidate['id'],
                'type_conge_id' => $typeId,
                'date_debut' => $date,
                'date_fin' => $endDate->toDateString(),
                'nombre_jours' => $actualDuration,
                'motif' => $motifs[($monthIndex + $i) % count($motifs)],
                'justificatif' => $justificatifPath,
                'statut' => $status,
                'commentaire_validation' => $status === 'en_attente' ? null : ($status === 'approuve' ? 'Demande approuvée — donnée fictive.' : 'Demande refusée — donnée fictive.'),
                'valide_par' => $status === 'en_attente' ? null : $validator,
                'date_validation' => $validationDate,
                'created_at' => $date . ' 08:00:00',
            ]);
        }
    }

    private function seedAbsencesForMonth(int $year, int $month, int $monthIndex, Carbon $limit): void
    {
        $count = 1 + ($monthIndex % 2);
        $statuses = ['justifie', 'non_justifie', 'en_attente', 'autorise'];

        for ($i = 0; $i < $count; $i++) {
            $targetDay = min($limit->day, $i === 0 ? 8 : 19);
            $date = Carbon::create($year, $month, $targetDay)->toDateString();
            $eligible = $this->employeesActiveAt($date);
            if ($eligible === []) {
                continue;
            }
            $employee = $eligible[($monthIndex + $i * 5) % count($eligible)];
            $status = $statuses[($monthIndex + $i) % count($statuses)];
            $validated = $status !== 'en_attente';
            $absenceJustificatif = $status === 'justifie'
                ? 'demo/justificatifs/absence-' . $employee['matricule'] . '-' . $date . '.txt'
                : null;
            if ($absenceJustificatif !== null) {
                Storage::disk('public')->put($absenceJustificatif, "Justificatif fictif d’absence pour {$employee['matricule']} — {$date}.\n");
            }
            $this->put('absences', [
                'employe_id' => $employee['id'],
                'date_debut' => $date,
                'date_fin' => $date,
                'motif' => 'Absence de démonstration ' . ($i + 1),
            ], [
                'employe_id' => $employee['id'],
                'date_debut' => $date,
                'date_fin' => $date,
                'nombre_jours' => 1,
                'motif' => $status === 'justifie' ? 'Absence justifiée' : 'Absence administrative ou personnelle',
                'description' => 'Enregistrement fictif destiné aux tests du suivi des absences.',
                'justificatif' => $absenceJustificatif,
                'statut' => $status,
                'valide_par' => $validated ? $this->validatorFor($employee) : null,
                'date_validation' => $validated ? Carbon::parse($date)->addDay()->min($limit)->format('Y-m-d') . ' 10:00:00' : null,
                'commentaire_validation' => $validated ? 'Traitement fictif pour les tests.' : null,
                'origine' => $i === 0 ? 'auto' : 'manuelle',
                'justificatif_path' => $absenceJustificatif,
                'created_at' => $date . ' 18:00:00',
            ]);
        }
    }

    private function workingDate(int $year, int $month, int $targetDay, Carbon $limit): Carbon
    {
        $day = min($targetDay, $limit->day);
        $date = Carbon::create($year, $month, $day)->startOfDay();
        while ($date->isWeekend() && $date->day < $limit->day) {
            $date->addDay();
        }
        while ($date->isWeekend() && $date->day > 1) {
            $date->subDay();
        }
        return $date;
    }

    private function seedAttendanceForMonth(int $year, int $month, int $monthIndex, Carbon $limit): void
    {
        $days = ($year === 2026 && $month === 11) ? [3, 7, 12] : [5, 12, 20, 26];

        foreach (array_values($this->employees) as $employeeIndex => $employee) {
            foreach ($days as $dayIndex => $targetDay) {
                $date = $this->workingDate($year, $month, $targetDay, $limit);
                $dateString = $date->toDateString();
                if (!$this->employeeActiveAt($employee, $dateString)) {
                    continue;
                }

                $pattern = ($employeeIndex + $monthIndex + $dayIndex) % 10;
                $status = $pattern === 0 ? 'absent' : (in_array($pattern, [2, 6], true) ? 'retard' : 'present');
                $isLate = $status === 'retard';
                $isAbsent = $status === 'absent';
                $source = (($employeeIndex + $monthIndex + $dayIndex) % 3 === 0) ? 'manuel' : 'qr_code';
                $entryTime = $isLate ? '08:20:00' : '08:00:00';
                $exitTime = $isAbsent ? null : '17:00:00';
                $hours = $isAbsent ? null : ($isLate ? 7.25 : 8.00);
                $reference = 'DEMO-' . $employee['matricule'] . '-' . $date->format('Ymd');

                $this->put('presences', [
                    'employe_id' => $employee['id'],
                    'date' => $dateString,
                ], [
                    'employe_id' => $employee['id'],
                    'date' => $dateString,
                    'heure_entree' => $isAbsent ? null : $entryTime,
                    'heure_sortie' => $exitTime,
                    'statut' => $status,
                    'retard_minutes' => $isLate ? 20 : 0,
                    'heures_travaillees' => $hours,
                    'source' => $source,
                    'reference_pointage' => $reference,
                    'commentaire' => $isAbsent ? 'Absence simulée pour les tests.' : ($isLate ? 'Arrivée tardive simulée.' : 'Présence normale de démonstration.'),
                    'created_at' => $dateString . ' 17:05:00',
                ]);

                if ($isAbsent) {
                    continue;
                }

                $qrId = $source === 'qr_code' ? ($this->qrIds[$employee['matricule']] ?? null) : null;
                $entryDateTime = $dateString . ' ' . $entryTime;
                $exitDateTime = $dateString . ' ' . $exitTime;

                $this->put('pointages', [
                    'employe_id' => $employee['id'],
                    'date_heure' => $entryDateTime,
                    'type' => 'entree',
                ], [
                    'employe_id' => $employee['id'],
                    'date_heure' => $entryDateTime,
                    'type' => 'entree',
                    'source' => $source,
                    'reference' => 'IN-' . $reference,
                    'terminal' => $source === 'qr_code' ? 'Lecteur QR — accueil' : 'Saisie RH',
                    'donnees' => json_encode(['simulation' => true, 'presence' => $status], JSON_UNESCAPED_UNICODE),
                    'commentaire' => $isLate ? 'Retard simulé.' : 'Pointage d’entrée de démonstration.',
                    'type_scan' => $source,
                    'qr_code_id' => $qrId,
                    'est_anomalie' => $isLate,
                    'description_anomalie' => $isLate ? 'Arrivée après l’heure prévue.' : null,
                    'created_at' => $entryDateTime,
                ]);

                $this->put('pointages', [
                    'employe_id' => $employee['id'],
                    'date_heure' => $exitDateTime,
                    'type' => 'sortie',
                ], [
                    'employe_id' => $employee['id'],
                    'date_heure' => $exitDateTime,
                    'type' => 'sortie',
                    'source' => $source,
                    'reference' => 'OUT-' . $reference,
                    'terminal' => $source === 'qr_code' ? 'Lecteur QR — accueil' : 'Saisie RH',
                    'donnees' => json_encode(['simulation' => true], JSON_UNESCAPED_UNICODE),
                    'commentaire' => 'Pointage de sortie de démonstration.',
                    'type_scan' => $source,
                    'qr_code_id' => $qrId,
                    'est_anomalie' => false,
                    'description_anomalie' => null,
                    'created_at' => $exitDateTime,
                ]);

                if ($source === 'qr_code') {
                    $this->qrScanCounts[$employee['matricule']] += 2;
                    $this->qrLastScans[$employee['matricule']] = $exitDateTime;
                }
            }
        }
    }

    private function seedPayrollForMonth(int $year, int $month, int $monthIndex, Carbon $limit): void
    {
        $employees = $this->employeesActiveInMonth($year, $month);
        foreach ($employees as $employeeIndex => $employee) {
            $hire = Carbon::parse($employee['embauche']);
            $monthStart = Carbon::create($year, $month, 1)->startOfDay();
            $monthEnd = $limit->copy();
            $operationDate = $monthStart->copy()->addDays(9);
            if ($operationDate->lt($hire)) {
                $operationDate = $hire->copy();
            }
            if ($employee['sortie'] !== null && $operationDate->toDateString() > $employee['sortie']) {
                $operationDate = Carbon::parse($employee['sortie']);
            }
            if ($operationDate->gt($monthEnd)) {
                $operationDate = $monthEnd->copy();
            }
            $operationDateString = $operationDate->toDateString();

            $prime = (($employeeIndex + $monthIndex) % 5 === 0) ? 35000 + (($employeeIndex % 3) * 5000) : 0;
            $overtimeHours = (($employeeIndex + $monthIndex) % 4 === 0) ? 3.5 : 0;
            $overtimeAmount = $overtimeHours > 0 ? (int) round($employee['salaire'] / 173 * $overtimeHours * 1.3) : 0;
            $retenue = (($employeeIndex + $monthIndex) % 7 === 0) ? 15000 : 0;

            if ($prime > 0) {
                $this->put('primes', [
                    'employe_id' => $employee['id'],
                    'date_prime' => $operationDateString,
                    'type_prime' => 'Prime de performance',
                ], [
                    'employe_id' => $employee['id'],
                    'type_prime' => 'Prime de performance',
                    'montant' => $prime,
                    'date_prime' => $operationDateString,
                    'statut' => 'accordee',
                    'motif' => 'Résultats et implication — simulation.',
                    'commentaire' => 'Prime fictive pour les tests de paie.',
                    'created_at' => $operationDateString . ' 16:00:00',
                ]);
            }

            if ($overtimeHours > 0) {
                $this->put('heures_supplementaires', [
                    'employe_id' => $employee['id'],
                    'date' => $operationDateString,
                ], [
                    'employe_id' => $employee['id'],
                    'date' => $operationDateString,
                    'nombre_heures' => $overtimeHours,
                    'taux' => 30.00,
                    'montant' => $overtimeAmount,
                    'statut' => 'validee',
                    'motif' => 'Intervention ponctuelle en dehors des horaires habituels.',
                    'commentaire' => 'Heures supplémentaires fictives.',
                    'created_at' => $operationDateString . ' 17:30:00',
                ]);
            }

            if ($retenue > 0) {
                $this->put('retenues', [
                    'employe_id' => $employee['id'],
                    'date_retenue' => $operationDateString,
                    'type_retenue' => 'Retenue exceptionnelle',
                ], [
                    'employe_id' => $employee['id'],
                    'type_retenue' => 'Retenue exceptionnelle',
                    'montant' => $retenue,
                    'date_retenue' => $operationDateString,
                    'statut' => 'active',
                    'motif' => 'Régularisation fictive.',
                    'commentaire' => 'Retenue de démonstration, sans valeur comptable.',
                    'created_at' => $operationDateString . ' 17:00:00',
                ]);
            }

            $gross = $employee['salaire'] + $prime + $overtimeAmount;
            $net = max(0, $gross - $retenue);
            $period = sprintf('%02d', $month);
            $generationDate = $limit->toDateString();
            $isCurrentPartialMonth = $year === 2026 && $month === 11;

            $bulletin = $this->put('bulletins_paie', [
                'employe_id' => $employee['id'],
                'periode' => $period,
                'annee' => $year,
            ], [
                'employe_id' => $employee['id'],
                'periode' => $period,
                'annee' => $year,
                'salaire_base' => $employee['salaire'],
                'total_primes' => $prime,
                'total_heures_supplementaires' => $overtimeAmount,
                'total_retenues' => $retenue,
                'salaire_brut' => $gross,
                'salaire_net' => $net,
                'statut' => $isCurrentPartialMonth ? 'brouillon' : 'paye',
                'date_generation' => $generationDate,
                'commentaire' => 'Bulletin fictif de démonstration ; calcul simplifié, sans cotisations légales.',
                'created_at' => $generationDate . ' 17:00:00',
            ]);

            $lines = [
                ['salaire', 'Salaire de base', $employee['salaire']],
                ['prime', 'Prime de performance', $prime],
                ['heures_supplementaires', 'Heures supplémentaires', $overtimeAmount],
                ['retenue', 'Retenue exceptionnelle', $retenue],
            ];
            foreach ($lines as [$type, $label, $amount]) {
                if ($amount <= 0 && $type !== 'salaire') {
                    continue;
                }
                $this->put('lignes_bulletins_paie', [
                    'bulletin_paie_id' => $bulletin->id,
                    'type' => $type,
                    'libelle' => $label,
                ], [
                    'bulletin_paie_id' => $bulletin->id,
                    'type' => $type,
                    'libelle' => $label,
                    'quantite' => null,
                    'taux' => null,
                    'montant' => $amount,
                    'created_at' => $generationDate . ' 17:00:00',
                ]);
            }
        }
    }

    private function seedDocuments(): void
    {
        foreach ($this->employees as $employee) {
            $matricule = $employee['matricule'];
            $documents = [
                [
                    'categorie' => 'contrat',
                    'titre' => 'Contrat de travail — ' . $matricule,
                    'filename' => 'contrat-' . $matricule . '.txt',
                    'path' => 'demo-sgrh/' . $matricule . '/contrat.txt',
                    'contrat_id' => $this->contractIds[$matricule],
                    'mois' => null,
                    'annee' => null,
                    'date' => $employee['embauche'],
                ],
                [
                    'categorie' => 'attestation',
                    'titre' => 'Attestation administrative — ' . $matricule,
                    'filename' => 'attestation-' . $matricule . '.txt',
                    'path' => 'demo-sgrh/' . $matricule . '/attestation.txt',
                    'contrat_id' => null,
                    'mois' => null,
                    'annee' => null,
                    'date' => $employee['embauche'],
                ],
            ];

            $payDate = $employee['sortie'] ? Carbon::parse($employee['sortie']) : $this->dateLimite->copy();
            $documents[] = [
                'categorie' => 'fiche_paie',
                'titre' => 'Exemple de fiche de paie — ' . $matricule,
                'filename' => 'fiche-paie-' . $matricule . '.txt',
                'path' => 'demo-sgrh/' . $matricule . '/fiche-paie.txt',
                'contrat_id' => null,
                'mois' => (int) $payDate->format('m'),
                'annee' => (int) $payDate->format('Y'),
                'date' => $payDate->toDateString(),
            ];

            foreach ($documents as $document) {
                $content = "DOCUMENT FICTIF DE DEMONSTRATION\n\n";
                $content .= "SGRH — MadaTech Solutions\n";
                $content .= "Employé : {$employee['prenom']} {$employee['nom']}\n";
                $content .= "Matricule : {$matricule}\n";
                $content .= "Type : {$document['categorie']}\n";
                $content .= "Date de référence : {$document['date']}\n\n";
                $content .= "Ce fichier est une donnée de test. Il ne constitue pas un document administratif réel.\n";
                Storage::disk('public')->put($document['path'], $content);

                $this->put('documents', [
                    'employe_id' => $employee['id'],
                    'fichier_path' => $document['path'],
                ], [
                    'employe_id' => $employee['id'],
                    'contrat_id' => $document['contrat_id'],
                    'categorie' => $document['categorie'],
                    'titre' => $document['titre'],
                    'description' => 'Fichier texte fictif généré par le seeder pour tester le module documentaire.',
                    'fichier_path' => $document['path'],
                    'fichier_nom_original' => $document['filename'],
                    'fichier_mime' => 'text/plain',
                    'fichier_taille' => strlen($content),
                    'mois' => $document['mois'],
                    'annee' => $document['annee'],
                    'uploaded_by' => $this->userIds['MAT-002'],
                    'deleted_at' => null,
                    'created_at' => $document['date'] . ' 10:00:00',
                ]);
            }
        }
    }

    private function seedTrainingSkillsAndEvaluations(): void
    {
        $trainingData = [
            ['Initiation à Laravel', 'Développement web avec le framework Laravel.', 'TACTINFO Formation', '2025-03-10', '2025-03-14', 'terminee', 24, 250000],
            ['Excel et suivi administratif', 'Utilisation des feuilles de calcul pour le suivi RH.', 'MadaTech Academy', '2025-06-16', '2025-06-18', 'terminee', 18, 180000],
            ['Sécurité informatique', 'Bonnes pratiques de sécurité et protection des données.', 'MadaTech Academy', '2025-10-06', '2025-10-07', 'terminee', 12, 220000],
            ['Gestion de la paie', 'Principes de base du traitement de la paie.', 'Centre de formation RH', '2026-03-16', '2026-03-18', 'terminee', 18, 300000],
            ['Communication professionnelle', 'Communication, collaboration et organisation du travail.', 'MadaTech Academy', '2026-08-10', '2026-08-11', 'terminee', 12, 150000],
            ['Sensibilisation à la cybersécurité', 'Prévention des risques numériques et sécurité des comptes.', 'MadaTech Academy', '2026-11-10', '2026-11-11', 'terminee', 6, 100000],
        ];

        $trainingIds = [];
        foreach ($trainingData as $index => [$nom, $description, $organisme, $start, $end, $status, $hours, $cost]) {
            $training = $this->put('formations', ['nom' => $nom], [
                'nom' => $nom,
                'description' => $description,
                'organisme' => $organisme,
                'formateur' => 'Formateur de démonstration',
                'lieu' => 'Antananarivo',
                'type' => 'Professionnelle',
                'duree_heures' => $hours,
                'date_debut' => $start,
                'date_fin' => $end,
                'capacite' => 20,
                'cout' => $cost,
                'statut' => $status,
                'created_at' => $start . ' 08:00:00',
            ]);
            $trainingIds[] = ['id' => (string) $training->id, 'start' => $start, 'end' => $end, 'status' => $status, 'index' => $index];
        }

        foreach ($trainingIds as $training) {
            $eligible = array_values(array_filter(
                $this->employeesActiveAt($training['start']),
                fn (array $employee) => $this->employeeActiveAt($employee, $training['end'])
            ));
            foreach ($eligible as $employeeIndex => $employee) {
                if (($employeeIndex + $training['index']) % 2 !== 0) {
                    continue;
                }
                $completed = $training['status'] === 'terminee';
                $hasCertificate = $completed && $employeeIndex % 3 !== 0;
                $certificatePath = $hasCertificate
                    ? 'demo-sgrh/certificats/' . $employee['matricule'] . '-formation-' . $training['index'] . '.txt'
                    : null;
                if ($certificatePath !== null) {
                    Storage::disk('public')->put($certificatePath, "CERTIFICAT FICTIF\n{$employee['prenom']} {$employee['nom']} — {$training['start']} au {$training['end']}\nFormation : démonstration SGRH.\n");
                }
                $this->put('employes_formations', [
                    'employe_id' => $employee['id'],
                    'formation_id' => $training['id'],
                ], [
                    'employe_id' => $employee['id'],
                    'formation_id' => $training['id'],
                    'date_inscription' => max($employee['embauche'], Carbon::parse($training['start'])->subDays(10)->toDateString()),
                    'date_debut' => $completed ? $training['start'] : null,
                    'date_fin' => $completed ? $training['end'] : null,
                    'statut' => $completed ? 'terminee' : 'inscrite',
                    'note' => $completed ? (70 + (($employeeIndex * 7 + $training['index']) % 29)) : null,
                    'resultat' => $completed ? 'Réussi' : null,
                    'certificat' => $hasCertificate,
                    'fichier_certificat' => $certificatePath,
                    'commentaire' => 'Participation fictive pour les tests.',
                    'created_at' => $training['start'] . ' 08:00:00',
                ]);
            }
        }

        $skillNames = [
            ['HTML et CSS', 'Technique'], ['JavaScript', 'Technique'], ['Laravel', 'Technique'],
            ['PHP', 'Technique'], ['MySQL', 'Technique'], ['Gestion administrative RH', 'Métier'],
            ['Comptabilité', 'Métier'], ['Réseaux informatiques', 'Technique'],
            ['Communication', 'Transversale'], ['Leadership', 'Transversale'],
        ];
        $skillIds = [];
        foreach ($skillNames as [$nom, $category]) {
            $skill = $this->put('competences', ['nom' => $nom], [
                'nom' => $nom,
                'categorie' => $category,
                'description' => 'Compétence suivie dans le cadre des données de démonstration.',
                'statut' => 'actif',
            ]);
            $skillIds[] = (string) $skill->id;
        }

        foreach (array_values($this->employees) as $employeeIndex => $employee) {
            for ($j = 0; $j < 4; $j++) {
                $skillIndex = ($employeeIndex + $j * 2) % count($skillIds);
                $score = 55 + (($employeeIndex * 9 + $j * 7) % 45);
                $level = $score >= 90 ? 'expert' : ($score >= 80 ? 'avance' : ($score >= 65 ? 'intermediaire' : 'debutant'));
                $dateEvaluationCarbon = Carbon::parse($employee['embauche'])->addMonths(2 + $j)->min($this->dateLimite);
                if ($employee['sortie'] !== null && $dateEvaluationCarbon->toDateString() > $employee['sortie']) {
                    $dateEvaluationCarbon = Carbon::parse($employee['sortie']);
                }
                $dateEvaluation = $dateEvaluationCarbon->toDateString();
                $this->put('employes_competences', [
                    'employe_id' => $employee['id'],
                    'competence_id' => $skillIds[$skillIndex],
                ], [
                    'employe_id' => $employee['id'],
                    'competence_id' => $skillIds[$skillIndex],
                    'niveau' => $level,
                    'score' => $score,
                    'annees_experience' => round(0.5 + (($employeeIndex + $j) % 5) * 0.5, 2),
                    'date_evaluation' => $dateEvaluation,
                    'evalue_par' => $this->validatorFor($employee),
                    'commentaire' => 'Évaluation de compétence fictive.',
                    'statut' => 'actif',
                ]);
            }
        }

        $levels = [
            ['Insuffisant', 'Résultats en dessous des attentes.', 0.00, 49.99, 1],
            ['À améliorer', 'Des améliorations sont nécessaires.', 50.00, 64.99, 2],
            ['Satisfaisant', 'Objectifs principaux atteints.', 65.00, 79.99, 3],
            ['Très bien', 'Résultats supérieurs aux attentes.', 80.00, 89.99, 4],
            ['Excellent', 'Résultats particulièrement élevés.', 90.00, 99.99, 5],
        ];
        foreach ($levels as [$nom, $description, $minimum, $maximum, $order]) {
            $level = $this->put('niveaux_appreciation', ['nom' => $nom], [
                'nom' => $nom,
                'description' => $description,
                // DECIMAL(4,2) ne peut pas stocker 100.00 : plafond volontaire à 99.99.
                'score_minimum' => $minimum,
                'score_maximum' => $maximum,
                'ordre' => $order,
                'statut' => 'actif',
            ]);
            $this->appreciationLevels[] = ['id' => (string) $level->id, 'min' => $minimum, 'max' => $maximum, 'name' => $nom];
        }

        foreach (array_values($this->employees) as $employeeIndex => $employee) {
            foreach ([2025, 2026] as $year) {
                $periodEnd = $year === 2025 ? Carbon::create(2025, 12, 31) : $this->dateLimite->copy();
                $periodStart = Carbon::create($year, 1, 1);
                if ($employee['embauche'] > $periodEnd->toDateString()) {
                    continue;
                }
                if ($employee['sortie'] !== null && $employee['sortie'] < $periodStart->toDateString()) {
                    continue;
                }
                $start = max($employee['embauche'], $periodStart->toDateString());
                $end = $employee['sortie'] !== null
                    ? min($employee['sortie'], $periodEnd->toDateString())
                    : $periodEnd->toDateString();
                $score = 68 + (($employeeIndex * 7 + $year) % 31);
                $selectedLevel = $this->appreciationLevels[0];
                foreach ($this->appreciationLevels as $level) {
                    if ($score >= $level['min'] && $score <= $level['max']) {
                        $selectedLevel = $level;
                        break;
                    }
                }
                $this->put('evaluations', [
                    'employe_id' => $employee['id'],
                    'type' => 'annuelle',
                    'periode' => (string) $year,
                    'titre' => 'Évaluation annuelle ' . $year,
                ], [
                    'employe_id' => $employee['id'],
                    'evaluateur_id' => $this->validatorFor($employee),
                    'niveau_appreciation_id' => $selectedLevel['id'],
                    'titre' => 'Évaluation annuelle ' . $year,
                    'type' => 'annuelle',
                    'periode' => (string) $year,
                    'date_debut' => $start,
                    'date_fin' => $end,
                    'date_evaluation' => $end,
                    'score_global' => $score,
                    'appreciation' => $selectedLevel['name'],
                    'commentaire' => 'Résultats fictifs destinés à tester le suivi des évaluations.',
                    'recommandation' => 'Poursuivre le développement des compétences et le suivi des objectifs.',
                    'statut' => 'terminee',
                    'created_at' => $end . ' 16:00:00',
                ]);
            }
        }
    }

    private function seedJobOffers(): void
    {
        $offers = [
            ['Développeur web junior', 'Stage', '2025-02-05', '2025-03-05', 'archivee'],
            ['Technicien réseau', 'CDI', '2025-05-10', '2025-06-10', 'archivee'],
            ['Assistant ressources humaines', 'Intérim', '2025-09-15', '2025-10-15', 'archivee'],
            ['Comptable junior', 'CDI', '2025-12-02', '2026-01-02', 'archivee'],
            ['Développeur Laravel', 'CDI', '2026-02-03', '2026-03-05', 'archivee'],
            ['Technicien support informatique', 'Freelance', '2026-05-10', '2026-06-10', 'archivee'],
            ['Développeur front-end', 'Stage', '2026-10-15', '2026-11-20', 'publiee'],
            ['Gestionnaire de paie', 'CDI', '2026-11-01', '2026-11-30', 'publiee'],
            ['Assistant administratif', 'CDD', '2026-11-10', '2026-12-10', 'brouillon'],
        ];

        foreach ($offers as $index => [$title, $contractType, $publishedAt, $deadline, $status]) {
            $slug = Str::slug($title) . '-' . substr(str_replace('-', '', $publishedAt), 0, 8);
            $this->put('offre_emplois', ['slug' => $slug], [
                'titre' => $title,
                'slug' => $slug,
                'description' => 'Offre fictive publiée pour tester la consultation des offres d’emploi.',
                'missions' => 'Participer aux activités du poste, collaborer avec l’équipe et assurer un suivi régulier.',
                'profil_recherche' => 'Personne organisée, motivée, rigoureuse et disposant des compétences liées au poste.',
                'type_contrat' => $contractType,
                'lieu' => 'Antananarivo, Madagascar',
                'experience' => $index % 2 === 0 ? 'Débutant accepté' : '1 à 2 ans',
                'niveau_etude' => $index % 2 === 0 ? 'Bac+2' : 'Bac+3',
                'salaire' => (600000 + ($index * 75000)) . ' MGA',
                'date_limite' => $deadline,
                'email_contact' => 'rh@madatech.test',
                'statut' => $status,
                'publiee_le' => $status === 'brouillon' ? null : $publishedAt . ' 08:00:00',
                'created_by' => $this->userIds['MAT-002'],
                'created_at' => $publishedAt . ' 08:00:00',
            ]);
        }
    }

    private function seedNotificationsAndHistory(): void
    {
        foreach (array_values($this->employees) as $index => $employee) {
            $userId = $employee['user_id'];
            $date = $employee['embauche'];
            $messages = [
                ['Bienvenue dans le SGRH', 'Votre compte de démonstration est prêt à être utilisé.', 'info'],
                ['Mise à jour des informations', 'Vérifiez les informations de votre profil utilisateur.', 'success'],
            ];
            foreach ($messages as $messageIndex => [$title, $message, $type]) {
                $createdAt = Carbon::parse($date)->addDays($messageIndex + 1)->format('Y-m-d H:i:s');
                $read = (($index + $messageIndex) % 2 === 0);
                $this->put('notifications', [
                    'user_id' => $userId,
                    'titre' => $title,
                    'message' => $message,
                ], [
                    'user_id' => $userId,
                    'titre' => $title,
                    'message' => $message,
                    'type' => $type,
                    'lue' => $read,
                    'date_lecture' => $read ? $createdAt : null,
                    'url' => null,
                    'created_at' => $createdAt,
                ]);
            }

            $this->put('historiques_actions', [
                'user_id' => $userId,
                'action' => 'creation',
                'module' => 'employes',
                'cible_id' => $employee['id'],
            ], [
                'user_id' => $userId,
                'action' => 'creation',
                'module' => 'employes',
                'description' => 'Création du dossier de démonstration ' . $employee['matricule'] . '.',
                'type_cible' => 'Employe',
                'cible_id' => $employee['id'],
                'ancienne_valeur' => null,
                'nouvelle_valeur' => json_encode(['matricule' => $employee['matricule'], 'statut' => $employee['sortie'] ? 'sorti' : 'actif'], JSON_UNESCAPED_UNICODE),
                'adresse_ip' => '127.0.0.1',
                'user_agent' => 'Seeder SGRH — données fictives',
                'created_at' => $date . ' 09:00:00',
            ]);
        }
    }

    private function finalizeQrStatistics(): void
    {
        foreach ($this->qrScanCounts as $matricule => $count) {
            $this->put('qr_codes', ['code' => $matricule], [
                'scan_count' => $count,
                'dernier_scan_le' => $this->qrLastScans[$matricule],
            ]);
        }
    }
}
