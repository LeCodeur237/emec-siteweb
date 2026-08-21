# Notifications, Emails Et Evenements

## Perimetre

La Phase 7 met en place une architecture evenementielle backend pour les notifications transactionnelles et administratives existantes.

La Phase 6 Paiements ayant ete reportee, aucun evenement de paiement reel n'est cable dans cette phase. Les emails de donation et les evenements `PaymentSuccessful` / `PaymentFailed` devront etre branches lorsque le socle paiement existera.

## Architecture

```text
HTTP Request
      |
      v
Controller
      |
      v
Service
      |
      v
Database
      |
      v
Event after commit
      |
      v
Queued Listener
      |
      v
Mail / Database Notification
```

Les controllers restent fins. Les traitements sont portes par :

- `ContactMessageService`
- `NewsletterService`
- Events dans `app/Events`
- Listeners dans `app/Listeners`
- Mailables dans `app/Mail`
- Notifications dans `app/Notifications`

## Events

- `ContactMessageReceived` : declenche apres creation d'un message public.
- `NewsletterSubscriberCreated` : declenche lors d'une nouvelle inscription ou reinscription.
- `NewsletterSubscriberUnsubscribed` : declenche lors d'une desinscription validee.

Ces events implementent `ShouldDispatchAfterCommit` pour eviter un traitement avant commit DB.

## Listeners

- `SendContactReceivedMail`
- `NotifyAdminsOfContactMessage`
- `SendNewsletterSubscriptionMail`
- `NotifyAdminsOfNewsletterSubscription`

Les listeners implementent `ShouldQueue`, avec `tries = 3` et backoff progressif.

## Emails

Templates :

- `resources/views/emails/contact/received.blade.php`
- `resources/views/emails/newsletter/subscription-confirmed.blade.php`

Les emails ne contiennent aucun token, secret, mot de passe, PIN, OTP, credential ou donnee bancaire.

## Notifications Admin

Les notifications administratives utilisent la table Laravel standard `notifications`.

Routes :

- `GET /api/v1/admin/notifications`
- `GET /api/v1/admin/notifications/unread`
- `PATCH /api/v1/admin/notifications/{id}/read`
- `DELETE /api/v1/admin/notifications/{id}`

Permission : `notifications.view`.

Les notifications sont propres a l'utilisateur connecte. Un administrateur ne peut pas lire ou supprimer les notifications d'un autre utilisateur.

## Newsletter

Routes publiques :

- `POST /api/v1/newsletter/subscribe`
- `POST /api/v1/newsletter/unsubscribe`

L'inscription est idempotente :

- email nouveau : creation et event.
- email deja inscrit : reponse OK sans doublon.
- email desinscrit : reinscription et event.

La desinscription utilise `email` + `unsubscribe_token`. Elle ne depend jamais d'un id sequentiel public.

## Contact

Route publique :

- `POST /api/v1/contact`

Le message est valide, nettoye avec `strip_tags`, enregistre en `status=new`, puis l'event `ContactMessageReceived` est declenche. La reponse publique ne retourne pas l'email du visiteur.

## Rate Limiting

- `contact` : 5 requetes par minute par IP.
- `newsletter` : 10 requetes par minute par IP.

Ces limites completent la validation et le honeypot `website`.

## Configuration

`.env.example` utilise une configuration locale sans SMTP reel :

```text
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

En production, lancer un worker adapte :

```bash
php artisan queue:work
```

Supervisor, systemd et Horizon ne sont pas configures dans cette phase.

## Tables

- `jobs` : queue database Laravel.
- `notifications` : notifications database Laravel.
- `newsletter_subscribers.unsubscribe_token` : reference de desinscription non sequentielle.
- `failed_jobs` existait deja.

## Tests

Les tests utilisent :

- `Event::fake()`
- `Mail::fake()`
- `Notification::fake()`

Aucun SMTP reel, SMS, WhatsApp, push ou fournisseur externe n'est appele.
