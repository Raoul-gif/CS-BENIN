<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel vaccinal - Carnet de Santé Bénin</title>
    <style>
        /* Styles généraux */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* En-tête */
        .header {
            background-color: #047857;
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        
        /* Contenu */
        .content {
            padding: 30px;
            background-color: #ffffff;
        }
        
        /* Boîte d'information enfant */
        .enfant-box {
            background-color: #e6f7f0;
            border-left: 4px solid #047857;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .enfant-box h3 {
            margin-top: 0;
            color: #047857;
            font-size: 18px;
        }
        .enfant-box p {
            margin: 8px 0;
        }
        
        /* Boîte vaccin */
        .vaccin-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .vaccin-box h3 {
            margin-top: 0;
            color: #856404;
            font-size: 18px;
        }
        .vaccin-detail {
            background-color: white;
            border-radius: 5px;
            padding: 15px;
            margin-top: 10px;
        }
        .vaccin-detail table {
            width: 100%;
            border-collapse: collapse;
        }
        .vaccin-detail td {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .vaccin-detail td:first-child {
            font-weight: bold;
            width: 40%;
            color: #555;
        }
        
        /* Bouton d'action */
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background-color: #047857;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #065f46;
        }
        
        /* Pied de page */
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 5px 0;
        }
        
        /* Informations de contact */
        .contact-info {
            background-color: #f0f9ff;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
            text-align: center;
        }
        
        /* Icônes */
        .icon {
            font-size: 20px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>🇧🇯 Carnet de Santé Bénin</h1>
            <p>Programme Élargi de Vaccination</p>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            <h2 style="margin-top: 0; color: #333;">Bonjour {{ $parent->name }},</h2>
            
            <p>Ceci est un rappel concernant la vaccination de votre enfant.</p>

            <!-- Informations de l'enfant -->
            <div class="enfant-box">
                <h3>👶 Enfant concerné</h3>
                <p><strong>{{ $enfant->prenom }} {{ $enfant->nom }}</strong></p>
                <p>Né(e) le : {{ $enfant->date_naissance->format('d/m/Y') }}</p>
                <p>Âge : {{ $enfant->ageFormate  }} mois</p>
                @if($enfant->nom_mere)
                <p>Mère : {{ $enfant->nom_mere }}</p>
                @endif
            </div>

            <!-- Détails du vaccin -->
            <div class="vaccin-box">
                <h3>💉 Vaccin à administrer</h3>
                <div class="vaccin-detail">
                    <table>
                        <tr>
                            <td>Vaccin :</td>
                            <td><strong>{{ $vaccin->nom }}</strong> (dose {{ $vaccin->dose_numero }})</td>
                        </tr>
                        <tr>
                            <td>Protège contre :</td>
                            <td>{{ $vaccin->maladie_evitee }}</td>
                        </tr>
                        <tr>
                            <td>Âge recommandé :</td>
                            <td>{{ $vaccin->ageTexte }}</td>
                        </tr>
                        <tr>
                            <td>Statut :</td>
                            <td><span style="color: #dc2626; font-weight: bold;">⏳ À faire</span></td>
                        </tr>
                    </table>
                </div>
                <p style="margin-top: 15px; font-style: italic; color: #666;">
                    <small>Ce vaccin était recommandé à cet âge. Si vous ne l'avez pas encore fait, prenez rendez-vous dès que possible.</small>
                </p>
            </div>

            <!-- Message d'encouragement -->
            <div class="contact-info">
                <p style="margin: 0;">
                    <span class="icon">🏥</span> Rendez-vous dans le centre de santé le plus proche avec le carnet de santé de votre enfant.
                </p>
            </div>

            <!-- Bouton d'action -->
            <!-- <div class="btn-container">
                <a href="" class="btn">
                    🔔 Voir le carnet de {{ $enfant->prenom }}
                </a>
            </div> -->
            <div style="text-align: center; padding: 10px; background-color: #e6f7f0; border-radius: 5px;">
                🔔 Connectez-vous à votre compte pour voir le carnet de {{ $enfant->prenom }}
            </div>            

            <p style="text-align: center; color: #666;">
                Vous pouvez aussi vous connecter à votre compte pour plus d'informations.
            </p>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p>Carnet de Santé Bénin - Programme Élargi de Vaccination</p>
            <p>Ce message est automatique, merci de ne pas y répondre.</p>
            <p>© {{ date('Y') }} - Tous droits réservés</p>
            <p style="font-size: 10px; color: #999; margin-top: 10px;">
                Conformément à la loi béninoise sur la protection des données, 
                vous pouvez demander la suppression de vos informations à tout moment.
            </p>
        </div>
    </div>
</body>
</html>