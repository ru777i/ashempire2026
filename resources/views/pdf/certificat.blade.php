<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $numero }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }
        .cadre {
            border: 10px solid #047857;
            border-radius: 6px;
            margin: 20px;
            padding: 40px 60px;
            height: 92vh;
            box-sizing: border-box;
            position: relative;
        }
        .cadre::before {
            content: '';
            position: absolute;
            top: 12px; left: 12px; right: 12px; bottom: 12px;
            border: 1px solid #a7f3d0;
            border-radius: 4px;
        }
        .entete {
            text-align: center;
            margin-bottom: 10px;
        }
        .entete .organisme {
            font-size: 13px;
            letter-spacing: 2px;
            color: #047857;
            font-weight: bold;
            text-transform: uppercase;
        }
        .titre {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 30px 0 6px 0;
        }
        .sous-titre {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 30px;
        }
        .contenu {
            text-align: center;
            font-size: 15px;
            line-height: 1.9;
            margin: 0 20px;
        }
        .nom-apprenant {
            font-size: 24px;
            font-weight: bold;
            color: #047857;
            margin: 14px 0;
            text-transform: uppercase;
        }
        .formation {
            font-weight: bold;
            color: #065f46;
        }
        .details {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .details .col {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
        .details .col strong {
            display: block;
            color: #1f2937;
            font-size: 13px;
            margin-bottom: 4px;
        }
        .pied {
            position: absolute;
            bottom: 30px;
            left: 60px;
            right: 60px;
            display: table;
            width: calc(100% - 120px);
        }
        .pied .col {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
        .numero {
            position: absolute;
            top: 20px;
            right: 40px;
            font-size: 10px;
            color: #9ca3af;
            font-family: monospace;
        }
        .signature-ligne {
            margin-top: 45px;
            border-top: 1px solid #9ca3af;
            width: 160px;
            margin-left: auto;
            margin-right: auto;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div class="cadre">
        <div class="numero">N° {{ $numero }}</div>

        <div class="entete">
            <div class="organisme">Centre de Formation Professionnelle</div>
        </div>

        <div class="titre">
            {{ $type === 'certificat' ? 'Certificat de Formation' : "Attestation de Formation" }}
        </div>
        <div class="sous-titre">Délivré(e) conformément aux résultats obtenus durant la formation</div>

        <div class="contenu">
            Le présent document atteste que

            <div class="nom-apprenant">
                {{ $inscription->aprenant->user->name ?? '' }} {{ $inscription->aprenant->prenom ?? '' }}
            </div>

            {{ $inscription->aprenant->sexe === 'M' ? 'né' : 'née' }} le
            {{ \Carbon\Carbon::parse($inscription->aprenant->dateNaissance)->format('d/m/Y') }},
            matricule <strong>{{ $inscription->aprenant->matricule ?? '' }}</strong>,
            a suivi avec succès la formation

            <div class="formation">{{ $inscription->sessionn->formation->nom ?? '' }}</div>

            dispensée du {{ \Carbon\Carbon::parse($inscription->sessionn->dateDebut)->format('d/m/Y') }}
            au {{ \Carbon\Carbon::parse($inscription->sessionn->dateFin)->format('d/m/Y') }},
            avec la mention <strong>{{ $resultat->mention }}</strong>.
        </div>

        <div class="details">
            <div class="col">
                <strong>{{ number_format($resultat->moyenneGenerale, 2) }} / 20</strong>
                Moyenne générale
            </div>
            <div class="col">
                <strong>{{ $resultat->descision }}</strong>
                Décision du jury
            </div>
            <div class="col">
                <strong>{{ $dateDelivrance->format('d/m/Y') }}</strong>
                Date de délivrance
            </div>
        </div>

        <div class="pied">
            <div class="col">
                <div class="signature-ligne">Le Directeur du Centre</div>
            </div>
            <div class="col">
                <div class="signature-ligne">Le Responsable Pédagogique</div>
            </div>
        </div>
    </div>
</body>
</html>
