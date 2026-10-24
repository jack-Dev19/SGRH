<?php

namespace App\Http\Controllers\Gestion;

use App\Helpers\RelationHelper;
use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserController extends Controller
{
    // =================================================================
    // INDEX
    // =================================================================

    public function index(Request $request): View
    {
        $query = User::with(['role', 'employe']);

        // RH ne voit pas les admins ni les autres RH
        if (auth()->user()->isRH()) {
            $query->whereHas('role', function ($q) {
                $q->whereNotIn('nom', ['Administrateur', 'Responsable RH']);
            });
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('gestion.users.index', compact('users'));
    }

    // =================================================================
    // CREATE
    // =================================================================

    public function create(): View
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $rolesAutorises = Role::whereIn('nom', [
                'Responsable RH',
                'Chef de département',
                'Employé',
            ])->get();
        } elseif ($user->isRH()) {
            $rolesAutorises = Role::whereIn('nom', [
                'Chef de département',
                'Employé',
            ])->get();
        } else {
            abort(403, 'Vous n\'êtes pas autorisé à créer des comptes.');
        }

        // Employés sans compte user
        $matriculesAvecCompte = User::whereNotNull('matricule')->pluck('matricule')->toArray();
        $employes = Employe::whereNotIn('matricule', $matriculesAvecCompte)
            ->orderBy('nom')
            ->get();

        return view('gestion.users.create', compact('rolesAutorises', 'employes'));
    }

    // =================================================================
    // STORE
    // =================================================================

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role_id'   => ['required', 'exists:roles,id'],
            'matricule' => ['nullable', 'string', 'exists:employes,matricule'],
            'statut'    => ['nullable', 'boolean'],
        ], [
            'name.required'      => 'Le nom complet est obligatoire.',
            'email.required'     => 'L\'email est obligatoire.',
            'email.email'        => 'L\'email doit être valide.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'role_id.required'   => 'Veuillez sélectionner un rôle.',
        ]);

        // Vérifier le rôle autorisé
        $role = Role::findOrFail($validated['role_id']);
        $userCreateur = auth()->user();

        if ($userCreateur->isRH() && in_array($role->nom, ['Administrateur', 'Responsable RH'])) {
            abort(403, 'Vous ne pouvez pas créer ce type de compte.');
        }

        if (!$userCreateur->isAdmin() && !$userCreateur->isRH()) {
            abort(403, 'Accès non autorisé.');
        }

        // Si rôle Employé ou Chef → matricule obligatoire
        if (in_array($role->nom, ['Employé', 'Chef de département']) && empty($validated['matricule'])) {
            return back()
                ->withInput()
                ->withErrors(['matricule' => 'Veuillez sélectionner un employé pour ce rôle.']);
        }

        try {
            // ⭐ Créer l'utilisateur
            $user = User::create([
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => Hash::make($validated['password']),
                'role_id'   => $validated['role_id'],
                'matricule' => $validated['matricule'] ?? null,
                'statut'    => $validated['statut'] ?? 1,
            ]);

            // ⭐ Lier l'employé au user
            if (!empty($validated['matricule'])) {
                $employe = Employe::where('matricule', $validated['matricule'])->first();
                if ($employe) {
                    $employe->update(['user_id' => $user->id]);
                }
            }

            Log::info('Utilisateur créé', [
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $role->nom,
                'created_by' => $userCreateur->name,
            ]);

            return redirect()
                ->route('gestion.users.index')
                ->with('success', "Utilisateur {$user->name} créé avec succès (rôle : {$role->nom}).");

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Création de l'utilisateur {$validated['name']}",
                "Impossible de créer l'utilisateur. Veuillez vérifier les données."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // SHOW
    // =================================================================

    public function show(string $id): View
    {
        $user = User::with(['role', 'employe'])->findOrFail($id);

        if (auth()->user()->isRH() && in_array($user->role?->nom, ['Administrateur', 'Responsable RH'])) {
            abort(403);
        }

        return view('gestion.users.show', compact('user'));
    }

    // =================================================================
    // EDIT
    // =================================================================

    public function edit(string $id): View
    {
        $user = User::findOrFail($id);
        $userCreateur = auth()->user();

        if ($userCreateur->isRH() && in_array($user->role?->nom, ['Administrateur', 'Responsable RH'])) {
            abort(403);
        }

        if ($userCreateur->isAdmin()) {
            $rolesAutorises = Role::whereIn('nom', [
                'Responsable RH',
                'Chef de département',
                'Employé',
            ])->get();
        } else {
            $rolesAutorises = Role::whereIn('nom', [
                'Chef de département',
                'Employé',
            ])->get();
        }

        $employes = Employe::orderBy('nom')->get();

        return view('gestion.users.edit', compact('user', 'rolesAutorises', 'employes'));
    }

    // =================================================================
    // UPDATE
    // =================================================================

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $userCreateur = auth()->user();

        if ($userCreateur->isRH() && in_array($user->role?->nom, ['Administrateur', 'Responsable RH'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id'   => ['required', 'exists:roles,id'],
            'matricule' => ['nullable', 'string', 'exists:employes,matricule'],
            'statut'    => ['nullable', 'boolean'],
        ]);

        $role = Role::findOrFail($validated['role_id']);
        if ($userCreateur->isRH() && in_array($role->nom, ['Administrateur', 'Responsable RH'])) {
            abort(403);
        }

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role_id'   => $validated['role_id'],
            'matricule' => $validated['matricule'] ?? null,
            'statut'    => $validated['statut'] ?? 1,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        try {
            $user->update($data);

            // ⭐ Mettre à jour le lien user_id
            if (!empty($validated['matricule'])) {
                Employe::where('matricule', $validated['matricule'])
                    ->update(['user_id' => $user->id]);
            }

            return redirect()
                ->route('gestion.users.index')
                ->with('success', 'Utilisateur modifié avec succès.');

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Modification de l'utilisateur {$user->name}",
                "Impossible de modifier l'utilisateur. Veuillez vérifier les données."
            );

            return back()
                ->withInput()
                ->withErrors(['error' => $message]);
        }
    }

    // =================================================================
    // DESTROY — ⭐ Vérification complète + logs
    // =================================================================

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $userCreateur = auth()->user();

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 1 : Vérifier cas spéciaux
        // ═══════════════════════════════════════════════════════════════
        if ($user->id === $userCreateur->id) {
            return back()->with('error_delete', [
                'titre'   => 'Action impossible',
                'message' => "Vous ne pouvez pas supprimer <strong>votre propre compte</strong>. Contactez un autre administrateur.",
                'icone'   => 'error',
            ]);
        }

        if ($userCreateur->isRH() && in_array($user->role?->nom, ['Administrateur', 'Responsable RH'])) {
            abort(403);
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 2 : Vérifier les dépendances
        // ═══════════════════════════════════════════════════════════════
        $dependances = [];

        // Historique d'actions
        if (\Schema::hasTable('historique_actions')) {
            $historiquesCount = \DB::table('historique_actions')
                ->where('user_id', $user->id)
                ->count();
            if ($historiquesCount > 0) {
                $dependances[] = "{$historiquesCount} action(s) dans l'historique";
            }
        }

        // Congés validés
        if (\Schema::hasTable('demande_conges')) {
            $congesValidesCount = \DB::table('demande_conges')
                ->where('valide_par', $user->id)
                ->count();
            if ($congesValidesCount > 0) {
                $dependances[] = "{$congesValidesCount} congé(s) validé(s)";
            }
        }

        // Absences validées
        if (\Schema::hasTable('absences')) {
            $absencesValideesCount = \DB::table('absences')
                ->where('valide_par', $user->id)
                ->count();
            if ($absencesValideesCount > 0) {
                $dependances[] = "{$absencesValideesCount} absence(s) validée(s)";
            }
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 3 : Bloquer si dépendances
        // ═══════════════════════════════════════════════════════════════
        if (!empty($dependances)) {
            $liste = implode(', ', $dependances);

            return redirect()
                ->route('gestion.users.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => "L'utilisateur <strong>{$user->name}</strong> ne peut pas être supprimé car il possède des enregistrements associés : <strong>{$liste}</strong>. Veuillez d'abord désactiver ce compte au lieu de le supprimer.",
                    'icone'   => 'error',
                ]);
        }

        // ═══════════════════════════════════════════════════════════════
        // ⭐ ÉTAPE 4 : Suppression
        // ═══════════════════════════════════════════════════════════════
        try {
            // Détacher l'employé lié
            if ($user->matricule) {
                Employe::where('matricule', $user->matricule)
                    ->update(['user_id' => null]);
            }

            $nomUser = $user->name;
            $user->delete();

            Log::info('Utilisateur supprimé', [
                'name'       => $nomUser,
                'deleted_by' => $userCreateur->name,
            ]);

            return redirect()
                ->route('gestion.users.index')
                ->with('success', "Utilisateur {$nomUser} supprimé avec succès.");

        } catch (QueryException $e) {
            $message = RelationHelper::logAndReturnMessage(
                $e,
                "Suppression de l'utilisateur {$user->name}",
                "Impossible de supprimer cet utilisateur car il est lié à d'autres données. Veuillez le désactiver à la place."
            );

            return redirect()
                ->route('gestion.users.index')
                ->with('error_delete', [
                    'titre'   => 'Suppression impossible',
                    'message' => $message,
                    'icone'   => 'error',
                ]);
        }
    }
}