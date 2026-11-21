<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\BulletinPaie;
use Illuminate\Support\Facades\Auth;
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
            ->with(['employe', 'lignes'])
            ->firstOrFail();

        $mois = (int) $bulletin->periode;

        $periodeLabel = \Carbon\Carbon::create()
            ->month($mois)
            ->locale('fr')
            ->translatedFormat('F') . ' ' . $bulletin->annee;

        // Données du bulletin enregistré en base de données.
        $pdf = Pdf::loadView('employee.paie.pdf', [
            'bulletin' => $bulletin,
            'employe' => $employe,
            'periodeLabel' => $periodeLabel,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $nomFichier = 'bulletin-paie-' .
            $bulletin->annee . '-' .
            str_pad($bulletin->periode, 2, '0', STR_PAD_LEFT) .
            '.pdf';

        return $pdf->download($nomFichier);
    }
}
