<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carnet de vaccination - {{ $enfant->prenom }} {{ $enfant->nom }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #006400;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #006400;
        }
        .sous-titre {
            font-size: 16px;
            color: #008000;
        }
        .infos-enfant {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #006400;
            color: white;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .cachet {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
            border-top: 1px dashed #333;
            padding-top: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .mention-legale {
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🇧🇯 RÉPUBLIQUE DU BÉNIN</div>
        <div class="sous-titre">Ministère de la Santé</div>
        <div>Programme Élargi de Vaccination (PEV)</div>
        <h2>CARNET DE VACCINATION</h2>
        <div>N° {{ $numero_carnet }}</div>
    </div>

    <div class="infos-enfant">
        <h3>IDENTITÉ DE L'ENFANT</h3>
        <p><strong>Nom :</strong> {{ $enfant->nom }}</p>
        <p><strong>Prénom(s) :</strong> {{ $enfant->prenom }}</p>
        <p><strong>Date de naissance :</strong> {{ $enfant->date_naissance->format('d/m/Y') }}</p>
        <p><strong>Lieu de naissance :</strong> {{ $enfant->lieu_naissance ?? 'Non renseigné' }}</p>
        <p><strong>Nom du père :</strong> {{ $enfant->nom_pere ?? 'Non renseigné' }}</p>
        <p><strong>Nom de la mère :</strong> {{ $enfant->nom_mere ?? 'Non renseigné' }}</p>
    </div>

    <h3>HISTORIQUE DES VACCINATIONS</h3>
    
    @if($historique->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Vaccin</th>
                    <th>Lieu</th>
                    <th>Professionnel</th>
                    <th>Lot</th>
                    <th>Prochain rappel</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historique as $vaccin)
                <tr>
                    <td>{{ $vaccin->date_administration->format('d/m/Y') }}</td>
                    <td>{{ $vaccin->vaccin->nom }}</td>
                    <td>{{ $vaccin->lieu_administration }}</td>
                    <td>{{ $vaccin->professionnel_sante ?? '—' }}</td>
                    <td>{{ $vaccin->lot_vaccin ?? '—' }}</td>
                    <td>{{ $vaccin->prochain_rappel ? $vaccin->prochain_rappel->format('d/m/Y') : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #999; text-align: center; padding: 20px;">
            Aucun vaccin enregistré pour le moment.
        </p>
    @endif

    <div class="cachet">
        <p>{{ $cachet }}</p>
        <p>Généré le {{ $date_generation }}</p>
        <p>Document valable jusqu'au {{ now()->addYear()->format('d/m/Y') }}</p>
        <p style="font-size: 10px;">Hash de vérification : {{ $carnet->hash ?? 'N/A' }}</p>
    </div>

    <div class="footer">
        <p class="mention-legale">
            Ce document fait foi jusqu'à preuve du contraire. 
            Toute modification invalide le cachet numérique.
            En cas de perte, contactez votre centre de santé.
        </p>
    </div>
</body>
</html>