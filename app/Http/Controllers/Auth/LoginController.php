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
     * Route : GET /login
     * Accessible aux invités uniquement (middleware guest)
     */
    public function showLoginForm()
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers son dashboard
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role->nom);
        }

        return view('auth.login');
    }

    /**
     * =============================================
     * TRAITER LA CONNEXION
     * =============================================
     * Route : POST /login
     *
     * Accepte :
     * - Email OU matricule (détection automatique)
     * - Mot de passe
     * - Case "Se souvenir de moi"
     */
    public function login(Request $request)
    {
        // =============================================
        // 1. VALIDATION DES DONNÉES
        // =============================================
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string|min:6',
        ], [
            'email.required'    => 'L\'email ou le matricule est obligatoire.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins 6 caractères.',
        ]);

        // =============================================
        // 2. ANTI-BRUTE-FORCE (max 5 tentatives/minute)
        // =============================================
        $this->checkTooManyFailedAttempts($request);

        // =============================================
        // 3. DÉTECTER SI C'EST UN EMAIL OU UN MATRICULE
        // =============================================
        // filter_var vérifie si c'est un email valide
        // Sinon, on suppose que c'est un matricule
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'matricule';

        // =============================================
        // 4. PRÉPARER LES CREDENTIALS
        // =============================================
        $credentials = [
            $fieldType => $request->email, // 'email' ou 'matricule'
            'password' => $request->password,
        ];

        // =============================================
        // 5. VÉRIFIER QUE LE COMPTE EST ACTIF
        // =============================================
        // On ajoute 'statut' => true pour empêcher la connexion
        // d'un utilisateur désactivé
        $credentials['statut'] = true;

        // =============================================
        // 6. TENTATIVE DE CONNEXION
        // =============================================
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {

            // 6.1. Régénérer la session (protection contre Session Fixation)
            $request->session()->regenerate();

            // 6.2. Récupérer l'utilisateur connecté
            $user = Auth::user();

            // 6.3. Mettre à jour la date de dernière connexion
            $user->update([
                'last_login_at' => now(),
            ]);

            // 6.4. Réinitialiser le compteur de tentatives
            RateLimiter::clear($this->throttleKey($request));

            // 6.5. Rediriger selon le rôle
            return $this->redirectBasedOnRole($user->role->nom);
        }

        // =============================================
        // 7. ÉCHEC : INCRÉMENTER LE COMPTEUR
        // =============================================
        RateLimiter::hit($this->throttleKey($request), 60);

        // =============================================
        // 8. RETOUR AVEC ERREUR
        // =============================================
        throw ValidationException::withMessages([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * =============================================
     * REDIRECTION SELON LE RÔLE
     * =============================================
     * @param  string  $role  Le nom du rôle (admin, rh, chef, employe)
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'admin'   => redirect()->intended(route('admin.dashboard')),
            'rh'      => redirect()->intended(route('rh.dashboard')),
            'chef'    => redirect()->intended(route('chef.dashboard')),
            'employe' => redirect()->intended(route('employe.dashboard')),
            default   => redirect('/')->with('error', 'Rôle non reconnu.'),
        };
    }

    /**
     * =============================================
     * DÉCONNEXION
     * =============================================
     * Route : POST /logout
     */
    public function logout(Request $request)
    {
        // 1. Déconnecter l'utilisateur
        Auth::logout();

        // 2. Invalider la session en cours
        $request->session()->invalidate();

        // 3. Régénérer le token CSRF
        $request->session()->regenerateToken();

        // 4. Rediriger vers la page d'accueil avec message
        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * =============================================
     * ANTI-BRUTE-FORCE
     * =============================================
     * Limite à 5 tentatives échouées par minute.
     * Utilise le cache pour stocker le compteur.
     */
    protected function checkTooManyFailedAttempts(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Trop de tentatives de connexion. Veuillez réessayer dans {$seconds} secondes.",
            ]);
        }
    }

    /**
     * =============================================
     * CLÉ UNIQUE POUR LE RATE LIMITER
     * =============================================
     * Combine l'email/matricule + l'IP pour identifier
     * de manière unique chaque tentative.
     */
    protected function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }
}