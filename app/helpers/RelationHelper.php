<?php

namespace App\Helpers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RelationHelper
{
    /**
     * ⭐ Enregistre l'erreur technique dans les logs et retourne un message utilisateur.
     *
     * @param  QueryException  $e
     * @param  string  $contexte  Contexte lisible (ex: "suppression d'un employé")
     * @param  string  $messageUtilisateur  Message à afficher à l'utilisateur
     * @return string
     */
    public static function logAndReturnMessage(
        QueryException $e,
        string $contexte,
        string $messageUtilisateur
    ): string {
        // Log technique complet (pour le développeur)
        Log::error("Erreur de relation : {$contexte}", [
            'code'    => $e->getCode(),
            'sql_state' => $e->errorInfo[0] ?? null,
            'message' => $e->getMessage(),
            'sql'     => $e->getSql() ?? null,
            'bindings'=> $e->getBindings() ?? [],
        ]);

        // Message utilisateur (propre, sans détails techniques)
        return $messageUtilisateur;
    }

    /**
     * ⭐ Vérifie si une exception est une violation de clé étrangère.
     */
    public static function isForeignKeyViolation(QueryException $e): bool
    {
        $sqlState = $e->errorInfo[0] ?? '';
        $errorCode = $e->errorInfo[1] ?? 0;

        // MySQL : SQLSTATE 23000 + code 1451 (RESTRICT) ou 1452 (NOT NULL/FK)
        return $sqlState === '23000' && in_array($errorCode, [1451, 1452]);
    }

    /**
     * ⭐ Détecte la table source de la violation depuis le message SQL.
     */
    public static function detecterTableSource(QueryException $e): ?string
    {
        $message = $e->getMessage();

        // MySQL : "Cannot delete or update a parent row: a foreign key constraint fails (`db`.`TABLE`, CONSTRAINT ...)"
        if (preg_match('/`[^`]+`\.`([^`]+)`/', $message, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * ⭐ Retourne un message adapté selon la table source.
     */
    public static function messageParTable(string $table, string $entite = 'cet élément'): string
    {
        $messages = [
            'absences'    => "{$entite} ne peut pas être supprimé(e) car il/elle possède des absences enregistrées. Pour préserver l'historique, veuillez archiver ou désactiver plutôt que supprimer définitivement.",
            'presences'   => "{$entite} ne peut pas être supprimé(e) car il/elle possède des présences enregistrées. Pour préserver l'historique, veuillez archiver ou désactiver plutôt que supprimer définitivement.",
            'pointages'   => "{$entite} ne peut pas être supprimé(e) car il/elle possède des pointages enregistrés. Pour préserver l'historique, veuillez archiver ou désactiver plutôt que supprimer définitivement.",
            'contrats'    => "{$entite} ne peut pas être supprimé(e) car il/elle possède des contrats associés. Veuillez d'abord archiver les contrats.",
            'documents'   => "{$entite} ne peut pas être supprimé(e) car il/elle possède des documents uploadés. Veuillez d'abord supprimer les documents.",
            'qr_codes'    => "{$entite} ne peut pas être supprimé(e) car il/elle possède des QR codes actifs. Veuillez d'abord les révoquer.",
            'conges'      => "{$entite} ne peut pas être supprimé(e) car il/elle possède des demandes de congé. Veuillez d'abord les archiver.",
            'demande_conges' => "{$entite} ne peut pas être supprimé(e) car il/elle possède des demandes de congé. Veuillez d'abord les archiver.",
            'postes'      => "{$entite} ne peut pas être supprimé(e) car des postes y sont associés. Veuillez d'abord les retirer.",
            'employes'    => "{$entite} ne peut pas être supprimé(e) car des employés y sont associés. Veuillez d'abord les réaffecter à un autre département.",
            'evaluations' => "{$entite} ne peut pas être supprimé(e) car il/elle possède des évaluations enregistrées.",
            'salaires'    => "{$entite} ne peut pas être supprimé(e) car il/elle possède des salaires enregistrés.",
        ];

        return $messages[$table]
            ?? "{$entite} ne peut pas être supprimé(e) car d'autres enregistrements y sont associés. Veuillez d'abord traiter ces éléments.";
    }
}