# Production Checklist

## Environment

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` renseignee une seule fois et sauvegardee.
- PHP 8.3+ si la cible projet le confirme.
- `APP_URL` pointe vers le domaine API public.
- Aucun secret reel dans le repository.
- Planifier la montee Laravel majeure necessaire pour resoudre les advisories restantes `laravel/framework`.

## Database

- MySQL 8+.
- Charset/collation `utf8mb4`.
- Utilisateur DB a privileges limites.
- Migrations testees en staging avant production.
- Sauvegarde DB avant chaque deploiement important.

## Storage

- `php artisan storage:link` execute.
- Permissions filesystem limitees au compte applicatif.
- Sauvegarde reguliere de `storage/app/public`.
- Les medias uploades restent traites comme contenu, jamais comme code executable.

## Mail

- SMTP ou fournisseur mail configure en `.env`.
- `MAIL_FROM_ADDRESS` et `MAIL_FROM_NAME` valides.
- En staging, utiliser un mail sandbox ou `MAIL_MAILER=log`.
- Ne jamais stocker les credentials mail dans `site_settings`.

## Queue

- `QUEUE_CONNECTION=database` ou `redis`.
- Worker configure hors application web.
- Commande de base : `php artisan queue:work`.
- Surveiller `php artisan queue:failed`.

## Payments

- Phase paiement reel reportee.
- Avant production paiement : utiliser uniquement les APIs officielles fournisseurs.
- Verifier sandbox, signatures webhook, idempotence, montants, devise, logs et rotation des secrets.

## Security

- HTTPS obligatoire.
- `APP_DEBUG=false`.
- CORS limite aux domaines EMEC et staging.
- Rate limiting actif.
- Logs sans secrets, tokens, PIN, OTP ou credentials.
- Rotation immediate si un secret reel est detecte.

## CORS

- `FRONTEND_URL=https://egliseemec.org`
- `FRONTEND_WWW_URL=https://www.egliseemec.org`
- `MESSAGES_FRONTEND_URL=https://messages.egliseemec.org`
- `DOSC_FRONTEND_URL=https://dosc.egliseemec.org`
- Ajouter le domaine admin/staging seulement si necessaire.
- Ne pas utiliser `CORS_ALLOWED_ORIGINS=*`.

## SSL

- Certificat HTTPS valide.
- Renouvellement automatique verifie.
- HSTS seulement lorsque HTTPS est stable.

## Backups

- DB : sauvegarde quotidienne minimale, plus sauvegarde avant migration.
- Fichiers : sauvegarde reguliere de `storage/app/public`.
- Tester la restauration, pas seulement la creation des backups.
- Conserver une politique de retention documentee.

## Monitoring

- Surveiller HTTP 5xx.
- Surveiller queue failed jobs.
- Surveiller espace disque.
- Surveiller latence DB et erreurs SQL.
- Paiements : monitoring a definir apres Phase 6.

## Logs

- `LOG_LEVEL=warning` ou niveau adapte en production.
- Rotation des logs active cote serveur.
- Pas de query logging complet en production.

## Cron

- Configurer le scheduler Laravel si des taches planifiees sont ajoutees :

```bash
php artisan schedule:run
```

## Deployment

- Installer dependencies avec options production.
- Executer migrations en fenetre controlee.
- Reconstruire caches.
- Verifier health check.
- Conserver une procedure rollback.

## Rollback

- Conserver l'ancienne version applicative.
- Sauvegarde DB avant migration.
- Ne jamais faire de rollback destructif sans validation.
- Restaurer aussi les fichiers si le probleme concerne les medias.
