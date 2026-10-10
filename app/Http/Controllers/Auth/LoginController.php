<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => ['required', 'string'], // Peut être email ou matricule
            'password' => ['required', 'string'],
        ]);

        // Logique pour permettre la connexion par Email OU Matricule
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'matricule';
        
        $credentials = [
            $fieldType => $request->email,
            'password' => $request->password,
        ];

        // Tentative de connexion
        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate(); // Sécurité contre la fixation de session

            // Redirection selon le rôle
            $role = Auth::user()->role->nom; // Assurez-vous que la relation 'role' existe dans le modèle User
            
            return match ($role) {
                'admin' => redirect()->intended('/admin/dashboard'),
                'rh' => redirect()->intended('/rh/dashboard'),
                'chef' => redirect()->intended('/chef/dashboard'),
                'employe' => redirect()->intended('/employee/dashboard'),
                default => redirect('/'),
            };
        }

        // Échec de connexion
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}