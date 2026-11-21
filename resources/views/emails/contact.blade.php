
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau message</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 30px;">

    <div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 12px;">

        <h2 style="color: #81388d;">
            Nouveau message de contact
        </h2>

        <hr>

        <p><strong>Nom :</strong>
            {{ $contactData['nom'] }}
        </p>

        <p><strong>Email :</strong>
            {{ $contactData['email'] }}
        </p>

        <p><strong>Téléphone :</strong>
            {{ $contactData['telephone'] ?? 'Non renseigné' }}
        </p>

        <p><strong>Sujet :</strong>
            {{ $contactData['sujet'] ?? 'Sans sujet' }}
        </p>

        <hr>

        <h3>Message :</h3>

        <p style="white-space: pre-line; line-height: 1.7;">
            {{ $contactData['message'] }}
        </p>

        <hr>

        <p style="color: #64748b; font-size: 12px;">
            Ce message a été envoyé depuis le formulaire
            de contact de votre site web.
        </p>

    </div>

</body>
</html>
