<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Vérifie si le rôle correspond
        // On suppose que le modèle User a une relation 'role'
        if (Auth::user()->role->nom !== $role) {
            abort(403, 'Accès non autorisé pour ce rôle.');
        }

        return $next($request);
    }
}