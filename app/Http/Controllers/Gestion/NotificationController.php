<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

/**
 * NotificationController
 * --------------------------------------------------
 * Gestion du centre de notifications de l'utilisateur.
 *
 * Fonctionnalités :
 * - Afficher ses notifications
 * - Marquer une notification comme lue
 * - Marquer toutes ses notifications comme lues
 *
 * Sécurité :
 * Chaque opération passe par l'utilisateur authentifié.
 * Un utilisateur ne peut pas modifier les notifications
 * d'un autre utilisateur.
 */
class NotificationController extends Controller
{
    /**
     * Afficher le centre de notifications.
     */
    public function index()
    {
        // Récupérer uniquement les notifications
        // de l'utilisateur connecté.
        $user = Auth::user();

        // Notifications paginées, les plus récentes en premier.
        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        // Nombre de notifications non lues.
        $nombreNonLues = $user->notifications()
            ->nonLues()
            ->count();

        // Afficher la vue du centre de notifications.
        return view('gestion.notifications.index', compact(
            'notifications',
            'nombreNonLues'
        ));
    }

    /**
     * Marquer une notification comme lue.
     */
    public function marquerCommeLue(string $id)
    {
        // Chercher la notification uniquement
        // parmi celles appartenant à l'utilisateur connecté.
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        // Marquer comme lue.
        $notification->marquerCommeLue();

        return redirect()
            ->back()
            ->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marquer toutes les notifications comme lues.
     */
    public function toutMarquerCommeLue()
    {
        // Modifier uniquement les notifications non lues
        // de l'utilisateur actuellement connecté.
        Auth::user()
            ->notifications()
            ->nonLues()
            ->update([
                'lue' => true,
                'date_lecture' => now(),
            ]);

        return redirect()
            ->back()
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
