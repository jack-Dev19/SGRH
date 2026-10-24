@php
    /* ================================================================
     |  CONFIGURATION DYNAMIQUE PAR TYPE DE CONTRAT
     | ================================================================ */
    $config = [
        'CDI' => [
            'titre_document'        => 'CONTRAT DE TRAVAIL',
            'sous_titre'            => 'À DURÉE INDÉTERMINÉE',
            'badge_color'           => '#10b981',
            'badge_bg'              => '#d1fae5',
            'titre_court'           => 'contrat de travail',
            'appellation'           => 'l\'Employé(e)',
            'appellation_employeur' => 'l\'Employeur',
            'label_beneficiaire'    => 'L\'EMPLOYÉ(E)',
            'nature_remuneration'   => 'un salaire mensuel brut',
            'date_fin_label'        => 'Non applicable (durée indéterminée)',
            'mention_duree'         => 'Le présent contrat est conclu pour une durée indéterminée.',
        ],
        'CDD' => [
            'titre_document'        => 'CONTRAT DE TRAVAIL',
            'sous_titre'            => 'À DURÉE DÉTERMINÉE',
            'badge_color'           => '#f97316',
            'badge_bg'              => '#fed7aa',
            'titre_court'           => 'contrat de travail',
            'appellation'           => 'l\'Employé(e)',
            'appellation_employeur' => 'l\'Employeur',
            'label_beneficiaire'    => 'L\'EMPLOYÉ(E)',
            'nature_remuneration'   => 'un salaire mensuel brut',
            'date_fin_label'        => $contrat->date_fin?->format('d/m/Y') ?? '—',
            'mention_duree'         => 'Le présent contrat est conclu pour une durée déterminée.',
        ],
        'Stage' => [
            'titre_document'        => 'CONVENTION DE STAGE',
            'sous_titre'            => 'STAGE PROFESSIONNEL',
            'badge_color'           => '#3b82f6',
            'badge_bg'              => '#dbeafe',
            'titre_court'           => 'convention de stage',
            'appellation'           => 'le Stagiaire',
            'appellation_employeur' => 'l\'Organisme d\'accueil',
            'label_beneficiaire'    => 'LE STAGIAIRE',
            'nature_remuneration'   => 'une gratification mensuelle',
            'date_fin_label'        => $contrat->date_fin?->format('d/m/Y') ?? '—',
            'mention_duree'         => 'La présente convention est conclue pour une durée déterminée de stage.',
        ],
        'Intérim' => [
            'titre_document'        => 'CONTRAT DE TRAVAIL',
            'sous_titre'            => 'TEMPORAIRE — MISSION',
            'badge_color'           => '#8b5cf6',
            'badge_bg'              => '#e9d5ff',
            'titre_court'           => 'contrat de mission',
            'appellation'           => 'le Salarié temporaire',
            'appellation_employeur' => 'l\'Entreprise utilisatrice',
            'label_beneficiaire'    => 'LE SALARIÉ TEMPORAIRE',
            'nature_remuneration'   => 'un salaire mensuel brut',
            'date_fin_label'        => $contrat->date_fin?->format('d/m/Y') ?? '—',
            'mention_duree'         => 'Le présent contrat est conclu pour une mission temporaire.',
        ],
        'Freelance' => [
            'titre_document'        => 'CONTRAT DE PRESTATION',
            'sous_titre'            => 'DE SERVICES — FREELANCE',
            'badge_color'           => '#ec4899',
            'badge_bg'              => '#fce7f3',
            'titre_court'           => 'contrat de prestation',
            'appellation'           => 'le Prestataire',
            'appellation_employeur' => 'le Client',
            'label_beneficiaire'    => 'LE PRESTATAIRE',
            'nature_remuneration'   => 'des honoraires mensuels',
            'date_fin_label'        => $contrat->date_fin?->format('d/m/Y') ?? '—',
            'mention_duree'         => 'Le présent contrat est conclu pour une mission ponctuelle.',
        ],
    ];

    $type = $contrat->type;
    $c    = $config[$type] ?? $config['CDI'];

    $referenceContrat = $contrat->numero;
    $dateDebut        = $contrat->date_debut?->format('d/m/Y') ?? '—';
    $dateFin          = $contrat->date_fin?->format('d/m/Y');
    $montantFormate   = number_format($contrat->salaire, 0, ',', ' ') . ' ' . $contrat->devise;

    $nomEntreprise        = 'DIGITAL AGENCY';
    $adresseEntreprise    = 'Lot III G 08 HANGAR Antohomadinika, Antananarivo';
    $telephoneEntreprise  = '+261 34 00 000 00';
    $emailEntreprise      = 'contact@digitalagency.mg';
    $nomRepresentant      = 'Le Directeur Général';
    $fonctionRepresentant = 'Directeur Général';

    $nomCompletEmploye = $employe->nom_complet;
    $matricule         = $employe->matricule;
    $departement       = $employe->departement?->nom ?? '—';
    $poste             = $employe->poste?->nom ?? '—';
    $villeSignature    = 'Antananarivo';
    $dateSignature     = now()->format('d/m/Y');
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $c['titre_document'] }} {{ $c['sous_titre'] }} — {{ $referenceContrat }}</title>
    <style>
        /* ============================================================= */
        /* RESET                                                          */
        /* ============================================================= */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            line-height: 1.55;
            color: #1e293b;
        }

        /* ============================================================= */
        /* CONTENEUR PRINCIPAL AVEC MARGES INTERNES                       */
        /* ============================================================= */
        .page {
            padding: 18mm 16mm 15mm 16mm;
            position: relative;
        }

        /* ============================================================= */
        /* EN-TÊTE                                                        */
        /* ============================================================= */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .logo-box {
            display: inline-block;
            vertical-align: middle;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background-color: #7c3aed;
            border-radius: 10px;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            line-height: 38px;
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
            font-size: 16px;
            font-weight: bold;
            color: #7c3aed;
            letter-spacing: 0.5px;
        }

        .brand-sub {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-info {
            text-align: right;
            font-size: 8.5px;
            color: #64748b;
            line-height: 1.7;
        }

        .header-info strong {
            color: #7c3aed;
        }

        /* Bandeau dégradé */
        .top-banner {
            height: 4px;
            background: linear-gradient(90deg, #7c3aed 0%, #ec4899 100%);
            margin-bottom: 20px;
            border-radius: 2px;
        }

        /* ============================================================= */
        /* BADGE + RÉFÉRENCE                                              */
        /* ============================================================= */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .meta-table td {
            vertical-align: middle;
            padding: 0;
        }

        .reference-box {
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }

        .reference-box .ref-value {
            font-family: monospace;
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 1px;
        }

        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 14px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ============================================================= */
        /* TITRE PRINCIPAL                                                */
        /* ============================================================= */
        .title-block {
            text-align: center;
            margin: 20px 0 24px 0;
            padding: 16px 0;
            border-top: 2px solid #1e293b;
            border-bottom: 2px solid #1e293b;
        }

        .title-block .titre {
            font-size: 17px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }

        .title-block .sous-titre {
            font-size: 11px;
            font-weight: bold;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        /* ============================================================= */
        /* SECTIONS                                                       */
        /* ============================================================= */
        .section {
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }

        .section p {
            margin-bottom: 6px;
            text-align: justify;
        }

        /* ============================================================= */
        /* INFO GRID                                                      */
        /* ============================================================= */
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 16px 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .info-grid td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        .info-grid .label {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            width: 38%;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.5px;
        }

        .info-grid .value {
            color: #1e293b;
            font-weight: 500;
        }

        /* ============================================================= */
        /* CLAUSES                                                        */
        /* ============================================================= */
        .clause {
            margin-bottom: 14px;
            text-align: justify;
            page-break-inside: avoid;
        }

        .clause-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 6px;
            padding-left: 10px;
            border-left: 3px solid #7c3aed;
        }

        .clause p {
            font-size: 10.5px;
            margin-bottom: 5px;
            padding-left: 10px;
        }

        .clause ul {
            margin-left: 26px;
            margin-top: 4px;
            font-size: 10.5px;
        }

        .clause ul li {
            margin-bottom: 3px;
        }

        /* ============================================================= */
        /* MONTANT MISE EN AVANT                                          */
        /* ============================================================= */
        .amount-box {
            background: linear-gradient(135deg, #f8fafc 0%, #ede9fe 100%);
            border: 1px solid #c4b5fd;
            border-radius: 8px;
            padding: 10px 14px;
            margin: 8px 0 8px 10px;
            text-align: center;
        }

        .amount-label {
            font-size: 8.5px;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 3px;
        }

        .amount-value {
            font-size: 15px;
            font-weight: bold;
            color: #1e293b;
            letter-spacing: 0.5px;
        }

        /* ============================================================= */
        /* SIGNATURES                                                     */
        /* ============================================================= */
        .signatures {
            margin-top: 45px;
            page-break-inside: avoid;
        }

        .signatures-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
        }

        .signatures-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature-box {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px 14px;
            min-height: 120px;
            background-color: #fefefe;
        }

        .signature-label {
            font-size: 8.5px;
            font-weight: bold;
            color: #7c3aed;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #cbd5e1;
        }

        .signature-name {
            font-size: 10px;
            color: #1e293b;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .signature-role {
            font-size: 8.5px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .signature-line {
            margin-top: 55px;
            padding-top: 5px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
            font-style: italic;
        }

        /* ============================================================= */
        /* PIED DE PAGE                                                   */
        /* ============================================================= */
        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-brand {
            font-weight: bold;
            color: #7c3aed;
            font-size: 9px;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

        /* ============================================================= */
        /* WATERMARK                                                      */
        /* ============================================================= */
        .watermark {
            position: fixed;
            top: 48%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-40deg);
            font-size: 95px;
            font-weight: bold;
            color: rgba(124, 58, 237, 0.045);
            z-index: -1;
            white-space: nowrap;
        }

        /* ============================================================= */
        /* UTILITAIRES                                                    */
        /* ============================================================= */
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .bold        { font-weight: bold; }
        .mt-10       { margin-top: 10px; }
        .mt-16       { margin-top: 16px; }
        .mb-10       { margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="watermark">DIGITAL AGENCY</div>

    <div class="page">

        {{-- ========================================================= --}}
        {{-- EN-TÊTE                                                    --}}
        {{-- ========================================================= --}}
        <table class="header-table">
            <tr>
                <td>
                    <div class="logo-box">
                        <div class="logo-icon">
                            <span class="logo-icon-text">DA</span>
                        </div>
                    </div>
                    <div class="brand-name">
                        <div class="brand-title">{{ $nomEntreprise }}</div>
                        <div class="brand-sub">Solutions Digitales · Madagascar</div>
                    </div>
                </td>
                <td class="header-info">
                    <strong>{{ $adresseEntreprise }}</strong><br>
                    {{ $telephoneEntreprise }}<br>
                    {{ $emailEntreprise }}
                </td>
            </tr>
        </table>

        <div class="top-banner"></div>

        {{-- ========================================================= --}}
        {{-- BADGE + RÉFÉRENCE                                          --}}
        {{-- ========================================================= --}}
        <table class="meta-table">
            <tr>
                <td style="text-align: left;">
                    <span class="badge" style="background-color: {{ $c['badge_bg'] }}; color: {{ $c['badge_color'] }};">
                        {{ $contrat->type }}
                    </span>
                </td>
                <td class="reference-box">
                    Référence : <span class="ref-value">{{ $referenceContrat }}</span><br>
                    Généré le {{ $dateSignature }}
                </td>
            </tr>
        </table>

        {{-- ========================================================= --}}
        {{-- TITRE                                                      --}}
        {{-- ========================================================= --}}
        <div class="title-block">
            <div class="titre">{{ $c['titre_document'] }}</div>
            <div class="sous-titre">{{ $c['sous_titre'] }}</div>
        </div>

        {{-- ========================================================= --}}
        {{-- PRÉAMBULE                                                  --}}
        {{-- ========================================================= --}}
        <div class="section">
            <div class="section-title">Entre les soussignés</div>

            <p>
                <strong>{{ ucfirst($c['appellation_employeur']) }} :</strong>
                <strong>{{ $nomEntreprise }}</strong>, sis(e) à {{ $adresseEntreprise }},
                représenté(e) par <strong>{{ $nomRepresentant }}</strong>,
                en qualité de <strong>{{ $fonctionRepresentant }}</strong>,
                ci-après désigné(e) <strong>« {{ $c['appellation_employeur'] }} »</strong>.
            </p>

            <p class="text-center bold mt-10 mb-10">ET</p>

            <p>
                <strong>{{ $c['label_beneficiaire'] }} :</strong>
                <strong>{{ $nomCompletEmploye }}</strong>,
                matricule <strong>{{ $matricule }}</strong>,
                affecté(e) au département <strong>{{ $departement }}</strong>,
                en qualité de <strong>{{ $poste }}</strong>,
                ci-après désigné(e) <strong>« {{ $c['appellation'] }} »</strong>.
            </p>

            <table class="info-grid">
                <tr>
                    <td class="label">Nom et prénom</td>
                    <td class="value"><strong>{{ $nomCompletEmploye }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Matricule</td>
                    <td class="value">{{ $matricule }}</td>
                </tr>
                <tr>
                    <td class="label">Date de naissance</td>
                    <td class="value">{{ $employe->date_naissance?->format('d/m/Y') ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Adresse</td>
                    <td class="value">{{ $employe->adresse ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Téléphone</td>
                    <td class="value">{{ $employe->telephone ?? '—' }}</td>
                </tr>
                <tr>
                    <td class="label">Poste occupé</td>
                    <td class="value">{{ $poste }}</td>
                </tr>
                <tr>
                    <td class="label">Département</td>
                    <td class="value">{{ $departement }}</td>
                </tr>
            </table>

            <p class="bold mt-16 text-center">IL A ÉTÉ CONVENU CE QUI SUIT :</p>
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE 1                                                  --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">Article 1 — Objet du contrat</div>
            <p>
                Le présent document constitue un(e) <strong>{{ $c['titre_court'] }}</strong>
                de type <strong>{{ $contrat->type }}</strong> et définit les conditions
                de collaboration entre les deux parties, conformément à la
                réglementation applicable à Madagascar.
            </p>
            <p>{{ $c['mention_duree'] }}</p>
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE 2                                                  --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">Article 2 — Durée</div>
            <p>
                Le contrat débute le <strong>{{ $dateDebut }}</strong>
                @if($contrat->type === 'CDI')
                    pour une durée indéterminée.
                @else
                    et prend fin le <strong>{{ $c['date_fin_label'] }}</strong>.
                @endif
            </p>
            <p>
                Toute prolongation ou modification devra faire l'objet d'un avenant
                signé par les deux parties, dans le respect des dispositions légales
                applicables.
            </p>
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE 3                                                  --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">Article 3 — Fonctions et responsabilités</div>
            <p>
                {{ ucfirst($c['appellation']) }} exerce les fonctions de
                <strong>{{ $poste }}</strong> au sein du département
                <strong>{{ $departement }}</strong> et s'engage à accomplir les
                missions qui lui sont confiées avec sérieux et professionnalisme.
            </p>
            @if($contrat->type === 'Stage')
                <p>
                    Un tuteur sera désigné au sein de l'entreprise pour assurer
                    l'encadrement et le suivi pédagogique du stagiaire.
                </p>
            @endif
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE 4 — RÉMUNÉRATION                                   --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">Article 4 — Rémunération</div>
            <p>
                {{ ucfirst($c['appellation']) }} percevra
                <strong>{{ $c['nature_remuneration'] }}</strong> de :
            </p>

            <div class="amount-box">
                <div class="amount-label">Montant mensuel</div>
                <div class="amount-value">{{ $montantFormate }}</div>
            </div>

            @if($contrat->type === 'Stage')
                <p>
                    Cette gratification n'a pas le caractère d'un salaire et
                    n'entraîne aucune affiliation à la CNAPS ou à l'OSTIE.
                </p>
            @elseif($contrat->type === 'Freelance')
                <p>
                    Le paiement sera effectué sur présentation d'une facture, dans
                    un délai de 30 jours.
                </p>
            @else
                <p>
                    Cette rémunération est soumise aux retenues légales
                    (CNAPS, OSTIE, IRSA).
                </p>
            @endif
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE 5 — OBLIGATIONS                                    --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">Article 5 — Obligations des parties</div>
            <p>{{ ucfirst($c['appellation']) }} s'engage à :</p>
            <ul>
                <li>Respecter les horaires et le règlement intérieur ;</li>
                <li>Observer une stricte confidentialité sur les informations de l'entreprise ;</li>
                <li>Exécuter ses missions avec diligence et professionnalisme ;</li>
                <li>Respecter les consignes de sécurité ;</li>
                @if($contrat->type === 'Stage')
                    <li>Rédiger un rapport de stage à l'issue de la période.</li>
                @endif
            </ul>
            <p class="mt-10">
                <strong>{{ ucfirst($c['appellation_employeur']) }}</strong> s'engage à :
            </p>
            <ul>
                <li>Respecter ses obligations contractuelles et légales ;</li>
                <li>Fournir les moyens nécessaires à l'exécution de la mission ;</li>
                @if($contrat->type === 'Stage')
                    <li>Délivrer une attestation de stage à l'issue de la période.</li>
                @else
                    <li>Verser la rémunération dans les délais convenus.</li>
                @endif
            </ul>
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE 6 (Stage/Freelance)                                --}}
        {{-- ========================================================= --}}
        @if(in_array($contrat->type, ['Stage', 'Freelance']))
            <div class="clause">
                <div class="clause-title">Article 6 — Statut</div>
                @if($contrat->type === 'Stage')
                    <p>
                        Le stagiaire n'est pas lié à l'Organisme d'accueil par un
                        contrat de travail. Il ne peut prétendre à aucun poste
                        permanent à l'issue de son stage, sauf accord formel
                        ultérieur des deux parties.
                    </p>
                @else
                    <p>
                        Le Prestataire exerce son activité de manière indépendante,
                        sous sa propre responsabilité, et demeure seul responsable
                        de ses obligations fiscales et sociales.
                    </p>
                    <p>
                        Il n'existe aucun lien de subordination entre le Prestataire
                        et le Client.
                    </p>
                @endif
            </div>
        @endif

        {{-- ========================================================= --}}
        {{-- ARTICLE 7 / 6                                              --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">
                Article {{ in_array($contrat->type, ['Stage', 'Freelance']) ? '7' : '6' }} — Modification et fin du contrat
            </div>
            <p>
                Toute modification ou cessation du contrat devra respecter les
                conditions prévues par le présent contrat et la législation
                applicable.
            </p>
            @if($contrat->type === 'CDD')
                <p>
                    À l'échéance du terme, le contrat prendra fin automatiquement.
                    Une indemnité de précarité sera versée conformément à la loi.
                </p>
            @elseif($contrat->type === 'Intérim')
                <p>
                    À l'échéance de la mission, une indemnité de fin de mission
                    sera versée conformément à la réglementation en vigueur.
                </p>
            @elseif($contrat->type === 'CDI')
                <p>
                    Le contrat pourra être rompu par démission avec préavis,
                    licenciement pour motif légitime, rupture conventionnelle
                    ou départ à la retraite.
                </p>
            @endif
        </div>

        {{-- ========================================================= --}}
        {{-- ARTICLE FINAL                                              --}}
        {{-- ========================================================= --}}
        <div class="clause">
            <div class="clause-title">
                Article {{ in_array($contrat->type, ['Stage', 'Freelance']) ? '8' : '7' }} — Dispositions finales
            </div>
            <p>
                Tout différend relatif à l'interprétation ou à l'exécution du
                présent contrat sera traité conformément à la législation en
                vigueur à Madagascar, et soumis, à défaut d'accord amiable,
                aux juridictions compétentes d'Antananarivo.
            </p>
            <p>Le présent contrat est établi en deux exemplaires originaux, un pour chaque partie.</p>
        </div>

        {{-- ========================================================= --}}
        {{-- LIEU ET DATE                                               --}}
        {{-- ========================================================= --}}
        <p class="mt-16">
            Fait à <strong>{{ $villeSignature }}</strong>,
            le <strong>{{ $dateSignature }}</strong>.
        </p>

        {{-- ========================================================= --}}
        {{-- SIGNATURES                                                 --}}
        {{-- ========================================================= --}}
        <div class="signatures">
            <table class="signatures-table">
                <tr>
                    <td>
                        <div class="signature-box">
                            <div class="signature-label">{{ ucfirst($c['appellation_employeur']) }}</div>
                            <div class="signature-name">{{ $nomRepresentant }}</div>
                            <div class="signature-role">{{ $fonctionRepresentant }}</div>
                            <div class="signature-line">Signature et cachet</div>
                        </div>
                    </td>
                    <td>
                        <div class="signature-box">
                            <div class="signature-label">{{ $c['label_beneficiaire'] }}</div>
                            <div class="signature-name">{{ $nomCompletEmploye }}</div>
                            <div class="signature-role">Matricule : {{ $matricule }}</div>
                            <div class="signature-line">Signature précédée de « Lu et approuvé »</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- PIED DE PAGE                                               --}}
        {{-- ========================================================= --}}
        <div class="footer">
            <div class="footer-brand">{{ $nomEntreprise }}</div>
            <p>Document généré électroniquement — © {{ date('Y') }} {{ $nomEntreprise }}. Tous droits réservés.</p>
            <p>Ce contrat est soumis au Code du Travail Malgache (Loi n° 2003-044).</p>
        </div>

    </div>

</body>
</html>