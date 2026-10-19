<?php

namespace App\Services;

use App\Models\Contrat;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfInstance;
use Illuminate\Support\Facades\Log;

/**
 * ContratPdfService
 * -----------------
 * Génère les PDF de contrats avec UN SEUL template universel
 * qui s'adapte automatiquement au type (CDI, CDD, Stage, Intérim, Freelance).
 */
class ContratPdfService
{
    /**
     * Types de contrats supportés.
     */
    protected array $typesSupportes = ['CDI', 'CDD', 'Stage', 'Intérim', 'Freelance'];

    /**
     * Vue Blade utilisée pour le rendu PDF.
     */
    protected string $vue = 'gestion.contrats.preview-pdf';

    /**
     * Génère le PDF d'un contrat (retourne l'instance DomPDF).
     *
     * @throws \Exception
     */
    public function generer(Contrat $contrat): DomPdfInstance
    {
        $contrat->load(['employe.departement', 'employe.poste']);

        $employe = $contrat->employe;

        if (!$employe) {
            throw new \Exception("Impossible de générer le PDF : aucun employé associé au contrat.");
        }

        if (!in_array($contrat->type, $this->typesSupportes)) {
            throw new \Exception("Type de contrat non supporté : {$contrat->type}");
        }

        try {
            /** @var DomPdfInstance $pdf */
            $pdf = Pdf::loadView($this->vue, [
                    'contrat' => $contrat,
                    'employe' => $employe,
                ])
                ->setPaper('A4', 'portrait')
                ->setOption('isRemoteEnabled', true)
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('defaultFont', 'DejaVu Sans')
                ->setOption('dpi', 150)
                ->setOption('enable_font_subsetting', true);

            return $pdf;

        } catch (\Exception $e) {
            Log::error("Erreur génération PDF contrat {$contrat->numero} : " . $e->getMessage());
            throw new \Exception("Erreur de génération du PDF. Veuillez réessayer.");
        }
    }

    /**
     * Force le téléchargement du PDF.
     */
    public function download(Contrat $contrat)
    {
        /** @var DomPdfInstance $pdf */
        $pdf = $this->generer($contrat);

        return $pdf->download($this->getNomFichier($contrat));
    }

    /**
     * Retourne le nom du fichier PDF.
     */
    public function getNomFichier(Contrat $contrat): string
    {
        $nomEmploye = str_replace(' ', '-', $contrat->employe?->nom_complet ?? 'employe');
        $type       = strtolower(str_replace(' ', '-', $contrat->type));

        return "contrat-{$type}-{$contrat->numero}-{$nomEmploye}.pdf";
    }
}