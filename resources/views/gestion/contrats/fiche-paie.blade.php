@php
    /* ================================================================
     |  CONFIGURATION ENTREPRISE
     | ================================================================ */
    $nomEntreprise        = 'DIGITAL AGENCY';
    $adresseEntreprise    = 'Lot III G 08 HANGAR Antohomadinika, Antananarivo';
    $telephoneEntreprise  = '+261 34 00 000 00';
    $emailEntreprise      = 'contact@digitalagency.mg';
    $nifEntreprise        = '3000 123 456';
    $statEntreprise       = '47110 11 2020 0 00123';
    $cnapsEmployeur       = '123456789';

    /* ================================================================
     |  SÉCURISATION DES VARIABLES
     | ================================================================ */
    $nomCompletEmploye = $employe->nom_complet ?? 'Employé';
    $matricule         = $employe->matricule   ?? '—';
    $departement       = optional($employe->departement)->nom ?? '—';
    $poste             = optional($employe->poste)->nom       ?? '—';

    // Formatage monétaire sécurisé
    $fmt = function ($montant) {
        return number_format((float) ($montant ?? 0), 0, ',', ' ') . ' Ar';
    };

    // Sécurité sur les variables de calcul
    $ancienneteAnnees = $ancienneteAnnees ?? 0;
    $tauxAnciennete   = $tauxAnciennete   ?? 0;
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fiche de paie — {{ $periodeLabel }} — {{ $nomCompletEmploye }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #1e293b;
        }

        .page {
            padding: 15mm 14mm 12mm 14mm;
        }

        /* ============ HEADER ============ */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }

        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }

        .header-left { width: 55%; }
        .header-right { width: 45%; text-align: right; }

        .logo-icon {
            width: 40px;
            height: 40px;
            background-color: #7c3aed;
            border-radius: 10px;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 40px;
        }

        .logo-icon-text {
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        .brand-name {
            display: inline-block;
            vertical-align: middle;
            margin-left: 8px;
        }

        .brand-title {
            font-size: 15px;
            font-weight: bold;
            color: #7c3aed;
            letter-spacing: 0.5px;
        }

        .brand-sub {
            font-size: 7.5px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-right {
            font-size: 8.5px;
            color: #64748b;
            line-height: 1.6;
        }

        .header-right strong {
            color: #7c3aed;
        }

        .top-banner {
            height: 4px;
            background: linear-gradient(90deg, #7c3aed 0%, #ec4899 100%);
            margin-bottom: 18px;
            border-radius: 2px;
        }

        /* ============ TITRE ============ */
        .title-block {
            text-align: center;
            margin-bottom: 20px;
            padding: 12px 0;
            border-top: 2px solid #1e293b;
            border-bottom: 2px solid #1e293b;
        }

        .title-block .titre {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .title-block .periode {
            font-size: 11px;
            font-weight: bold;
            color: #7c3aed;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        /* ============ INFO GRID ============ */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .info-grid td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: top;
        }

        .info-grid .label {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            width: 22%;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
        }

        .info-grid .value {
            color: #1e293b;
            font-weight: 500;
        }

        /* ============ TABLEAU PAIE ============ */
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: white;
            background-color: #7c3aed;
            padding: 6px 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 0;
            border-radius: 4px 4px 0 0;
        }

        .pay-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .pay-table td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .pay-table .label {
            color: #475569;
            width: 65%;
        }

        .pay-table .value {
            text-align: right;
            font-weight: 600;
            color: #1e293b;
            width: 35%;
        }

        .pay-table tr.subtotal td {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
            border-top: 2px solid #cbd5e1;
        }

        .pay-table tr.total td {
            background-color: #ede9fe;
            font-weight: bold;
            font-size: 11px;
            color: #5b21b6;
            border-top: 2px solid #7c3aed;
            border-bottom: 2px solid #7c3aed;
        }

        .pay-table tr.negatif .value {
            color: #dc2626;
        }

        /* ============ NET À PAYER ============ */
        .net-box {
            background: linear-gradient(135deg, #ede9fe 0%, #fce7f3 100%);
            border: 2px solid #7c3aed;
            border-radius: 8px;
            padding: 14px 18px;
            margin: 18px 0;
            text-align: center;
        }

        .net-box .net-label {
            font-size: 9px;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .net-box .net-value {
            font-size: 22px;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 0.5px;
        }

        /* ============ SIGNATURES ============ */
        .signatures-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
            margin-top: 30px;
        }

        .signatures-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 12px;
            min-height: 90px;
            background-color: #fefefe;
        }

        .signature-label {
            font-size: 8px;
            font-weight: bold;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #cbd5e1;
        }

        .signature-line {
            margin-top: 45px;
            padding-top: 4px;
            font-size: 7.5px;
            color: #94a3b8;
            text-align: center;
            font-style: italic;
        }

        /* ============ FOOTER ============ */
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 7.5px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-brand {
            font-weight: bold;
            color: #7c3aed;
            font-size: 8.5px;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        /* ============ WATERMARK ============ */
        .watermark {
            position: fixed;
            top: 48%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-40deg);
            font-size: 85px;
            font-weight: bold;
            color: rgba(124, 58, 237, 0.04);
            z-index: -1;
            white-space: nowrap;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .bold        { font-weight: bold; }
        .mt-10       { margin-top: 10px; }
        .mb-10       { margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="watermark">DIGITAL AGENCY</div>

    <div class="page">

        {{-- ========================================================= --}}
        {{-- EN-TÊTE                                                    --}}
        {{-- ========================================================= --}}
        <table class="header">
            <tr>
                <td class="header-left">
                    <div class="logo-icon">
                        <span class="logo-icon-text">DA</span>
                    </div>
                    <div class="brand-name">
                        <div class="brand-title">{{ $nomEntreprise }}</div>
                        <div class="brand-sub">Solutions Digitales · Madagascar</div>
                    </div>
                </td>
                <td class="header-right">
                    <strong>{{ $adresseEntreprise }}</strong><br>
                    Tél : {{ $telephoneEntreprise }}<br>
                    {{ $emailEntreprise }}<br>
                    NIF : {{ $nifEntreprise }} · STAT : {{ $statEntreprise }}
                </td>
            </tr>
        </table>

        <div class="top-banner"></div>

        {{-- ========================================================= --}}
        {{-- TITRE                                                      --}}
        {{-- ========================================================= --}}
        <div class="title-block">
            <div class="titre">Bulletin de paie</div>
            <div class="periode">Période : {{ $periodeLabel }}</div>
        </div>

        {{-- ========================================================= --}}
        {{-- INFOS EMPLOYÉ / ENTREPRISE                                 --}}
        {{-- ========================================================= --}}
        <table class="info-grid">
            <tr>
                <td class="label">Nom et prénom</td>
                <td class="value"><strong>{{ $nomCompletEmploye }}</strong></td>
                <td class="label">Matricule</td>
                <td class="value"><strong>{{ $matricule }}</strong></td>
            </tr>
            <tr>
                <td class="label">Poste</td>
                <td class="value">{{ $poste }}</td>
                <td class="label">Département</td>
                <td class="value">{{ $departement }}</td>
            </tr>
            <tr>
                <td class="label">Type de contrat</td>
                <td class="value">{{ $contrat->type ?? '—' }}</td>
                <td class="label">Date d'embauche</td>
                <td class="value">
                    {{ optional($contrat->date_debut)->format('d/m/Y') ?? '—' }}
                </td>
            </tr>
            <tr>
                <td class="label">Ancienneté</td>
                <td class="value">{{ $ancienneteAnnees }} an(s)</td>
                <td class="label">N° CNAPS</td>
                <td class="value">{{ $cnapsEmployeur }}</td>
            </tr>
        </table>

        {{-- ========================================================= --}}
        {{-- GAINS                                                      --}}
        {{-- ========================================================= --}}
        <div class="section-title">1. Éléments de rémunération (Gains)</div>
        <table class="pay-table">
            <tr>
                <td class="label">Salaire de base</td>
                <td class="value">{{ $fmt($salaireBase ?? 0) }}</td>
            </tr>
            <tr>
                <td class="label">
                    Prime d'ancienneté
                    <span style="color: #94a3b8; font-size: 8.5px;">
                        ({{ $ancienneteAnnees }} an(s) × 1% = {{ number_format($tauxAnciennete * 100, 0) }}%)
                    </span>
                </td>
                <td class="value">{{ $fmt($primeAnciennete ?? 0) }}</td>
            </tr>
            <tr>
                <td class="label">Prime de transport</td>
                <td class="value">{{ $fmt($primeTransport ?? 0) }}</td>
            </tr>
            <tr>
                <td class="label">Prime de logement</td>
                <td class="value">{{ $fmt($primeLogement ?? 0) }}</td>
            </tr>
            <tr class="subtotal">
                <td class="label">TOTAL BRUT</td>
                <td class="value">{{ $fmt($salaireBrut ?? 0) }}</td>
            </tr>
        </table>

        {{-- ========================================================= --}}
        {{-- RETENUES                                                   --}}
        {{-- ========================================================= --}}
        <div class="section-title">2. Retenues salariales</div>
        <table class="pay-table">
            <tr class="negatif">
                <td class="label">
                    CNAPS salarié
                    <span style="color: #94a3b8; font-size: 8.5px;">(1%)</span>
                </td>
                <td class="value">- {{ $fmt($cnapsSalarie ?? 0) }}</td>
            </tr>
            <tr class="negatif">
                <td class="label">
                    OSTIE salarié
                    <span style="color: #94a3b8; font-size: 8.5px;">(1%)</span>
                </td>
                <td class="value">- {{ $fmt($ostieSalarie ?? 0) }}</td>
            </tr>
            <tr class="negatif">
                <td class="label">
                    IRSA
                    <span style="color: #94a3b8; font-size: 8.5px;">(20% simplifié)</span>
                </td>
                <td class="value">- {{ $fmt($irsa ?? 0) }}</td>
            </tr>
            <tr class="subtotal">
                <td class="label">TOTAL RETENUES</td>
                <td class="value">- {{ $fmt($totalRetenues ?? 0) }}</td>
            </tr>
        </table>

        {{-- ========================================================= --}}
        {{-- NET À PAYER                                                --}}
        {{-- ========================================================= --}}
        <div class="net-box">
            <div class="net-label">Net à payer</div>
            <div class="net-value">{{ $fmt($salaireNet ?? 0) }}</div>
        </div>

        {{-- ========================================================= --}}
        {{-- COTISATIONS PATRONALES                                     --}}
        {{-- ========================================================= --}}
        <div class="section-title">3. Charges patronales</div>
        <table class="pay-table">
            <tr>
                <td class="label">
                    CNAPS employeur
                    <span style="color: #94a3b8; font-size: 8.5px;">(13%)</span>
                </td>
                <td class="value">{{ $fmt($cnapsEmployeur ?? 0) }}</td>
            </tr>
            <tr>
                <td class="label">
                    OSTIE employeur
                    <span style="color: #94a3b8; font-size: 8.5px;">(5%)</span>
                </td>
                <td class="value">{{ $fmt($ostieEmployeur ?? 0) }}</td>
            </tr>
            <tr class="subtotal">
                <td class="label">TOTAL CHARGES PATRONALES</td>
                <td class="value">{{ $fmt($totalCotisationsPatronales ?? 0) }}</td>
            </tr>
            <tr class="total">
                <td class="label">COÛT TOTAL EMPLOYEUR</td>
                <td class="value">{{ $fmt($coutTotal ?? 0) }}</td>
            </tr>
        </table>

        {{-- ========================================================= --}}
        {{-- SIGNATURES                                                 --}}
        {{-- ========================================================= --}}
        <table class="signatures-table">
            <tr>
                <td>
                    <div class="signature-box">
                        <div class="signature-label">L'Employeur</div>
                        <div class="signature-line">Signature et cachet</div>
                    </div>
                </td>
                <td>
                    <div class="signature-box">
                        <div class="signature-label">L'Employé(e)</div>
                        <div class="signature-line">Signature précédée de « Reçu conforme »</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ========================================================= --}}
        {{-- PIED DE PAGE                                               --}}
        {{-- ========================================================= --}}
        <div class="footer">
            <div class="footer-brand">{{ $nomEntreprise }}</div>
            <p>Document généré électroniquement — © {{ date('Y') }} {{ $nomEntreprise }}. Tous droits réservés.</p>
            <p>Cette fiche de paie est établie conformément au Code du Travail Malgache (Loi n° 2003-044).</p>
            <p>À conserver sans limitation de durée.</p>
        </div>

    </div>

</body>
</html>