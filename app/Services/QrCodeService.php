<?php

namespace App\Services;

use App\Models\Employe;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * QrCodeService
 * -------------
 * Gère la génération, la révocation et la validation des QR codes employés.
 *
 * ⭐ Le code du QR = le matricule de l'employé (ex: EMP-001)
 */
class QrCodeService
{
    /**
     * Génère un QR code pour un employé.
     * Le QR contient simplement son matricule (ex: EMP-001).
     */
    public function genererPourEmploye(Employe $employe, bool $renouveler = false): QrCode
    {
        if ($employe->statut !== 'actif') {
            throw new \Exception("Impossible de générer un QR code : l'employé est inactif.");
        }

        // ⭐ 1. Chercher TOUS les QR codes de cet employé (y compris révoqués)
        $qrActif = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        if ($qrActif && !$renouveler) {
            throw new \Exception("Un QR code est déjà actif pour cet employé. Utilisez « Régénérer » pour le renouveler.");
        }

        // ⭐ 2. Le code = matricule
        $matricule = $employe->matricule;
        $code      = $matricule;
        $token     = $matricule;
        $contenuQr = $matricule;

        return DB::transaction(function () use ($employe, $qrActif, $code, $token, $contenuQr, $renouveler) {

            // ⭐ 3. Révoquer l'ancien QR actif si renouvellement
            if ($qrActif && $renouveler) {
                $qrActif->update([
                    'actif'             => false,
                    'revoque_le'        => now(),
                    'revoque_par'       => Auth::id(),
                    'raison_revocation' => 'Renouvellement volontaire',
                ]);
            }

            // ⭐ 4. Chercher s'il existe DÉJÀ un QR avec ce code (même révoqué)
            $qrExistant = QrCode::where('code', $code)->first();

            if ($qrExistant) {
                // Réutiliser l'enregistrement existant
                $qrExistant->update([
                    'employe_id'        => $employe->id,
                    'token'             => $token,
                    'contenu_qr'        => $contenuQr,
                    'actif'             => true,
                    'genere_par'        => Auth::id(),
                    'genere_le'         => now(),
                    'revoque_le'        => null,
                    'revoque_par'       => null,
                    'raison_revocation' => null,
                ]);

                Log::info("QR code réactivé", ['code' => $code, 'employe' => $employe->id]);

                return $qrExistant->fresh();
            }

            // ⭐ 5. Sinon, créer un nouveau QR
            return QrCode::create([
                'employe_id' => $employe->id,
                'code'       => $code,
                'token'      => $token,
                'contenu_qr' => $contenuQr,
                'actif'      => true,
                'genere_par' => Auth::id(),
                'genere_le'  => now(),
            ]);
        });
    }

    /**
     * Révoque un QR code.
     */
    public function revoquer(QrCode $qrCode, string $raison = 'Révocation manuelle'): void
    {
        if (!$qrCode->actif) {
            throw new \Exception("Ce QR code est déjà révoqué.");
        }

        $qrCode->update([
            'actif'             => false,
            'revoque_le'        => now(),
            'revoque_par'       => Auth::id(),
            'raison_revocation' => $raison,
        ]);
    }

    /**
     * Valide un QR code à partir de son contenu.
     */
    public function validerParToken(string $contenu): ?QrCode
    {
        $contenu = trim($contenu);

        if (empty($contenu)) {
            return null;
        }

        // ⭐ Extraire le matricule
        $matricule = $this->extraireMatricule($contenu);

        if (!$matricule) {
            return null;
        }

        // ⭐ Chercher l'employé
        $employe = Employe::where('matricule', $matricule)->first();

        if (!$employe || $employe->statut !== 'actif') {
            return null;
        }

        // ⭐ Chercher le QR code
        $qrCode = QrCode::where('employe_id', $employe->id)
            ->where('actif', true)
            ->first();

        // Si aucun QR n'existe, on en crée un à la volée
        if (!$qrCode) {
            $qrCode = QrCode::updateOrCreate(
                ['code' => $employe->matricule],
                [
                    'employe_id' => $employe->id,
                    'token'      => $employe->matricule,
                    'contenu_qr' => $employe->matricule,
                    'actif'      => true,
                    'genere_par' => Auth::id(),
                    'genere_le'  => now(),
                ]
            );
        }

        return $qrCode;
    }

    // =================================================================
    // HELPERS
    // =================================================================

    /**
     * Extrait le matricule depuis n'importe quel input.
     */
    private function extraireMatricule(string $input): ?string
    {
        // Cas 1 : URL avec /qr/verifier/
        if (str_contains($input, '/qr/verifier/')) {
            $parts = explode('/qr/verifier/', $input);
            return trim(end($parts), "/ \t\n\r\0\x0B");
        }

        // Cas 2 : URL avec ?data=
        if (str_contains($input, 'data=')) {
            if (preg_match('/data=([^&]+)/', $input, $m)) {
                return urldecode($m[1]);
            }
        }

        // Cas 3 : Matricule pur (EMP-001)
        $trimmed = trim($input);
        if (preg_match('/^[A-Z]{2,5}-[0-9]{2,6}$/i', $trimmed)) {
            return strtoupper($trimmed);
        }

        return $trimmed;
    }
}