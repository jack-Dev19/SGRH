<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Données de test du SGRH.
 *
 * - Utilise les UUID.
 * - Respecte les noms de tables/colonnes relevés dans les migrations fournies.
 * - Peut être relancé sans dupliquer les données (recherche par clés naturelles).
 * - À exécuter uniquement dans un environnement de développement/test.
 */
class SgrhTestDataSeeder extends Seeder
{
    private function put(string $table, array $keys, array $data): ?object
    {
        if (!Schema::hasTable($table)) {
            $this->command?->warn("Table absente, ignorée : {$table}");
            return null;
        }

        // N'envoyer que les colonnes réellement présentes dans la migration.
        $data = array_filter(
            $data,
            fn ($value, $column) => Schema::hasColumn($table, $column),
            ARRAY_FILTER_USE_BOTH
        );

        $query = DB::table($table);
        foreach ($keys as $column => $value) {
            if (!Schema::hasColumn($table, $column)) {
                throw new RuntimeException("Colonne {$table}.{$column} absente.");
            }
            $query->where($column, $value);
        }

        $existing = $query->first();

        if ($existing) {
            $id = $existing->id ?? null;
            $updates = $data;
            unset($updates['id'], $updates['created_at']);
            if (Schema::hasColumn($table, 'updated_at')) {
                $updates['updated_at'] = now();
            }
            if ($updates) {
                DB::table($table)->where($keys)->update($updates);
            }
            return DB::table($table)->where($keys)->first();
        }

        if (Schema::hasColumn($table, 'id') && !isset($data['id'])) {
            $data['id'] = (string) Str::uuid();
        }
        if (Schema::hasColumn($table, 'created_at') && !isset($data['created_at'])) {
            $data['created_at'] = now();
        }
        if (Schema::hasColumn($table, 'updated_at') && !isset($data['updated_at'])) {
            $data['updated_at'] = now();
        }

        DB::table($table)->insert($data);
        return DB::table($table)->where($keys)->first();
    }

    private function id(string $table, array $keys): string
    {
        $query = DB::table($table);
        foreach ($keys as $column => $value) {
            $query->where($column, $value);
        }
        $row = $query->first();
        if (!$row || !isset($row->id)) {
            throw new RuntimeException("Enregistrement introuvable dans {$table} : " . json_encode($keys));
        }
        return $row->id;
    }

    public function run(): void
    {
        DB::transaction(function () {
            $this->seedRolesUsers();
            $this->seedOrganisation();
            $this->seedCongeTemps();
            $this->seedDocumentsFormationsCompetences();
            $this->seedEvaluationsPaie();
            $this->seedRecrutement();
            $this->seedSuivi();
        });

        $this->command?->info('Seeders SGRH terminés. Les UUID et les clés étrangères ont été générés/résolus.');
    }

    private function seedRolesUsers(): void
    {
        $roles = [
            ['Administrateur', 'Administration complète du système'],
            ['Responsable RH', 'Gestion des ressources humaines'],
            ['Chef de département', 'Suivi de son équipe'],
            ['Employé', 'Accès à ses propres informations'],
            ['Recruteur', 'Gestion du recrutement'],
        ];

        foreach ($roles as [$nom, $description]) {
            $this->put('roles', ['nom' => $nom], [
                'nom' => $nom, 'description' => $description,
            ]);
        }

        $users = [
            ['Andry Rakoto', 'admin@madatech.test', 'ADM001', 'Administrateur'],
            ['Hanitra Rabe', 'rh@madatech.test', 'RH001', 'Responsable RH'],
            ['Hery Randria', 'manager@madatech.test', 'MGR001', 'Chef de département'],
            ['Mialy Rakoto', 'mialy@madatech.test', 'EMP001', 'Employé'],
            ['Tiana Rasoanaivo', 'tiana@madatech.test', 'EMP002', 'Employé'],
            ['Fara Andriamihaja', 'recruteur@madatech.test', 'REC001', 'Recruteur'],
        ];

        foreach ($users as [$name, $email, $matricule, $roleNom]) {
            $roleId = $this->id('roles', ['nom' => $roleNom]);
            $this->put('users', ['email' => $email], [
                'name' => $name,
                'email' => $email,
                'matricule' => $matricule,
                'role_id' => $roleId,
                'password' => Hash::make('Test1234!'),
                'statut' => true,
                'email_verified_at' => now(),
            ]);
        }
    }

    private function seedOrganisation(): void
    {
        $departements = [
            ['Direction générale', 'Pilotage général de l’entreprise'],
            ['Ressources humaines', 'Administration du personnel et recrutement'],
            ['Développement informatique', 'Développement et maintenance des applications'],
            ['Réseaux et systèmes', 'Infrastructure, réseaux et support informatique'],
            ['Administration et finances', 'Comptabilité et gestion financière'],
        ];
        foreach ($departements as [$nom, $description]) {
            $this->put('departements', ['nom' => $nom], ['nom' => $nom, 'description' => $description, 'statut' => 'actif']);
        }

        $postes = [
            ['Directeur général', 'Direction générale', 'Direction', 1800000, 3000000],
            ['Responsable RH', 'Ressources humaines', 'Responsable', 1200000, 2000000],
            ['Développeur web', 'Développement informatique', 'Junior', 800000, 1300000],
            ['Développeur full-stack', 'Développement informatique', 'Confirmé', 1200000, 2200000],
            ['Technicien réseau', 'Réseaux et systèmes', 'Technicien', 700000, 1200000],
            ['Assistant RH', 'Ressources humaines', 'Assistant', 650000, 1000000],
            ['Comptable', 'Administration et finances', 'Employé', 800000, 1400000],
        ];
        foreach ($postes as [$nom, $depNom, $niveau, $min, $max]) {
            $this->put('postes', ['nom' => $nom, 'departement_id' => $this->id('departements', ['nom' => $depNom])], [
                'nom' => $nom,
                'departement_id' => $this->id('departements', ['nom' => $depNom]),
                'description' => "Poste de {$nom} chez MadaTech Solutions.",
                'niveau' => $niveau,
                'salaire_min' => $min,
                'salaire_max' => $max,
                'statut' => 'actif',
            ]);
        }

        $employes = [
            ['admin@madatech.test', 'MAT-001', 'Rakoto', 'Andry', 'Homme', '1985-02-14', '0340000001', 'Direction générale', 'Directeur général', '2022-01-03'],
            ['rh@madatech.test', 'MAT-002', 'Rabe', 'Hanitra', 'Femme', '1990-06-22', '0340000002', 'Ressources humaines', 'Responsable RH', '2023-03-01'],
            ['manager@madatech.test', 'MAT-003', 'Randria', 'Hery', 'Homme', '1992-11-09', '0340000003', 'Développement informatique', 'Développeur full-stack', '2024-01-15'],
            ['mialy@madatech.test', 'MAT-004', 'Rakoto', 'Mialy', 'Femme', '1998-04-12', '0340000004', 'Développement informatique', 'Développeur web', '2025-01-06'],
            ['tiana@madatech.test', 'MAT-005', 'Rasoanaivo', 'Tiana', 'Homme', '1996-08-20', '0340000005', 'Réseaux et systèmes', 'Technicien réseau', '2025-06-02'],
            ['recruteur@madatech.test', 'MAT-006', 'Andriamihaja', 'Fara', 'Femme', '1995-12-03', '0340000006', 'Ressources humaines', 'Assistant RH', '2025-09-01'],
        ];

        foreach ($employes as [$email, $matricule, $nom, $prenom, $sexe, $naissance, $telephone, $depNom, $posteNom, $embauche]) {
            $userId = $this->id('users', ['email' => $email]);
            $depId = $this->id('departements', ['nom' => $depNom]);
            $posteId = $this->id('postes', ['nom' => $posteNom, 'departement_id' => $depId]);
            $this->put('employes', ['matricule' => $matricule], [
                'user_id' => $userId, 'departement_id' => $depId, 'poste_id' => $posteId,
                'matricule' => $matricule, 'nom' => $nom, 'prenom' => $prenom, 'sexe' => $sexe,
                'date_naissance' => $naissance, 'telephone' => $telephone, 'email' => $email,
                'adresse' => 'Antananarivo, Madagascar', 'date_embauche' => $embauche, 'statut' => 'actif',
            ]);
        }

        
        // Les responsables des départements sont des utilisateurs.
        // La colonne departements.responsable_id référence users.id.
        $responsables = [
            'Direction générale' => 'admin@madatech.test',
            'Ressources humaines' => 'rh@madatech.test',
            'Développement informatique' => 'manager@madatech.test',
            'Réseaux et systèmes' => 'tiana@madatech.test',
            'Administration et finances' => 'rh@madatech.test',
        ];

        foreach ($responsables as $depNom => $emailResponsable) {
            // Récupérer l'UUID de l'utilisateur, et non celui de l'employé.
            $responsableId = $this->id('users', [
                'email' => $emailResponsable,
            ]);

            // Mettre à jour le département avec le bon UUID.
            $this->put('departements', ['nom' => $depNom], [
                'nom' => $depNom,
                'responsable_id' => $responsableId,
            ]);
        }

        $contrats = [
            ['MAT-001', 'CTR-001', 'CDI', '2022-01-03', null, 2500000],
            ['MAT-002', 'CTR-002', 'CDI', '2023-03-01', null, 1600000],
            ['MAT-003', 'CTR-003', 'CDI', '2024-01-15', null, 1800000],
            ['MAT-004', 'CTR-004', 'CDD', '2025-01-06', '2026-12-31', 1000000],
            ['MAT-005', 'CTR-005', 'CDI', '2025-06-02', null, 950000],
            ['MAT-006', 'CTR-006', 'CDD', '2025-09-01', '2026-11-30', 850000],
        ];
        foreach ($contrats as [$mat, $numero, $type, $debut, $fin, $salaire]) {
            $this->put('contrats', ['numero' => $numero], [
                'employe_id' => $this->id('employes', ['matricule' => $mat]),
                'numero' => $numero, 'type' => $type, 'date_debut' => $debut, 'date_fin' => $fin,
                'salaire' => $salaire, 'devise' => 'MGA', 'periode_essai' => 3,
                'unite_periode_essai' => 'mois', 'statut' => 'actif',
                'description' => 'Contrat fictif pour les tests du SGRH.',
            ]);
        }
    }

    private function seedCongeTemps(): void
    {
        $types = [
            ['Congé annuel', 'ANNUEL', 'Congé annuel payé', 30, false],
            ['Congé maladie', 'MALADIE', 'Congé pour raison de santé', 30, true],
            ['Congé exceptionnel', 'EXCEPTIONNEL', 'Événement exceptionnel autorisé', 5, true],
        ];
        foreach ($types as [$nom, $code, $desc, $duree, $justif]) {
            $this->put('types_conges', ['nom' => $nom], [
                'nom' => $nom, 'code' => $code, 'description' => $desc,
                'duree_max' => $duree, 'necessite_justificatif' => $justif, 'statut' => 'actif',
            ]);
        }

        $conges = [
            ['MAT-004', 'ANNUEL', '2026-11-02', '2026-11-06', 5, 'Vacances annuelles', 'en_attente', null],
            ['MAT-005', 'MALADIE', '2026-09-14', '2026-09-15', 2, 'Repos médical', 'approuve', 'rh@madatech.test'],
            ['MAT-006', 'ANNUEL', '2026-12-01', '2026-12-05', 5, 'Congé planifié', 'refuse', 'rh@madatech.test'],
        ];
        foreach ($conges as [$mat, $code, $debut, $fin, $jours, $motif, $statut, $valideur]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'date_debut' => $debut, 'date_fin' => $fin];
            $this->put('demandes_conges', $keys, [
                ...$keys,
                'type_conge_id' => $this->id('types_conges', ['code' => $code]),
                'nombre_jours' => $jours, 'motif' => $motif, 'statut' => $statut,
                'valide_par' => $valideur ? $this->id('users', ['email' => $valideur]) : null,
                'date_validation' => $valideur ? now() : null,
                'commentaire_validation' => $valideur ? 'Décision fictive pour les tests.' : null,
            ]);
        }

        $horaires = [
            ['Horaire standard', '08:00:00', '17:00:00', '12:00:00', '13:00:00', 1, 8],
            ['Horaire demi-journée', '08:00:00', '12:00:00', null, null, 0, 4],
        ];
        foreach ($horaires as [$nom, $debut, $fin, $pauseDebut, $pauseFin, $pause, $heures]) {
            $this->put('horaires_travail', ['nom' => $nom], [
                'nom' => $nom, 'description' => 'Horaire fictif de test.',
                'heure_debut' => $debut, 'heure_fin' => $fin, 'debut_pause' => $pauseDebut,
                'fin_pause' => $pauseFin, 'duree_pause' => $pause, 'tolerance_retard' => 10,
                'heures_prevues' => $heures, 'statut' => 'actif',
            ]);
        }

        // Convention Laravel/PHP : 0 = dimanche, 1 = lundi, ..., 6 = samedi.
        foreach (['Horaire standard' => [1,2,3,4,5], 'Horaire demi-journée' => [6]] as $nom => $joursTravail) {
            $horaireId = $this->id('horaires_travail', ['nom' => $nom]);
            for ($jour = 0; $jour <= 6; $jour++) {
                $travaille = in_array($jour, $joursTravail, true);
                $debut = $nom === 'Horaire standard' ? '08:00:00' : '08:00:00';
                $fin = $nom === 'Horaire standard' ? '17:00:00' : '12:00:00';
                $this->put('horaires_jours', ['horaire_id' => $horaireId, 'jour_semaine' => $jour], [
                    'horaire_id' => $horaireId, 'jour_semaine' => $jour, 'est_travaille' => $travaille,
                    'heure_debut' => $travaille ? $debut : null, 'heure_fin' => $travaille ? $fin : null,
                    'heures_prevues' => $travaille ? ($nom === 'Horaire standard' ? 8 : 4) : 0,
                ]);
            }
        }

        foreach (['MAT-003' => 'Horaire standard', 'MAT-004' => 'Horaire standard', 'MAT-005' => 'Horaire standard', 'MAT-006' => 'Horaire demi-journée'] as $mat => $horaireNom) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'date_debut' => '2026-01-01'];
            $this->put('affectations_horaires', $keys, [
                ...$keys, 'horaire_id' => $this->id('horaires_travail', ['nom' => $horaireNom]),
                'date_fin' => null, 'statut' => 'active', 'motif' => 'Affectation de test',
            ]);
        }

        foreach ([
            ['Fête de l’Indépendance', '2026-06-26'],
            ['Assomption', '2026-08-15'],
            ['Journée de test', '2026-11-01'],
        ] as [$nom, $date]) {
            $this->put('jours_feries', ['date' => $date], [
                'nom' => $nom, 'date' => $date, 'description' => 'Donnée de test, à vérifier avec le calendrier officiel.',
                'est_chome' => true, 'statut' => 'actif',
            ]);
        }

        foreach ([
            ['MAT-004', '2026-10-05', '08:00:00', '17:00:00', 'present', 0, 8],
            ['MAT-005', '2026-10-05', '08:15:00', '17:05:00', 'present', 15, 7.83],
            ['MAT-006', '2026-10-05', '08:00:00', '12:00:00', 'present', 0, 4],
        ] as [$mat, $date, $entree, $sortie, $statut, $retard, $heures]) {
            $this->put('presences', ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'date' => $date], [
                'employe_id' => $this->id('employes', ['matricule' => $mat]), 'date' => $date,
                'heure_entree' => $entree, 'heure_sortie' => $sortie, 'statut' => $statut,
                'retard_minutes' => $retard, 'heures_travaillees' => $heures, 'source' => 'manuel',
                'commentaire' => 'Présence fictive de test.',
            ]);
            foreach ([[$entree, 'entree'], [$sortie, 'sortie']] as [$heure, $type]) {
                $dt = $date . ' ' . $heure;
                $this->put('pointages', ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'date_heure' => $dt, 'type' => $type], [
                    'employe_id' => $this->id('employes', ['matricule' => $mat]),
                    'date_heure' => $dt, 'type' => $type, 'source' => 'manuel',
                    'commentaire' => 'Pointage fictif de test.',
                ]);
            }
        }

        $this->put('absences', ['employe_id' => $this->id('employes', ['matricule' => 'MAT-004']), 'date_debut' => '2026-09-10', 'date_fin' => '2026-09-10'], [
            'employe_id' => $this->id('employes', ['matricule' => 'MAT-004']),
            'date_debut' => '2026-09-10', 'date_fin' => '2026-09-10', 'nombre_jours' => 1,
            'motif' => 'Absence exceptionnelle', 'description' => 'Donnée fictive pour test.',
            'statut' => 'justifie', 'valide_par' => $this->id('users', ['email' => 'rh@madatech.test']),
            'date_validation' => now(), 'commentaire_validation' => 'Justificatif simulé.',
        ]);
    }

    private function seedDocumentsFormationsCompetences(): void
    {
        foreach ([
            ['MAT-003', 'Contrat', 'contrat_mat003.pdf'],
            ['MAT-004', 'CV', 'cv_mat004.pdf'],
            ['MAT-005', 'Diplôme', 'diplome_mat005.pdf'],
            ['MAT-006', 'Certificat', 'certificat_mat006.pdf'],
        ] as [$mat, $type, $fichier]) {
            $this->put('documents_employes', ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'fichier' => $fichier], [
                'employe_id' => $this->id('employes', ['matricule' => $mat]),
                'type_document' => $type, 'nom' => $type . ' - document fictif',
                'fichier' => 'documents/test/' . $fichier, 'nom_original' => $fichier,
                'extension' => 'pdf', 'mime_type' => 'application/pdf', 'taille' => 1024,
                'date_document' => '2026-01-15', 'description' => 'Chemin fictif : aucun fichier réel n’est créé.',
                'statut' => 'actif', 'uploaded_by' => $this->id('users', ['email' => 'rh@madatech.test']),
            ]);
        }

        $formations = [
            ['Laravel et développement web', 20, '2026-03-02', '2026-03-05', 150000],
            ['Administration réseaux', 16, '2026-04-06', '2026-04-08', 120000],
            ['Sensibilisation cybersécurité', 12, '2026-05-11', '2026-05-12', 100000],
            ['Gestion des ressources humaines', 10, '2026-06-01', '2026-06-02', 80000],
        ];
        foreach ($formations as [$nom, $heures, $debut, $fin, $cout]) {
            $this->put('formations', ['nom' => $nom], [
                'nom' => $nom, 'description' => 'Formation fictive pour vérifier les relations.',
                'organisme' => 'MadaTech Academy', 'formateur' => 'Formateur de démonstration',
                'lieu' => 'Antananarivo', 'type' => 'technique', 'duree_heures' => $heures,
                'date_debut' => $debut, 'date_fin' => $fin, 'capacite' => 20, 'cout' => $cout,
                'statut' => 'terminee',
            ]);
        }

        foreach ([
            ['MAT-003', 'Laravel et développement web', 'terminee', 90, 'reussi', true],
            ['MAT-004', 'Laravel et développement web', 'en_cours', null, null, false],
            ['MAT-005', 'Administration réseaux', 'terminee', 85, 'reussi', true],
            ['MAT-006', 'Gestion des ressources humaines', 'inscrite', null, null, false],
        ] as [$mat, $formation, $statut, $note, $resultat, $certificat]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'formation_id' => $this->id('formations', ['nom' => $formation])];
            $this->put('employes_formations', $keys, [
                ...$keys, 'date_inscription' => '2026-01-15', 'date_debut' => '2026-03-02',
                'date_fin' => $statut === 'terminee' ? '2026-03-05' : null, 'statut' => $statut,
                'note' => $note, 'resultat' => $resultat, 'certificat' => $certificat,
                'fichier_certificat' => $certificat ? 'certificats/test.pdf' : null,
            ]);
        }

        $competences = [
            ['HTML/CSS', 'Développement web'], ['JavaScript', 'Développement web'],
            ['PHP/Laravel', 'Développement web'], ['MySQL', 'Base de données'],
            ['Administration réseau', 'Réseaux'], ['Gestion RH', 'Ressources humaines'],
            ['Communication professionnelle', 'Soft skills'],
        ];
        foreach ($competences as [$nom, $categorie]) {
            $this->put('competences', ['nom' => $nom], [
                'nom' => $nom, 'categorie' => $categorie, 'description' => 'Compétence de démonstration.', 'statut' => 'actif',
            ]);
        }

        foreach ([
            ['MAT-003', 'PHP/Laravel', 'avance', 90, 3],
            ['MAT-003', 'MySQL', 'intermediaire', 75, 2],
            ['MAT-004', 'HTML/CSS', 'avance', 88, 3],
            ['MAT-004', 'JavaScript', 'intermediaire', 72, 2],
            ['MAT-005', 'Administration réseau', 'avance', 91, 4],
            ['MAT-006', 'Gestion RH', 'intermediaire', 70, 2],
        ] as [$mat, $competence, $niveau, $score, $annees]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'competence_id' => $this->id('competences', ['nom' => $competence])];
            $this->put('employes_competences', $keys, [
                ...$keys, 'niveau' => $niveau, 'score' => $score, 'annees_experience' => $annees,
                'date_evaluation' => '2026-06-30', 'evalue_par' => $this->id('users', ['email' => 'rh@madatech.test']),
                'commentaire' => 'Évaluation fictive de compétence.', 'statut' => 'actif',
            ]);
        }
    }

    private function seedEvaluationsPaie(): void
    {
        $niveaux = [
            ['Insuffisant', 0, 39.99, 1],
            ['À améliorer', 40, 59.99, 2],
            ['Satisfaisant', 60, 74.99, 3],
            ['Très satisfaisant', 75, 89.99, 4],
            ['Excellent', 90, 100, 5],
        ];
        foreach ($niveaux as [$nom, $min, $max, $ordre]) {
            $this->put('niveaux_appreciation', ['nom' => $nom], [
                'nom' => $nom, 'description' => 'Niveau de test.', 'score_minimum' => $min,
                'score_maximum' => $max, 'ordre' => $ordre, 'statut' => 'actif',
            ]);
        }

        foreach ([
            ['MAT-003', 'Évaluation annuelle 2026', 'annuelle', 85, 'Très satisfaisant'],
            ['MAT-004', 'Évaluation annuelle 2026', 'annuelle', 72, 'Satisfaisant'],
            ['MAT-005', 'Évaluation annuelle 2026', 'annuelle', 90, 'Excellent'],
            ['MAT-006', 'Évaluation annuelle 2026', 'annuelle', 68, 'Satisfaisant'],
        ] as [$mat, $titre, $type, $score, $appreciation]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'titre' => $titre];
            $this->put('evaluations', $keys, [
                ...$keys, 'evaluateur_id' => $this->id('users', ['email' => 'rh@madatech.test']),
                'niveau_appreciation_id' => $this->id('niveaux_appreciation', ['nom' => $appreciation]),
                'type' => $type, 'periode' => '2026', 'date_debut' => '2026-01-01',
                'date_fin' => '2026-06-30', 'date_evaluation' => '2026-06-30',
                'score_global' => $score, 'appreciation' => $appreciation,
                'commentaire' => 'Évaluation fictive pour tester les relations.',
                'recommandation' => 'Poursuivre le développement des compétences.', 'statut' => 'terminee',
            ]);
        }

        $salaires = [
            ['MAT-001', 2500000], ['MAT-002', 1600000], ['MAT-003', 1800000],
            ['MAT-004', 1000000], ['MAT-005', 950000], ['MAT-006', 850000],
        ];
        foreach ($salaires as [$mat, $montant]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'date_debut' => '2026-01-01'];
            $this->put('salaires', $keys, [
                ...$keys, 'salaire_base' => $montant, 'date_fin' => null,
                'motif' => 'Salaire de base de test', 'statut' => 'actif',
                'commentaire' => 'Montant fictif en ariary.',
            ]);
        }

        foreach ([
            ['MAT-003', 'performance', 150000, '2026-09-30'],
            ['MAT-004', 'projet', 75000, '2026-09-30'],
            ['MAT-005', 'exceptionnelle', 50000, '2026-09-30'],
        ] as [$mat, $type, $montant, $date]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'type_prime' => $type, 'date_prime' => $date];
            $this->put('primes', $keys, [
                ...$keys, 'montant' => $montant, 'statut' => 'accordee',
                'motif' => 'Prime fictive de test', 'commentaire' => 'Montant de démonstration en MGA.',
            ]);
        }

        foreach ([
            ['MAT-003', 'test', 20000, '2026-09-30'],
            ['MAT-004', 'test', 10000, '2026-09-30'],
        ] as [$mat, $type, $montant, $date]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'type_retenue' => $type, 'date_retenue' => $date];
            $this->put('retenues', $keys, [
                ...$keys, 'montant' => $montant, 'statut' => 'active',
                'motif' => 'Retenue fictive de test', 'commentaire' => 'Ne représente pas un calcul légal de paie.',
            ]);
        }

        foreach ([
            ['MAT-003', '2026-10-02', 2, 30, 67500, 'validee'],
            ['MAT-004', '2026-10-03', 3, 30, 56250, 'en_attente'],
            ['MAT-005', '2026-10-04', 1, 25, 14844, 'validee'],
        ] as [$mat, $date, $heures, $taux, $montant, $statut]) {
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'date' => $date];
            $this->put('heures_supplementaires', $keys, [
                ...$keys, 'nombre_heures' => $heures, 'taux' => $taux, 'montant' => $montant,
                'statut' => $statut, 'motif' => 'Heures supplémentaires fictives',
            ]);
        }

        $paies = [
            ['MAT-003', 1800000, 150000, 67500, 20000],
            ['MAT-004', 1000000, 75000, 0, 10000],
            ['MAT-005', 950000, 50000, 14844, 0],
            ['MAT-006', 850000, 0, 0, 0],
        ];
        foreach ($paies as [$mat, $base, $primes, $hs, $retenues]) {
            $brut = $base + $primes + $hs;
            $net = $brut - $retenues;
            $keys = ['employe_id' => $this->id('employes', ['matricule' => $mat]), 'periode' => '09', 'annee' => 2026];
            $bulletin = $this->put('bulletins_paie', $keys, [
                ...$keys, 'salaire_base' => $base, 'total_primes' => $primes,
                'total_heures_supplementaires' => $hs, 'total_retenues' => $retenues,
                'salaire_brut' => $brut, 'salaire_net' => $net, 'statut' => 'brouillon',
                'commentaire' => 'Calcul simplifié de test, hors impôts et cotisations.',
            ]);
            $bulletinId = $bulletin->id;
            foreach ([
                ['salaire', 'Salaire de base', $base],
                ['prime', 'Primes', $primes],
                ['heures_supplementaires', 'Heures supplémentaires', $hs],
                ['retenue', 'Retenues', $retenues],
            ] as [$type, $libelle, $montant]) {
                $this->put('lignes_bulletins_paie', [
                    'bulletin_paie_id' => $bulletinId, 'type' => $type, 'libelle' => $libelle,
                ], [
                    'bulletin_paie_id' => $bulletinId, 'type' => $type,
                    'libelle' => $libelle, 'quantite' => null, 'taux' => null, 'montant' => $montant,
                ]);
            }
        }
    }

    private function seedRecrutement(): void
    {
        $offres = [
            ['Développeur web junior', 'Développeur web', 'CDD', 800000, 1300000, 'publiee'],
            ['Technicien réseau', 'Technicien réseau', 'CDI', 700000, 1200000, 'publiee'],
            ['Assistant RH', 'Assistant RH', 'CDD', 650000, 1000000, 'brouillon'],
        ];
        foreach ($offres as [$titre, $poste, $contrat, $min, $max, $statut]) {
            $this->put('offres_emploi', ['titre' => $titre], [
                'poste_id' => $this->id('postes', ['nom' => $poste, 'departement_id' => $this->id('departements', [
                    'nom' => $poste === 'Technicien réseau' ? 'Réseaux et systèmes' : ($poste === 'Assistant RH' ? 'Ressources humaines' : 'Développement informatique')
                ])]),
                'titre' => $titre, 'description' => 'Offre fictive pour les tests de recrutement.',
                'profil_recherche' => 'Profil junior avec motivation et bases techniques.',
                'competences_requises' => 'Communication, rigueur, compétences liées au poste.',
                'type_contrat' => $contrat, 'salaire_min' => $min, 'salaire_max' => $max,
                'date_publication' => '2026-10-01', 'date_expiration' => '2026-12-31', 'statut' => $statut,
            ]);
        }

        $candidats = [
            ['Andrianina', 'Toky', 'toky.candidat@madatech.test', '0341000001', 'Développeur web'],
            ['Ramanantsoa', 'Lova', 'lova.candidat@madatech.test', '0341000002', 'Développeur web'],
            ['Rakotomalala', 'Soa', 'soa.candidat@madatech.test', '0341000003', 'Technicien réseau'],
        ];
        foreach ($candidats as [$nom, $prenom, $email, $telephone, $profil]) {
            $this->put('candidats', ['email' => $email], [
                'nom' => $nom, 'prenom' => $prenom, 'email' => $email, 'telephone' => $telephone,
                'adresse' => 'Antananarivo', 'date_naissance' => '2000-01-15', 'sexe' => null,
                'ville' => 'Antananarivo', 'pays' => 'Madagascar', 'profil' => $profil,
                'statut' => 'actif', 'commentaire' => 'Candidat fictif.',
            ]);
        }

        $applications = [
            ['toky.candidat@madatech.test', 'Développeur web junior', 'nouvelle'],
            ['lova.candidat@madatech.test', 'Développeur web junior', 'en_cours'],
            ['soa.candidat@madatech.test', 'Technicien réseau', 'nouvelle'],
        ];
        foreach ($applications as [$email, $titre, $statut]) {
            $keys = [
                'candidat_id' => $this->id('candidats', ['email' => $email]),
                'offre_emploi_id' => $this->id('offres_emploi', ['titre' => $titre]),
            ];
            $this->put('candidatures', $keys, [
                ...$keys, 'date_candidature' => '2026-10-05', 'statut' => $statut,
                'commentaire' => 'Candidature fictive de test.',
            ]);
        }

        foreach ([
            ['toky.candidat@madatech.test', 'Développeur web junior', 'CV', 'cv-toky.pdf'],
            ['toky.candidat@madatech.test', 'Développeur web junior', 'Lettre de motivation', 'lettre-toky.pdf'],
            ['lova.candidat@madatech.test', 'Développeur web junior', 'CV', 'cv-lova.pdf'],
            ['soa.candidat@madatech.test', 'Technicien réseau', 'Diplôme', 'diplome-soa.pdf'],
        ] as [$email, $titre, $type, $nomFichier]) {
            $candId = $this->id('candidats', ['email' => $email]);
            $offreId = $this->id('offres_emploi', ['titre' => $titre]);
            $candidatureId = $this->id('candidatures', ['candidat_id' => $candId, 'offre_emploi_id' => $offreId]);
            $this->put('documents_candidatures', [
                'candidature_id' => $candidatureId, 'type_document' => $type, 'nom_fichier' => $nomFichier,
            ], [
                'candidature_id' => $candidatureId, 'type_document' => $type, 'nom_fichier' => $nomFichier,
                'chemin' => 'documents/candidatures/test/' . $nomFichier, 'extension' => 'pdf', 'taille' => 2048,
            ]);
        }

        $entretiens = [
            ['toky.candidat@madatech.test', 'Développeur web junior', '2026-10-20', '09:00:00', 'technique', 'rh@madatech.test', 4],
            ['lova.candidat@madatech.test', 'Développeur web junior', '2026-10-22', '10:00:00', 'technique', 'recruteur@madatech.test', 3],
            ['soa.candidat@madatech.test', 'Technicien réseau', '2026-10-24', '14:00:00', 'technique', 'recruteur@madatech.test', 5],
        ];
        foreach ($entretiens as [$email, $titre, $date, $heure, $type, $evaluateurEmail, $note]) {
            $candId = $this->id('candidats', ['email' => $email]);
            $offreId = $this->id('offres_emploi', ['titre' => $titre]);
            $candidatureId = $this->id('candidatures', ['candidat_id' => $candId, 'offre_emploi_id' => $offreId]);
            $keys = ['candidature_id' => $candidatureId, 'date_entretien' => $date];
            $entretien = $this->put('entretiens', $keys, [
                ...$keys, 'evaluateur_id' => $this->id('users', ['email' => $evaluateurEmail]),
                'heure_entretien' => $heure, 'type_entretien' => $type, 'lieu' => 'Bureau RH - Antananarivo',
                'statut' => 'planifie', 'note' => $note, 'commentaire' => 'Entretien fictif de test.',
            ]);
            $this->put('evaluations_candidats', [
                'candidature_id' => $candidatureId, 'entretien_id' => $entretien->id,
            ], [
                'candidature_id' => $candidatureId, 'entretien_id' => $entretien->id,
                'evaluateur_id' => $this->id('users', ['email' => $evaluateurEmail]),
                'competences_techniques' => $note, 'communication' => $note,
                'motivation' => $note, 'experience' => $note, 'note_globale' => $note,
                'recommandation' => $note >= 4 ? 'favorable' : 'à approfondir',
                'commentaire' => 'Évaluation fictive de candidat.',
            ]);
        }
    }

    private function seedSuivi(): void
    {
        foreach ([
            ['admin@madatech.test', 'Rapport des effectifs', 'effectifs'],
            ['rh@madatech.test', 'Rapport des congés', 'conges'],
            ['recruteur@madatech.test', 'Rapport de recrutement', 'recrutement'],
        ] as [$email, $titre, $type]) {
            $this->put('rapports', ['titre' => $titre], [
                'user_id' => $this->id('users', ['email' => $email]),
                'titre' => $titre, 'type_rapport' => $type,
                'periode_debut' => '2026-09-01', 'periode_fin' => '2026-09-30',
                'format' => 'pdf', 'chemin' => null,
                'description' => 'Rapport fictif de démonstration.', 'statut' => 'genere',
            ]);
        }

        foreach ([
            ['rh@madatech.test', 'Nouvelle demande de congé', 'Une demande de congé nécessite une vérification.', 'conge'],
            ['mialy@madatech.test', 'Décision de congé', 'Le statut de votre demande de congé a été mis à jour.', 'conge'],
            ['recruteur@madatech.test', 'Nouvelle candidature', 'Une candidature a été reçue pour une offre.', 'recrutement'],
        ] as [$email, $titre, $message, $type]) {
            $this->put('notifications', ['user_id' => $this->id('users', ['email' => $email]), 'titre' => $titre], [
                'user_id' => $this->id('users', ['email' => $email]), 'titre' => $titre,
                'message' => $message, 'type' => $type, 'lue' => false,
                'url' => null,
            ]);
        }

        foreach ([
            ['admin@madatech.test', 'creation', 'departements', 'Création de données de test pour le département.'],
            ['rh@madatech.test', 'validation', 'demandes_conges', 'Action simulée sur une demande de congé.'],
            ['recruteur@madatech.test', 'consultation', 'candidatures', 'Consultation simulée d’une candidature.'],
        ] as [$email, $action, $module, $description]) {
            $this->put('historiques_actions', [
                'user_id' => $this->id('users', ['email' => $email]),
                'action' => $action, 'module' => $module, 'description' => $description,
            ], [
                'user_id' => $this->id('users', ['email' => $email]),
                'action' => $action, 'module' => $module, 'description' => $description,
                'type_cible' => null, 'cible_id' => null,
                'nouvelle_valeur' => json_encode(['source' => 'seeder_test']),
                'adresse_ip' => '127.0.0.1', 'user_agent' => 'Seeder SGRH',
            ]);
        }

        $this->put('sanctions', [
            'employe_id' => $this->id('employes', ['matricule' => 'MAT-004']),
            'type_sanction' => 'avertissement',
            'date_sanction' => '2026-08-10',
        ], [
            'employe_id' => $this->id('employes', ['matricule' => 'MAT-004']),
            'user_id' => $this->id('users', ['email' => 'rh@madatech.test']),
            'type_sanction' => 'avertissement',
            'motif' => 'Retards répétés (exemple fictif uniquement).',
            'date_sanction' => '2026-08-10',
            'date_debut' => null, 'date_fin' => null,
            'description' => 'Donnée strictement fictive destinée aux tests.',
            'decision' => 'Avertissement écrit fictif.',
            'statut' => 'active',
        ]);
    }
}
