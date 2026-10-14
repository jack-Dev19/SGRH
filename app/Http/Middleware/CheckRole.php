<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 * --------------------
 * Vérifie que l'utilisateur connecté possède l'un des rôles autorisés.
 *
 * Utilisation dans les routes :
 *   ->middleware('role:Administrateur')
 *   ->middleware('role:Administrateur,Responsable RH')
 *
 * Si l'utilisateur n'a pas le bon rôle → réponse 403 (Accès refusé).
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Utilisateur non connecté
        if (!$user) {
            return redirect()->route('login');
        }

        // 2. Utilisateur sans rôle (cas anormal)
        if (!$user->role) {
            abort(403, 'Aucun rôle assigné à votre compte.');
        }

        // 3. Vérifier si le rôle de l'utilisateur est dans la liste autorisée
        if (!in_array($user->role->nom, $roles, true)) {
            abort(403, "Accès refusé : cette section est réservée aux rôles suivants : " . implode(', ', $roles));
        }

        return $next($request);
    }
}