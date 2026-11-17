<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoriqueAction;
use App\Models\JourFerie;
use App\Models\Role;
use App\Models\TypeConge;
use App\Models\User;
use App\Models\Parametre;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * ParametreController
 * -------------------
 * Gestion des paramètres système.
 * Réservé au rôle Administrateur.
 */
class ParametreController extends Controller
{
    
    /**
     * Page d'accueil du centre des paramètres.
     */
    public function index(): View
    {
        // Statistiques existantes
        $stats = [
            'utilisateurs' => User::count(),
            'roles'        => Role::count(),
            'types_conges' => TypeConge::count(),
            'jours_feries' => JourFerie::count(),
            'actions_log'  => HistoriqueAction::count(),
        ];

        // Paramètres généraux enregistrés en base de données
        $parametresGeneraux = Parametre::where('groupe', 'general')
            ->pluck('valeur', 'cle')
            ->toArray();

        return view(
            'admin.parametres.index',
            compact('stats', 'parametresGeneraux')
        );
    }
    /**
     * Enregistrer les paramètres du système.
     */
    public function update(Request $request)
    {
        // 1. Validation des paramètres généraux et du logo
        $donnees = $request->validate([
            'nom_entreprise' => ['required', 'string', 'max:255'],
            'fuseau_horaire' => ['required', 'timezone'],
            'langue'         => ['required', 'in:fr,mg,en'],
            'devise'         => ['required', 'in:MGA,EUR,USD'],
            'format_date'    => ['required', 'in:d/m/Y,Y-m-d,m/d/Y'],

            // Logo facultatif : PNG, JPG ou WEBP, maximum 2 Mo
            'logo_entreprise' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],
        ]);

        // 2. Enregistrer les paramètres textuels
        foreach ([
            'nom_entreprise',
            'fuseau_horaire',
            'langue',
            'devise',
            'format_date',
        ] as $cle) {
            Parametre::definir('general', $cle, $donnees[$cle]);
        }

        // 3. Traiter le logo uniquement si un nouveau fichier est envoyé
        if ($request->hasFile('logo_entreprise')) {

            // Récupérer le chemin de l'ancien logo
            $ancienLogo = Parametre::obtenir(
                'general',
                'logo_entreprise'
            );

            // Stocker le nouveau logo dans storage/app/public/logos
            $nouveauLogo = $request->file('logo_entreprise')
                ->store('logos', 'public');

            // Enregistrer le chemin du nouveau logo en base de données
            Parametre::definir(
                'general',
                'logo_entreprise',
                $nouveauLogo
            );

            // Supprimer l'ancien fichier après l'enregistrement du nouveau
            if ($ancienLogo && Storage::disk('public')->exists($ancienLogo)) {
                Storage::disk('public')->delete($ancienLogo);
            }
        }

        // 4. Retourner vers la page des paramètres
        return redirect()
            ->route('admin.parametres.index')
            ->with(
                'success',
                'Les paramètres généraux ont été enregistrés avec succès.'
            );
    }



    /**
     * Liste des utilisateurs (comptes).
     */
    public function utilisateurs(Request $request): View
    {
        // Récupérer le terme recherché
        $recherche = $request->input('recherche');
    
        // Construire la liste des utilisateurs
        $utilisateurs = User::with('role')
            ->when($recherche, function ($query, $recherche) {
    
                // Rechercher par nom ou adresse e-mail
                $query->where(function ($q) use ($recherche) {
    
                    $q->where('name', 'LIKE', "%{$recherche}%")
                      ->orWhere('email', 'LIKE', "%{$recherche}%");
    
                });
    
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();
    
        return view(
            'admin.parametres.utilisateurs',
            compact('utilisateurs', 'recherche')
        );
    }

    /**
     * Liste des rôles.
     */
    public function roles(): View
    {
        $roles = Role::withCount('users')
            ->orderBy('nom')
            ->get();

        return view('admin.parametres.roles', compact('roles'));
    }

    /**
     * Liste des types de congés.
     */
    public function typesConges(): View
    {
        $types = TypeConge::orderBy('nom')->get();

        return view('admin.parametres.types-conges', compact('types'));
    }

    /**
     * Liste des jours fériés.
     */
    public function joursFeries(): View
    {
        $jours = JourFerie::orderBy('date')->get();

        return view('admin.parametres.jours-feries', compact('jours'));
    }

    /**
     * Historique des actions (audit log).
     */
    public function historique(): View
    {
        $actions = HistoriqueAction::with('user')
            ->orderByDesc('created_at')
            ->paginate(30);

        return view('admin.parametres.historique', compact('actions'));
    }
}