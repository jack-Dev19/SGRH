<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Vérifie que l'utilisateur connecté a le bon rôle.
     *
     * @param  string  $role  Le rôle exigé (ex: 'admin', 'rh', 'chef', 'employe')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Vérifier que l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Vérifier que l'utilisateur a le bon rôle
        $userRole = Auth::user()->role->nom ?? null;

        if ($userRole !== $role) {
            // 3. Accès refusé → 403
            abort(403, 'Accès non autorisé pour ce rôle.');
        }

        return $next($request);
    }
}