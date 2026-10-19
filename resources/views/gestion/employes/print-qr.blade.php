<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code — {{ $employe->nom_complet }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .qr-card {
            background: white;
            width: 100%;
            max-width: 480px;
            padding: 40px 30px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .qr-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 8px;
            background: linear-gradient(90deg, #7c3aed, #ec4899);
        }
        .brand { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 30px; }
        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white;
        }
        .brand-name { font-size: 16px; font-weight: 800; letter-spacing: 0.5px; color: #1e293b; }
        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            color: white;
            font-size: 11px; font-weight: 800;
            letter-spacing: 2px;
            padding: 6px 16px;
            border-radius: 999px;
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .employee-name { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
        .employee-matricule {
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            font-size: 14px; color: #64748b;
            margin-bottom: 6px; letter-spacing: 1px;
        }
        .employee-poste { font-size: 13px; color: #94a3b8; margin-bottom: 30px; }
        .qr-wrapper {
            background: white;
            padding: 20px;
            border: 3px solid #e2e8f0;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .qr-image { width: 280px; height: 280px; display: block; }
        .qr-code-label {
            font-size: 10px; font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
        .qr-code-value {
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            font-size: 20px; font-weight: 800;
            color: #1e293b;
            letter-spacing: 4px;
            margin-bottom: 24px;
        }
        .instructions {
            background: #f8fafc;
            border-left: 4px solid #7c3aed;
            padding: 16px;
            border-radius: 12px;
            text-align: left;
            margin-bottom: 20px;
        }
        .instructions-title { font-size: 11px; font-weight: 800; color: #7c3aed; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .instructions-text { font-size: 12px; color: #475569; line-height: 1.6; }
        .footer {
            font-size: 10px; color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }
        .actions { position: fixed; top: 20px; right: 20px; display: flex; gap: 10px; z-index: 100; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13px; font-weight: 700;
            cursor: pointer; border: none;
            transition: all 0.2s; text-decoration: none;
        }
        .btn-print {
            background: linear-gradient(135deg, #7c3aed, #ec4899);
            color: white;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        .btn-print:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(124, 58, 237, 0.4); }
        .btn-close { background: white; color: #475569; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .btn-close:hover { background: #f1f5f9; }
        @media print {
            body { background: white; padding: 0; }
            .actions { display: none !important; }
            .qr-card { box-shadow: none; max-width: 100%; padding: 20px; }
            .qr-image { width: 260px; height: 260px; }
        }
    </style>
</head>
<body>

    <div class="actions">
        <button type="button" onclick="window.print();" class="btn btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Imprimer
        </button>

        <button type="button" onclick="window.close();" class="btn btn-close">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Fermer
        </button>
    </div>

    @php
        // Génération de l'URL de l'API QR (aucune extension PHP requise)
        $qrContent = "EMPLOYE|{$employe->matricule}|{$employe->nom_complet}|CODE:{$qrCode->code}";
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($qrContent);
    @endphp

    <div class="qr-card">

        <div class="brand">
            <div class="brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="brand-name">DIGITAL AGENCY</div>
        </div>

        <div class="badge">QR Code de pointage</div>

        <div class="employee-name">{{ $employe->nom_complet }}</div>
        <div class="employee-matricule">{{ $employe->matricule }}</div>
        <div class="employee-poste">
            {{ $employe->poste?->nom ?? 'Poste non défini' }}
            @if($employe->departement)
                · {{ $employe->departement->nom }}
            @endif
        </div>

        <div class="qr-wrapper">
            <img src="{{ $qrUrl }}"
                 alt="QR Code de {{ $employe->nom_complet }}"
                 class="qr-image">
        </div>

        <div class="qr-code-label">Code unique</div>
        <div class="qr-code-value">{{ $qrCode->code }}</div>

        <div class="instructions">
            <div class="instructions-title">Instructions</div>
            <div class="instructions-text">
                Présentez ce QR code face à la caméra du service RH :
                <br><strong>1.</strong> À l'arrivée le matin
                <br><strong>2.</strong> Au départ le soir
                <br>Conservez ce document en bon état.
            </div>
        </div>

        <div class="footer">
            Généré le {{ $qrCode->genere_le?->format('d/m/Y') }}
            · © {{ date('Y') }} Digital Agency
        </div>
    </div>

</body>
</html>