<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription confirmee - EMEC</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <main style="max-width: 640px; margin: 0 auto; padding: 24px;">
        <h1 style="font-size: 22px; margin-bottom: 16px;">Inscription confirmee</h1>
        <p>Bonjour {{ $subscriber->name ?: 'cher abonne' }},</p>
        <p>Votre inscription a la newsletter EMEC est confirmee.</p>
        <p>Vous recevrez uniquement les communications utiles d'EMEC.</p>
        <p style="margin-top: 24px;">EMEC</p>
    </main>
</body>
</html>
