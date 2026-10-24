<?php

namespace App\Http\Controllers\Gestion;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\HistoriqueAction;
use App\Models\QrCode;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * QrCodeController
 * ----------------
 * Génération, révocation et affichage des QR codes employés.
 *
 * ⭐ Le QR code contient simplement le MATRICULE de l'employé (ex: EMP-001)
 */
class QrCodeController extends Controller
{
    protected QrCodeService $service;

    public function __construct(QrCodeService $service)
    {
        $this->service = $service;
    }

    // =================================================================
    // 1. GÉNÉRER — Crée un nouveau QR code
    // =================================================================

    /**
     * Générer ou renouveler le QR code d'un employé.
     */
    public function generer(Request $request, Employe $employe)
    {
        try {
            $renouveler = $request->boolean('renouveler');

            $qrCode = $this->service->genererPourEmploye($employe, $renouveler);

            // Journal d'audit
            HistoriqueAction::create([
                'user_id'         => Auth::id(),
                'action'          => 'creation',
                'module'          => 'qr_codes',
                'description'     => "QR code généré pour {$employe->nom_complet} ({$employe->matricule})",
                'nouvelle_valeur' => json_encode(['code' => $qrCode->code]),
            ]);

            return redirect()
                ->route('gestion.employes.qr.show', $employe)
                ->with('success', "QR code généré avec succès (matricule : {$qrCode->code}).");

        } catch (Throwable $e) {
            Log::error('Erreur génération QR code', [
                'employe_id' => $employe->id,
                'message'    => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    // =================================================================
    // 2. RÉVOQUER
    // =================================================================

    /**
     * Révoquer le QR code actif d'un employé.
     */
    public function revoquer(Request $request, Employe $employe)
    {
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        if (!$qrCode) {
            return redirect()
                ->back()
                ->with('error', 'Aucun QR code actif à révoquer pour cet employé.');
        }

        try {
            $raison = $request->input('raison', 'Révocation manuelle');
            $this->service->revoquer($qrCode, $raison);

            HistoriqueAction::create([
                'user_id'     => Auth::id(),
                'action'      => 'suppression',
                'module'      => 'qr_codes',
                'description' => "QR code révoqué pour {$employe->nom_complet} ({$qrCode->code})",
            ]);

            return redirect()
                ->route('gestion.employes.show', $employe)
                ->with('success', 'QR code révoqué avec succès.');

        } catch (Throwable $e) {
            Log::error('Erreur révocation QR code', [
                'employe_id' => $employe->id,
                'message'    => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    // =================================================================
    // 3. SHOW — Affiche la page HTML du QR code
    // =================================================================

    /**
     * Affiche la page HTML du QR code.
     * Le QR contient le MATRICULE de l'employé (ex: EMP-001).
     */
    public function show(Employe $employe)
    {
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        $qrData = null;

        if ($qrCode) {
            // ⭐ Contenu du QR = MATRICULE de l'employé (ex: EMP-001)
            $qrContent = $employe->matricule;

            $qrData = [
                'code'       => $qrCode->code,
                'image'      => 'https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=' . urlencode($qrContent),
                'content'    => $qrContent,
                'genere_le'  => $qrCode->genere_le?->format('d/m/Y H:i'),
                'scan_count' => $qrCode->scan_count ?? 0,
            ];
        }

        return view('gestion.employes.qr-show', compact('employe', 'qrData', 'qrCode'));
    }

    // =================================================================
    // 4. IMAGE — Retourne le JSON du QR (API)
    // =================================================================

    /**
     * Retourne les infos du QR code en JSON.
     */
    public function image(Employe $employe)
    {
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        if (!$qrCode) {
            return response()->json([
                'message' => 'Aucun QR code actif pour cet employé.'
            ], 404);
        }

        // ⭐ Contenu = matricule
        $qrContent = $employe->matricule;

        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
                 . urlencode($qrContent);

        return response()->json([
            'code'       => $qrCode->code,
            'content'    => $qrContent,
            'image'      => $qrUrl,
            'genere_le'  => $qrCode->genere_le?->format('d/m/Y H:i'),
            'scan_count' => $qrCode->scan_count,
        ]);
    }

    // =================================================================
    // 5. IMPRIMER — Page imprimable
    // =================================================================

    /**
     * Affiche une page imprimable avec le QR code.
     */
    public function imprimer(Employe $employe)
    {
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        if (!$qrCode) {
            return redirect()
                ->route('gestion.employes.qr.show', $employe)
                ->with('error', "Aucun QR code actif. Générez d'abord un QR code avant d'imprimer.");
        }

        return view('gestion.employes.print-qr', compact('employe', 'qrCode'));
    }

    // =================================================================
    // 6. DOWNLOAD — Télécharger le QR en PNG
    // =================================================================

    /**
     * Télécharge l'image du QR code au format PNG.
     */
    public function downloadQr(Employe $employe)
    {
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        if (!$qrCode) {
            return redirect()
                ->route('gestion.employes.qr.show', $employe)
                ->with('error', 'Aucun QR code actif à télécharger.');
        }

        // ⭐ Contenu = matricule
        $qrContent = $employe->matricule;

        // URL de l'API QR Server (1000x1000 pour haute résolution)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data='
                 . urlencode($qrContent);

        // Récupérer l'image
        try {
            $imageContent = @file_get_contents($qrUrl);

            if ($imageContent === false) {
                throw new \Exception("Impossible de récupérer l'image depuis l'API.");
            }
        } catch (\Exception $e) {
            Log::error('Erreur téléchargement QR', [
                'employe_id' => $employe->id,
                'message'    => $e->getMessage(),
            ]);

            return redirect()
                ->route('gestion.employes.qr.show', $employe)
                ->with('error', "Impossible de télécharger le QR code : " . $e->getMessage());
        }

        // Nom du fichier
        $nomFichier = 'qr-' . $employe->matricule . '.png';

        return response($imageContent, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $nomFichier . '"',
            'Content-Length'      => strlen($imageContent),
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
}