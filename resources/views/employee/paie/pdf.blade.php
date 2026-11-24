<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin de paie</title>

    <style>
        @page {
            margin: 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #334155;
            line-height: 1.5;
        }

        .header {
            border-bottom: 3px solid #1e293b;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #0f172a;
            font-size: 23px;
            margin: 0;
        }

        .header p {
            color: #64748b;
            margin: 5px 0 0;
        }

        .period {
            text-align: right;
            color: #475569;
            font-weight: bold;
        }

        .section-title {
            background: #f1f5f9;
            padding: 9px 12px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 20px;
            border-left: 4px solid #475569;
        }

        .info-table,
        .pay-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 7px 5px;
            border-bottom: 1px solid #e2e8f0;
        }

        .label {
            color: #64748b;
            width: 30%;
        }

        .pay-table th {
            background: #e2e8f0;
            color: #334155;
            text-align: left;
            padding: 10px;
        }

        .pay-table td {
            padding: 9px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
        }

        .total-row {
            background: #f8fafc;
            font-weight: bold;
        }

        .net-box {
            margin-top: 25px;
            padding: 17px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            text-align: right;
        }

        .net-label {
            color: #047857;
            font-size: 12px;
            font-weight: bold;
        }

        .net-value {
            color: #065f46;
            font-size: 22px;
            font-weight: bold;
            margin-top: 5px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 10px;
            border-top: 1px solid #cbd5e1;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
        }
    </style>
</head>

<body>

    {{-- En-tête --}}
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h1>BULLETIN DE PAIE</h1>
                    <p>Document de rémunération</p>
                </td>

                <td class="period">
                    Période : {{ $periodeLabel }}
                    <br>
                    Généré le : {{ now()->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Informations employé --}}
    <div class="section-title">INFORMATIONS DE L'EMPLOYÉ</div>

    <table class="info-table">
        <tr>
            <td class="label">Nom complet</td>
            <td>
                {{ $employe->nom_complet ?? trim(($employe->prenom ?? '') . ' ' . ($employe->nom ?? '')) }}
            </td>
        </tr>

        <tr>
            <td class="label">Matricule</td>
            <td>{{ $employe->matricule ?? 'Non renseigné' }}</td>
        </tr>

        <tr>
            <td class="label">Période de paie</td>
            <td>{{ $periodeLabel }}</td>
        </tr>
    </table>

    {{-- Détail de la rémunération --}}
    <div class="section-title">DÉTAIL DE LA RÉMUNÉRATION</div>

    <table class="pay-table">
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Type</th>
                <th class="amount">Montant (MGA)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Salaire de base</td>
                <td>Base</td>
                <td class="amount">
                    {{ number_format($bulletin->salaire_base, 2, ',', ' ') }}
                </td>
            </tr>

            @foreach($bulletin->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->libelle }}</td>
                    <td>
                        {{ $ligne->type === 'prime' ? 'Prime' : 'Retenue' }}
                    </td>
                    <td class="amount">
                        {{ number_format($ligne->montant, 2, ',', ' ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Récapitulatif --}}
    <div class="section-title">RÉCAPITULATIF</div>

    <table class="pay-table">
        <tbody>
            <tr>
                <td>Total salaire brut</td>
                <td class="amount">
                    {{ number_format($bulletin->salaire_brut, 2, ',', ' ') }} MGA
                </td>
            </tr>

            <tr>
                <td>Total des primes</td>
                <td class="amount">
                    {{ number_format($bulletin->total_primes, 2, ',', ' ') }} MGA
                </td>
            </tr>

            <tr>
                <td>Total des retenues</td>
                <td class="amount">
                    {{ number_format($bulletin->total_retenues, 2, ',', ' ') }} MGA
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Net à payer --}}
    <div class="net-box">
        <div class="net-label">NET À PAYER</div>

        <div class="net-value">
            {{ number_format($bulletin->salaire_net, 2, ',', ' ') }} MGA
        </div>
    </div>

    <div class="footer">
        Document généré automatiquement par le système de gestion des ressources humaines.
        <br>
        Bulletin de paie — {{ $periodeLabel }}
    </div>

</body>
</html>
