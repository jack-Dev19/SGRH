<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\BulletinPaie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PaieController extends Controller
{
    /**
     * Afficher les bulletins de paie de l'employé connecté.
     */
    public function index()
    {
        $employe = Employe::where('user_id', Auth::id())
            ->firstOrFail();

        $bulletins = BulletinPaie::where('employe_id', $employe->id)
            ->where('statut', 'publie')
            ->orderByDesc('annee')
            ->orderByDesc('periode')
            ->paginate(12);

        return view('employee.paie.index', compact('bulletins'));
    }

    /**
     * Télécharger un bulletin de paie en PDF.
     * Aucun contrôle de statut : tous les bulletins sont téléchargeables.
     */
    public function telecharger($bulletin)
    {
        $employe = Employe::where('user_id', Auth::id())
            ->firstOrFail();

        // Vérifier que le bulletin appartient à l'employé connecté.
        $bulletin = BulletinPaie::where('id', $bulletin)
            ->where('employe_id', $employe->id)
            ->where('statut', 'publie')
            ->with(['employe', 'lignes'])
            ->firstOrFail();

        $periodeLabel = $bulletin->periode . ' ' . $bulletin->annee;

        // Données du bulletin enregistré en base de données.
        $pdf = Pdf::loadView('employee.paie.pdf', [
            'bulletin' => $bulletin,
            'employe' => $employe,
            'periodeLabel' => $periodeLabel,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $nomFichier = 'bulletin-paie-' .
            $bulletin->annee . '-' .
            Str::slug($bulletin->periode) .
            '.pdf';

        return $pdf->download($nomFichier);
    }
}
