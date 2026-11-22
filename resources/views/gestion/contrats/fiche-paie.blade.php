
@php
    /* CONFIGURATION ENTREPRISE */
    $nomEntreprise = 'DIGITAL AGENCY';
    $adresseEntreprise = 'Lot III G 08 HANGAR Antohomadinika, Antananarivo';
    $telephoneEntreprise = '+261 34 00 000 00';
    $emailEntreprise = 'contact@digitalagency.mg';
    $nifEntreprise = '3000 123 456';
    $statEntreprise = '47110 11 2020 0 00123';
    $numeroCnapsEntreprise = '123456789';

    /* INFORMATIONS EMPLOYE */
    $nomCompletEmploye = $employe->nom_complet
        ?? trim(($employe->nom ?? '') . ' ' . ($employe->prenom ?? ''));

    $matricule = $employe->matricule ?? '—';
    $departement = $employe->departement->nom ?? 'Non renseigné';
    $poste = $employe->poste->nom ?? 'Non renseigné';

    /* FORMATAGE */
    $fmt = function ($montant) {
        return number_format((float) ($montant ?? 0), 2, ',', ' ');
    };

    $ancienneteAnnees = $ancienneteAnnees ?? 0;
    $tauxAnciennete = $tauxAnciennete ?? 0;
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de paie - {{ $periodeLabel }}</title>

    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #334155;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #4338ca;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #312e81;
            font-size: 22px;
            margin: 0;
        }

        .header p {
            color: #64748b;
            margin: 6px 0;
        }

        .header h3 {
            color: #312e81;
            margin-bottom: 0;
        }

        .periode {
            background: #eef2ff;
            color: #3730a3;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            background: #f1f5f9;
            padding: 9px;
            font-weight: bold;
            color: #1e293b;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px;
        }

        th {
            background: #eef2ff;
            text-align: left;
            color: #312e81;
        }

        .montant {
            text-align: right;
        }

        .total {
            background: #f1f5f9;
            font-weight: bold;
        }

        .net {
            background: #ecfdf5;
            border: 2px solid #059669;
            color: #047857;
            padding: 15px;
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .charges {
            background: #fff7ed;
        }

        .footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
            line-height: 1.7;
        }

        .signature-table td {
            height: 75px;
            vertical-align: top;
        }

        .signature {
            margin-top: 40px;
            text-align: center;
            color: #64748b;
            font-size: 9px;
        }
    </style>
</head>

<body>

    {{-- EN-TETE --}}
    <div class="header">
        <h1>{{ $nomEntreprise }}</h1>
        <p>Service des Ressources Humaines</p>
        <p>{{ $adresseEntreprise }}</p>
        <p>
            Tél : {{ $telephoneEntreprise }} |
            {{ $emailEntreprise }}
        </p>
        <p>
            NIF : {{ $nifEntreprise }} |
            STAT : {{ $statEntreprise }}
        </p>
        <h3>BULLETIN DE PAIE</h3>
    </div>

    {{-- PERIODE --}}
    <div class="periode">
        PÉRIODE : {{ $periodeLabel }}
    </div>

    {{-- INFORMATIONS EMPLOYE --}}
    <div class="section-title">
        INFORMATIONS DE L'EMPLOYÉ
    </div>

    <table>
        <tr>
            <td width="35%"><strong>Nom complet</strong></td>
            <td>{{ $nomCompletEmploye }}</td>
        </tr>
        <tr>
            <td><strong>Matricule</strong></td>
            <td>{{ $matricule }}</td>
        </tr>
        <tr>
            <td><strong>Département</strong></td>
            <td>{{ $departement }}</td>
        </tr>
        <tr>
            <td><strong>Poste</strong></td>
            <td>{{ $poste }}</td>
        </tr>
        <tr>
            <td><strong>Type de contrat</strong></td>
            <td>{{ $contrat->type ?? 'Non renseigné' }}</td>
        </tr>
        <tr>
            <td><strong>Date d'embauche</strong></td>
            <td>
                {{ $contrat && $contrat->date_debut
                    ? \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y')
                    : 'Non renseignée' }}
            </td>
        </tr>
        <tr>
            <td><strong>Ancienneté</strong></td>
            <td>{{ $ancienneteAnnees }} an(s)</td>
        </tr>
        <tr>
            <td><strong>N° CNAPS</strong></td>
            <td>{{ $numeroCnapsEntreprise }}</td>
        </tr>
    </table>

    {{-- REMUNERATION --}}
    <div class="section-title">
        DÉTAIL DE LA RÉMUNÉRATION
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="montant">Montant (MGA)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Salaire de base</td>
                <td class="montant">{{ $fmt($salaireBase ?? 0) }}</td>
            </tr>

            <tr>
                <td>
                    Prime d'ancienneté
                    ({{ number_format($tauxAnciennete * 100, 0) }}%)
                </td>
                <td class="montant">{{ $fmt($primeAnciennete ?? 0) }}</td>
            </tr>

            <tr>
                <td>Prime de transport</td>
                <td class="montant">{{ $fmt($primeTransport ?? 0) }}</td>
            </tr>

            <tr>
                <td>Prime de logement</td>
                <td class="montant">{{ $fmt($primeLogement ?? 0) }}</td>
            </tr>

            <tr class="total">
                <td>SALAIRE BRUT</td>
                <td class="montant">{{ $fmt($salaireBrut ?? 0) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- RETENUES --}}
    <div class="section-title">
        RETENUES SALARIALES
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="montant">Montant (MGA)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>CNAPS salarié (1%)</td>
                <td class="montant">- {{ $fmt($cnapsSalarie ?? 0) }}</td>
            </tr>

            <tr>
                <td>OSTIE salarié (1%)</td>
                <td class="montant">- {{ $fmt($ostieSalarie ?? 0) }}</td>
            </tr>

            <tr>
                <td>IRSA (20% simplifié)</td>
                <td class="montant">- {{ $fmt($irsa ?? 0) }}</td>
            </tr>

            <tr class="total">
                <td>TOTAL DES RETENUES</td>
                <td class="montant">- {{ $fmt($totalRetenues ?? 0) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- NET A PAYER --}}
    <div class="net">
        NET À PAYER :
        {{ $fmt($salaireNet ?? 0) }} MGA
    </div>

    {{-- CHARGES PATRONALES --}}
    <div class="section-title">
        CHARGES PATRONALES
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="montant">Montant (MGA)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>CNAPS employeur (13%)</td>
                <td class="montant">{{ $fmt($cnapsEmployeur ?? 0) }}</td>
            </tr>

            <tr>
                <td>OSTIE employeur (5%)</td>
                <td class="montant">{{ $fmt($ostieEmployeur ?? 0) }}</td>
            </tr>

            <tr class="total">
                <td>TOTAL DES CHARGES PATRONALES</td>
                <td class="montant">
                    {{ $fmt($totalCotisationsPatronales ?? 0) }}
                </td>
            </tr>

            <tr class="total charges">
                <td>COÛT TOTAL EMPLOYEUR</td>
                <td class="montant">{{ $fmt($coutTotal ?? 0) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- SIGNATURES --}}
    <div class="section-title">
        SIGNATURES
    </div>

    <table class="signature-table">
        <tr>
            <td width="50%">
                <strong>L'Employeur</strong>
                <div class="signature">Signature et cachet</div>
            </td>
            <td width="50%">
                <strong>L'Employé(e)</strong>
                <div class="signature">Signature précédée de « Reçu conforme »</div>
            </td>
        </tr>
    </table>

    {{-- DATE --}}
    <p style="margin-top:20px;">
        <strong>Date de génération :</strong>
        {{ now()->format('d/m/Y') }}
    </p>

    {{-- FOOTER --}}
    <div class="footer">
        <strong>{{ $nomEntreprise }}</strong><br>
        Document généré électroniquement — © {{ date('Y') }} {{ $nomEntreprise }}.<br>
        Document à conserver sans limitation de durée.
    </div>

</body>
</html>
