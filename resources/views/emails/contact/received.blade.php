<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message recu - EMEC</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <main style="max-width: 640px; margin: 0 auto; padding: 24px;">
        <h1 style="font-size: 22px; margin-bottom: 16px;">Votre message a bien ete recu</h1>
        <p>Bonjour {{ $contactMessage->name }},</p>
        <p>Nous avons bien recu votre message adresse a EMEC.</p>
        @if($contactMessage->subject)
            <p><strong>Sujet :</strong> {{ $contactMessage->subject }}</p>
        @endif
        <p>Merci de votre confiance.</p>
        <p style="margin-top: 24px;">EMEC</p>
    </main>
</body>
</html>
