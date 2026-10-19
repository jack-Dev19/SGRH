<?php

namespace App\Http\Controllers\Gestion;

use App\Helpers\RelationHelper;
use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Models\Employe;
use App\Models\Pointage;
use App\Models\Poste;
use App\Models\QrCode;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EmployeController extends Controller
{
    // =================================================================
    // INDEX — Liste des employés
    // =================================================================

    public function index(Request $request): View
    {
        $query = Employe::with(['departement', 'poste']);

        // Recherche
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtre département
        if ($departementId = $request->input('departement')) {
            $query->where('departement_id', $departementId);
        }

        // Filtre statut
        if ($statut = $request->input('statut')) {
            $query->where('statut', $statut);
        }

        $employes     = $query->orderBy('nom')->paginate(12)->withQueryString();
        $departements = Departement::orderBy('nom')->get();

        return view('gestion.employes.index', compact('employes', 'departements'));
    }

    // =================================================================
    // SHOW — Détails d'un employé
    // =================================================================

    public function show(string $id): View
    {
        $employe = Employe::with(['departement', 'poste'])->findOrFail($id);

        // QR code actif
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        // Stats de pointages du mois
        $statsPointages = [
            'total_mois'   => Pointage::where('employe_id', $employe->id)
                ->whereMonth('date_heure', now()->month)
                ->whereYear('date_heure', now()->year)
                ->count(),
            'retards_mois' => Pointage::where('employe_id', $employe->id)
                ->whereMonth('date_heure', now()->month)
                ->whereYear('date_heure', now()->year)
                ->where('est_anomalie', true)
                ->count(),
        ];

        return view('gestion.employes.show', compact('employe', 'qrCode', 'statsPointages'));
    }

    // =================================================================
    // CREATE — Formulaire de création
    // =================================================================

    public function create(): View
    {
        $departements = Departement::orderBy('nom')->get();

        // ⭐ Uniquement les postes actifs (avec departement_id pour le JS)
        $postes = Poste::where('statut', 1)
            ->orderBy('nom')
            ->get(['id', 'nom', 'departement_id']);

        return view('gestion.employes.create', compact('departements', 'postes'));
    }

    // =================================================================
    // STORE — Enregistrement avec validation stricte
    // =================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom'             => ['required', 'string', 'max:100'],
            'prenom'          => ['required', 'string', 'max:100'],
            'email'           => ['nullable', 'email', 'unique:employes,email'],
            'telephone'       => ['nullable', 'string', 'max:20'],
            'adresse'         => ['nullable', 'string'],
            'date_naissance'  => ['nullable', 'date'],
            'date_embauche'   => ['nullable', 'date'],
            'departement_id'  => ['required', 'exists:departements,id'],
            'poste_id'        => ['required', 'exists:postes,id'],
            'statut'          => ['required', 'in:actif,inactif,sorti'],
        ], [
            'nom.required'            => 'Le nom est obligatoire.',
            'prenom.required'         => 'Le prénom est obligatoire.',
            'email.email'             => 'L\'email doit être valide.',
            'email.unique'            => 'Cet email est déjà utilisé.',
            'departement_id.required' => 'Veuillez sélectionner un département.',
            'poste_id.required'       => 'Veuillez sélectionner un poste.',
            'statut.required'         => 'Veuillez sélectionner un statut.',
        ]);

        // ⭐ VÉRIFICATION STRICTE : le poste doit appartenir au département
        $poste = Poste::find($validated['poste_id']);

        if (!$poste) {
            return back()
                ->withInput()
                ->withErrors(['poste_id' => 'Le poste sélectionné n\'existe pas.']);
        }

        if ($poste->departement_id !== $validated['departement_id']) {
            return back()
                ->withInput()
                ->withErrors(['poste_id' => 'Le poste sélectionné n\'appartient pas au département choisi.']);
        }

        if (!$poste->isActif()) {
            return back()
                ->withInput()
                ->withErrors(['poste_id' => 'Ce poste est désactivé et ne peut plus être attribué.']);
        }

        // ⭐ Générer le matricule automatiquement
        $validated['matricule'] = $this->genererMatricule();

        try {
            Employe::create($validated);

            return redirect()
                ->route('gestion.employes.index')
                ->with('success', "Employé {$validated['prenom']} {$validated['nom']} créé avec succès (matricule : {$validated['matricule']}).");

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Création de l'employé {$validated['prenom']} {$validated['nom']}",
                "Impossible de créer l'employé. Veuillez vérifier les données et réessayer."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // EDIT — Formulaire de modification
    // =================================================================

    public function edit(string $id): View
    {
        $employe      = Employe::findOrFail($id);
        $departements = Departement::orderBy('nom')->get();

        // ⭐ Postes actifs + celui attribué à cet employé (même inactif)
        $postes = Poste::where(function ($q) use ($employe) {
                $q->where('statut', 1)
                  ->orWhere('id', $employe->poste_id);
            })
            ->orderBy('nom')
            ->get(['id', 'nom', 'departement_id', 'statut']);

        return view('gestion.employes.edit', compact('employe', 'departements', 'postes'));
    }

    // =================================================================
    // UPDATE — Mise à jour avec validation stricte
    // =================================================================

    public function update(Request $request, string $id)
    {
        $employe = Employe::findOrFail($id);

        $validated = $request->validate([
            'nom'             => ['required', 'string', 'max:100'],
            'prenom'          => ['required', 'string', 'max:100'],
            'email'           => ['nullable', 'email', 'unique:employes,email,' . $employe->id],
            'telephone'       => ['nullable', 'string', 'max:20'],
            'adresse'         => ['nullable', 'string'],
            'date_naissance'  => ['nullable', 'date'],
            'date_embauche'   => ['nullable', 'date'],
            'departement_id'  => ['required', 'exists:departements,id'],
            'poste_id'        => ['required', 'exists:postes,id'],
            'statut'          => ['required', 'in:actif,inactif,sorti'],
        ]);

        // ⭐ VÉRIFICATION STRICTE : le poste doit appartenir au département
        $poste = Poste::find($validated['poste_id']);

        if (!$poste || $poste->departement_id !== $validated['departement_id']) {
            return back()
                ->withInput()
                ->withErrors(['poste_id' => 'Le poste sélectionné n\'appartient pas au département choisi.']);
        }

        try {
            $employe->update($validated);

            return redirect()
                ->route('gestion.employes.index')
                ->with('success', "Employé {$employe->nom_complet} modifié avec succès.");

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Modification de l'employé {$employe->nom_complet}",
                "Impossible de modifier l'employé. Veuillez vérifier les données."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // DESTROY — Suppression avec vérification COMPLÈTE des dépendances
    // =================================================================

    public function destroy(string $id)
    {
        $employe = Employe::findOrFail($id);

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 1 : Détecter TOUTES les dépendances
        // ═══════════════════════════════════════════════════════════════
        $dependances = [];

        // Pointages
        $pointagesCount = Pointage::where('employe_id', $employe->id)->count();
        if ($pointagesCount > 0) {
            $dependances[] = "{$pointagesCount} pointage(s)";
        }

        // Absences
        if (Schema::hasTable('absences')) {
            $absencesCount = DB::table('absences')->where('employe_id', $employe->id)->count();
            if ($absencesCount > 0) {
                $dependances[] = "{$absencesCount} absence(s)";
            }
        }

        // Présences
        if (Schema::hasTable('presences')) {
            $presencesCount = DB::table('presences')->where('employe_id', $employe->id)->count();
            if ($presencesCount > 0) {
                $dependances[] = "{$presencesCount} présence(s)";
            }
        }

        // Contrats
        if (Schema::hasTable('contrats')) {
            $contratsCount = DB::table('contrats')->where('employe_id', $employe->id)->count();
            if ($contratsCount > 0) {
                $dependances[] = "{$contratsCount} contrat(s)";
            }
        }

        // Documents
        if (Schema::hasTable('documents')) {
            $documentsCount = DB::table('documents')->where('employe_id', $employe->id)->count();
            if ($documentsCount > 0) {
                $dependances[] = "{$documentsCount} document(s)";
            }
        }

        // Demandes de congé
        if (Schema::hasTable('demande_conges')) {
            $congesCount = DB::table('demande_conges')->where('employe_id', $employe->id)->count();
            if ($congesCount > 0) {
                $dependances[] = "{$congesCount} demande(s) de congé";
            }
        }

        // Évaluations
        if (Schema::hasTable('evaluations')) {
            $evaluationsCount = DB::table('evaluations')->where('employe_id', $employe->id)->count();
            if ($evaluationsCount > 0) {
                $dependances[] = "{$evaluationsCount} évaluation(s)";
            }
        }

        // Salaires
        if (Schema::hasTable('salaires')) {
            $salairesCount = DB::table('salaires')->where('employe_id', $employe->id)->count();
            if ($salairesCount > 0) {
                $dependances[] = "{$salairesCount} salaire(s)";
            }
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 2 : Bloquer si dépendances
        // ═══════════════════════════════════════════════════════════════
        if (!empty($dependances)) {
            $liste = implode(', ', $dependances);

            return redirect()
                ->route('gestion.employes.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => "L'employé <strong>{$employe->nom_complet}</strong> ne peut pas être supprimé car il possède des enregistrements associés : <strong>{$liste}</strong>. Pour préserver l'historique, veuillez <strong>archiver</strong> ou <strong>désactiver</strong> cet employé plutôt que de le supprimer définitivement.",
                    'icone'   => 'error',
                ]);
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 3 : Suppression (avec gestion d'erreur SQL)
        // ═══════════════════════════════════════════════════════════════
        try {
            // Révoquer le QR code actif avant suppression
            QrCode::where('employe_id', $employe->id)
                ->where('actif', true)
                ->update([
                    'actif'             => false,
                    'revoque_le'        => now(),
                    'revoque_par'       => auth()->id(),
                    'raison_revocation' => 'Suppression de l\'employé',
                ]);

            $nomComplet = $employe->nom_complet;
            $employe->delete();

            return redirect()
                ->route('gestion.employes.index')
                ->with('success', "Employé {$nomComplet} supprimé avec succès.");

        } catch (QueryException $e) {
            // ⭐ Si une FK non prévue bloque la suppression
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Suppression de l'employé {$employe->nom_complet}",
                "Impossible de supprimer cet employé car d'autres données y sont encore associées. Veuillez le désactiver à la place."
            );

            return redirect()
                ->route('gestion.employes.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => $message,
                    'icone'   => 'error',
                ]);
        }
    }

    // =================================================================
    // HELPERS
    // =================================================================

    /**
     * Génère un matricule unique au format EMP-XXX.
     */
    private function genererMatricule(): string
    {
        $dernier = Employe::where('matricule', 'like', 'EMP-%')
            ->orderByDesc('matricule')
            ->first();

        if (!$dernier || !preg_match('/EMP-(\d+)/', $dernier->matricule, $m)) {
            return 'EMP-001';
        }

        return 'EMP-' . str_pad((int) $m[1] + 1, 3, '0', STR_PAD_LEFT);
    }
}