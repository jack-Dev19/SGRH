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
            line-height: 1.6;
            color: #0f172a;
            background: #ffffff;
        }

        .page {
            padding: 14mm 14mm 12mm 14mm;
            position: relative;
        }

        /* ============ HEADER ============ */
        .header {
            width: 100%;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .brand-block {
            display: inline-block;
            vertical-align: middle;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background-color: #7c3aed;
            border-radius: 10px;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 42px;
        }

        .logo-icon-text {
            color: white;
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .brand-name {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px;
        }

        .brand-title {
            font-size: 15px;
            font-weight: bold;
            color: #7c3aed;
            letter-spacing: 0.5px;
        }

        .brand-sub {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1px;
        }

        .header-right {
            font-size: 8.5px;
            color: #64748b;
            line-height: 1.5;
            text-align: right;
        }

        .header-right strong {
            color: #1e293b;
            font-weight: 600;
        }

        /* ============ TITRE ============ */
        .title-block {
            text-align: center;
            margin: 16px 0 18px 0;
            padding: 12px 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #7c3aed;
            border-right: 4px solid #7c3aed;
            border-radius: 4px;
        }

        .title-block .titre {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 3px;
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
            border-radius: 4px;
            overflow: hidden;
        }

        .info-grid td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
            vertical-align: top;
        }

        .info-grid .label {
            background-color: #f8fafc;
            font-weight: bold;
            color: #64748b;
            width: 20%;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
        }

        .info-grid .value {
            color: #1e293b;
            font-weight: 500;
            width: 30%;
        }

        /* ============ SECTION ============ */
        .section-block {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .section-header {
            display: table;
            width: 100%;
            background-color: #7c3aed;
            border-radius: 6px 6px 0 0;
            padding: 0;
        }

        .section-header-left {
            display: table-cell;
            padding: 8px 12px;
            vertical-align: middle;
        }

        .section-header-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            text-align: center;
            line-height: 20px;
            font-size: 10px;
            color: #ffffff;
            font-weight: bold;
            vertical-align: middle;
            margin-right: 6px;
        }

        .section-header-title {
            display: inline-block;
            vertical-align: middle;
            font-size: 10px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .section-header-right {
            display: table-cell;
            padding: 8px 12px;
            text-align: right;
            font-size: 8px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            vertical-align: middle;
            letter-spacing: 0.3px;
        }

        /* ============ TABLE PAIE ============ */
        .pay-table {
            width: 100%;
            border-collapse: collapse;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 0 0 6px 6px;
            overflow: hidden;
        }

        .pay-table td {
            padding: 7px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10px;
            vertical-align: middle;
        }

        .pay-table tr:last-child td {
            border-bottom: none;
        }

        .pay-table .label {
            color: #475569;
            width: 70%;
        }

        .pay-table .label .sub-label {
            color: #94a3b8;
            font-size: 8.5px;
            font-weight: normal;
            display: block;
            margin-top: 1px;
        }

        .pay-table .value {
            text-align: right;
            font-weight: 600;
            color: #1e293b;
            width: 30%;
            font-variant-numeric: tabular-nums;
        }

        .pay-table tr.row-alt td {
            background-color: #fafafa;
        }

        .pay-table tr.subtotal td {
            background-color: #f1f5f9;
            font-weight: bold;
            color: #1e293b;
            border-top: 2px solid #cbd5e1;
            border-bottom: 1px solid #e2e8f0;
        }

        .pay-table tr.subtotal td.label {
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pay-table tr.total td {
            background-color: #ede9fe;
            font-weight: bold;
            font-size: 11px;
            color: #5b21b6;
            border-top: 2px solid #7c3aed;
            border-bottom: 2px solid #7c3aed;
        }

        .pay-table tr.total td.label {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 10px;
        }

        .pay-table tr.negatif .value {
            color: #dc2626;
        }

        /* ============ NET À PAYER ============ */
        .net-box {
            background: linear-gradient(135deg, #ede9fe 0%, #fce7f3 100%);
            border: 2px solid #7c3aed;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 18px 0;
            text-align: center;
        }

        .net-box .net-label {
            font-size: 9px;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .net-box .net-value {
            font-size: 24px;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 0.5px;
            font-variant-numeric: tabular-nums;
        }

        .net-box .net-sub {
            font-size: 8px;
            color: #7c3aed;
            margin-top: 4px;
            font-style: italic;
        }

        /* ============ SIGNATURES ============ */
        .signatures-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0;
            margin-top: 24px;
            page-break-inside: avoid;
        }

        .signatures-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature-box {
            border: 1.5px dashed #cbd5e1;
            border-radius: 8px;
            padding: 10px 14px;
            min-height: 90px;
            background-color: #fefefe;
        }

        .signature-label {
            font-size: 8px;
            font-weight: bold;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 6px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #e2e8f0;
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
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 7.5px;
            color: #94a3b8;
            line-height: 1.7;
        }

        .footer-brand {
            font-weight: bold;
            color: #7c3aed;
            font-size: 8.5px;
            letter-spacing: 1.5px;
            margin-bottom: 3px;
        }

        /* ============ WATERMARK ============ */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 90px;
            font-weight: bold;
            color: rgba(124, 58, 237, 0.04);
            z-index: -1;
            white-space: nowrap;
            letter-spacing: 6px;
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
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 55%;">
                        <div class="logo-icon">
                            <span class="logo-icon-text">DA</span>
                        </div>
                        <div class="brand-name">
                            <div class="brand-title">{{ $nomEntreprise }}</div>
                            <div class="brand-sub">Solutions Digitales · Madagascar</div>
                        </div>
                    </td>
                    <td class="header-right" style="width: 45%;">
                        <strong>{{ $adresseEntreprise }}</strong><br>
                        Tél : {{ $telephoneEntreprise }}<br>
                        {{ $emailEntreprise }}<br>
                        NIF : {{ $nifEntreprise }}<br>
                        STAT : {{ $statEntreprise }}
                    </td>
                </tr>
            </table>
        </div>

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
        {{-- 1. GAINS                                                   --}}
        {{-- ========================================================= --}}
        <div class="section-block">
            <div class="section-header">
                <div class="section-header-left">
                    <span class="section-header-icon">1</span>
                    <span class="section-header-title">Éléments de rémunération</span>
                </div>
                <div class="section-header-right">Gains</div>
            </div>
            <table class="pay-table">
                <tr>
                    <td class="label">Salaire de base</td>
                    <td class="value">{{ $fmt($salaireBase ?? 0) }}</td>
                </tr>
                <tr class="row-alt">
                    <td class="label">
                        Prime d'ancienneté
                        <span class="sub-label">({{ $ancienneteAnnees }} an(s) × 1% = {{ number_format($tauxAnciennete * 100, 0) }}%)</span>
                    </td>
                    <td class="value">{{ $fmt($primeAnciennete ?? 0) }}</td>
                </tr>
                <tr>
                    <td class="label">Prime de transport</td>
                    <td class="value">{{ $fmt($primeTransport ?? 0) }}</td>
                </tr>
                <tr class="row-alt">
                    <td class="label">Prime de logement</td>
                    <td class="value">{{ $fmt($primeLogement ?? 0) }}</td>
                </tr>
                <tr class="subtotal">
                    <td class="label">Total brut</td>
                    <td class="value">{{ $fmt($salaireBrut ?? 0) }}</td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- 2. RETENUES                                                --}}
        {{-- ========================================================= --}}
        <div class="section-block">
            <div class="section-header">
                <div class="section-header-left">
                    <span class="section-header-icon">2</span>
                    <span class="section-header-title">Retenues salariales</span>
                </div>
                <div class="section-header-right">Déductions</div>
            </div>
            <table class="pay-table">
                <tr class="negatif">
                    <td class="label">
                        CNAPS salarié
                        <span class="sub-label">(1%)</span>
                    </td>
                    <td class="value">- {{ $fmt($cnapsSalarie ?? 0) }}</td>
                </tr>
                <tr class="negatif row-alt">
                    <td class="label">
                        OSTIE salarié
                        <span class="sub-label">(1%)</span>
                    </td>
                    <td class="value">- {{ $fmt($ostieSalarie ?? 0) }}</td>
                </tr>
                <tr class="negatif">
                    <td class="label">
                        IRSA
                        <span class="sub-label">(20% simplifié)</span>
                    </td>
                    <td class="value">- {{ $fmt($irsa ?? 0) }}</td>
                </tr>
                <tr class="subtotal">
                    <td class="label">Total retenues</td>
                    <td class="value">- {{ $fmt($totalRetenues ?? 0) }}</td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- NET À PAYER                                                --}}
        {{-- ========================================================= --}}
        <div class="net-box">
            <div class="net-label">Net à payer</div>
            <div class="net-value">{{ $fmt($salaireNet ?? 0) }}</div>
            <div class="net-sub">Salaire net après déductions salariales</div>
        </div>

        {{-- ========================================================= --}}
        {{-- 3. CHARGES PATRONALES                                      --}}
        {{-- ========================================================= --}}
        <div class="section-block">
            <div class="section-header">
                <div class="section-header-left">
                    <span class="section-header-icon">3</span>
                    <span class="section-header-title">Charges patronales</span>
                </div>
                <div class="section-header-right">Employeur</div>
            </div>
            <table class="pay-table">
                <tr>
                    <td class="label">
                        CNAPS employeur
                        <span class="sub-label">(13%)</span>
                    </td>
                    <td class="value">{{ $fmt($cnapsEmployeur ?? 0) }}</td>
                </tr>
                <tr class="row-alt">
                    <td class="label">
                        OSTIE employeur
                        <span class="sub-label">(5%)</span>
                    </td>
                    <td class="value">{{ $fmt($ostieEmployeur ?? 0) }}</td>
                </tr>
                <tr class="subtotal">
                    <td class="label">Total charges patronales</td>
                    <td class="value">{{ $fmt($totalCotisationsPatronales ?? 0) }}</td>
                </tr>
                <tr class="total">
                    <td class="label">Coût total employeur</td>
                    <td class="value">{{ $fmt($coutTotal ?? 0) }}</td>
                </tr>
            </table>
        </div>

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