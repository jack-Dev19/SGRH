<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * LoginController
 * ---------------
 * Gère l'authentification des utilisateurs du SGRH.
 *
 * - Affiche le formulaire de connexion.
 * - Authentifie via email OU matricule.
 * - Refuse la connexion si le compte est désactivé (statut = false).
 * - Déconnecte l'utilisateur.
 */
class LoginController extends Controller
{
    /**
     * Affiche la page de connexion.
     * Route : GET /login  (name: login)
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Traite la soumission du formulaire de connexion.
     * Route : POST /login
     */
    public function login(Request $request): RedirectResponse
    {
        // ---------------------------------------------------------
        // 1. Validation des entrées
        // ---------------------------------------------------------
        $request->validate([
            'email'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Veuillez renseigner votre email ou matricule.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // ---------------------------------------------------------
        // 2. Détection automatique : email ou matricule ?
        // ---------------------------------------------------------
        $identifiant = $request->input('email');

        $field = filter_var($identifiant, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'matricule';

        // ---------------------------------------------------------
        // 3. Credentials
        // ---------------------------------------------------------
        // On ajoute 'statut' => true pour bloquer les comptes désactivés.
        $credentials = [
            $field     => $identifiant,
            'password' => $request->input('password'),
            'statut'   => true,
        ];

        // ---------------------------------------------------------
        // 4. Tentative d'authentification
        // ---------------------------------------------------------
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Protection contre la fixation de session
            $request->session()->regenerate();

            // Redirection vers le dashboard
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Bienvenue ' . Auth::user()->name . ' !');
        }

        // ---------------------------------------------------------
        // 5. Échec
        // ---------------------------------------------------------
        throw ValidationException::withMessages([
            'email' => 'Identifiants incorrects ou compte désactivé.',
        ]);
    }

    /**
     * Déconnecte l'utilisateur.
     * Route : POST /logout  (name: logout)
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Vous êtes bien déconnecté.');
    }
}