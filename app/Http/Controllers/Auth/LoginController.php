<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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
        // 1. Validation des entrées
        $request->validate([
            'email'    => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Veuillez renseigner votre email ou matricule.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // 2. Récupération des paramètres de sécurité
        $tentativesMax = (int) Parametre::obtenir(
            'securite',
            'tentatives_max',
            5
        );

        $dureeVerrouillage = (int) Parametre::obtenir(
            'securite',
            'duree_verrouillage',
            15
        );

        // Protection contre les valeurs invalides
        $tentativesMax = max(1, min(20, $tentativesMax));
        $dureeVerrouillage = max(1, min(1440, $dureeVerrouillage));

        // 3. Détection email ou matricule
        $identifiant = Str::lower(trim($request->input('email')));

        $field = filter_var($identifiant, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'matricule';

        // 4. Clé unique : identifiant + adresse IP
        $cleLimiteur = Str::transliterate($identifiant)
            . '|' . $request->ip();

        $cleLimiteur = 'connexion:' . sha1($cleLimiteur);

        // Clé distincte pour le verrouillage temporaire
        $cleVerrouillage = $cleLimiteur . ':verrouillage';

        // Vérifier si l'utilisateur est actuellement verrouillé
        if (RateLimiter::tooManyAttempts($cleVerrouillage, 1)) {

            $secondesRestantes = RateLimiter::availableIn($cleVerrouillage);

            $minutesRestantes = max(
                1,
                (int) ceil($secondesRestantes / 60)
            );

            throw ValidationException::withMessages([
                'email' => "Votre connexion est temporairement bloquée. Réessayez dans {$minutesRestantes} minute(s).",
            ]);
        }

        // 6. Préparer les identifiants
        $credentials = [
            $field     => $identifiant,
            'password' => $request->input('password'),
            'statut'   => true,
        ];

        // 7. Tentative d'authentification
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // Réinitialiser le compteur après connexion réussie
            RateLimiter::clear($cleLimiteur);

            // Régénérer la session
            $request->session()->regenerate();

            return redirect()
                ->intended(route('dashboard'))
                ->with('success', 'Bienvenue ' . Auth::user()->name . ' !');
        }

        // 8. Enregistrer l'échec
        RateLimiter::hit($cleLimiteur, 86400);

        // 9. Déclencher le verrouillage lorsque le seuil est atteint
        if (RateLimiter::attempts($cleLimiteur) >= $tentativesMax) {
        
            // Effacer le compteur des échecs
            RateLimiter::clear($cleLimiteur);
        
            // Activer le verrouillage pendant la durée configurée
            RateLimiter::hit(
                $cleVerrouillage,
                $dureeVerrouillage * 60
            );
        
            throw ValidationException::withMessages([
                'email' => "Nombre maximal de tentatives atteint. Votre connexion est bloquée pendant {$dureeVerrouillage} minutes.",
            ]);
        }

        // 10. Signaler un échec classique
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