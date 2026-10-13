<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * =============================================
     * AFFICHER LE FORMULAIRE DE CONNEXION
     * =============================================
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * =============================================
     * TRAITER LA CONNEXION
     * =============================================
     * - Accepte email OU matricule
     * - Vérifie le mot de passe
     * - Redirige selon le rôle
     */
    public function login(Request $request)
    {
        // 1. Validation des données
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Anti-brute-force : Limiter les tentatives
        $this->checkTooManyFailedAttempts($request);

        // 3. Déterminer si l'entrée est un email ou un matricule
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'matricule';

        // 4. Préparer les credentials
        $credentials = [
            $fieldType  => $request->email,
            'password'  => $request->password,
        ];

        // 5. Ajouter une vérification du statut actif
        $credentials['statut'] = true;

        // 6. Tentative de connexion
        if (Auth::attempt($credentials, $request->has('remember'))) {

            // 6.1. Régénérer la session (sécurité)
            $request->session()->regenerate();

            // 6.2. Mettre à jour la date de dernière connexion
            $user = Auth::user();
            $user->update(['last_login_at' => now()]);

            // 6.3. Réinitialiser le compteur de tentatives
            RateLimiter::clear($this->throttleKey($request));

            // 6.4. Rediriger selon le rôle
            return $this->redirectBasedOnRole($user->role->nom);
        }

        // 7. Échec : Incrémenter le compteur
        RateLimiter::hit($this->throttleKey($request));

        // 8. Retour avec erreur
        throw ValidationException::withMessages([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * =============================================
     * REDIRECTION SELON LE RÔLE
     * =============================================
     */
    protected function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'admin'   => redirect()->intended(route('admin.dashboard')),
            'rh'      => redirect()->intended(route('rh.dashboard')),
            'chef'    => redirect()->intended(route('chef.dashboard')),
            'employe' => redirect()->intended(route('employe.dashboard')),
            default   => redirect('/'),
        };
    }

    /**
     * =============================================
     * DÉCONNEXION
     * =============================================
     */
    public function logout(Request $request)
    {
        // 1. Déconnecter l'utilisateur
        Auth::logout();

        // 2. Invalider la session
        $request->session()->invalidate();

        // 3. Régénérer le token CSRF
        $request->session()->regenerateToken();

        // 4. Rediriger vers l'accueil
        return redirect('/')->with('success', 'Vous êtes déconnecté.');
    }

    /**
     * =============================================
     * ANTI-BRUTE-FORCE
     * =============================================
     * Limite à 5 tentatives par minute.
     */
    protected function checkTooManyFailedAttempts(Request $request): void
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }
    }

    /**
     * Clé unique pour identifier l'utilisateur qui essaie de se connecter.
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}