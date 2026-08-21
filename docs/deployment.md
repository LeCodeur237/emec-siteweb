# Deployment

## Procedure Generique

1. Recuperer le code de la version a deployer.
2. Installer les dependances PHP :

```bash
composer install --no-dev --optimize-autoloader
```

3. Configurer `.env` depuis `.env.example`.
4. Verifier `APP_KEY`, `APP_ENV`, `APP_DEBUG`, `APP_URL`, DB, mail, queue et CORS.
5. Verifier la connexion DB.
6. Executer les migrations :

```bash
php artisan migrate --force
```

7. Creer le lien storage :

```bash
php artisan storage:link
```

8. Construire les caches :

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

9. Demarrer ou redemarrer le worker queue :

```bash
php artisan queue:work
```

10. Verifier les permissions filesystem pour `storage` et `bootstrap/cache`.
11. Verifier le health check :

```text
GET /api/v1/health
```

## Staging

Preparer un environnement staging avant production :

- domaine API staging ;
- HTTPS staging ;
- DB separee ;
- CORS vers les frontends staging ;
- mail sandbox ou log ;
- queue active ;
- storage separe ;
- monitoring minimal ;
- paiement sandbox lorsque la Phase 6 sera implementee.

## Rollback Application

1. Revenir a l'ancienne release applicative.
2. Relancer les caches.
3. Redemarrer les workers queue.
4. Verifier `/api/v1/health`.

## Rollback Migration

Un rollback migration peut detruire ou transformer des donnees. Avant toute action :

- verifier la migration concernee ;
- sauvegarder la DB ;
- tester en staging ;
- preferer une migration corrective si les donnees de production sont sensibles.

Commande Laravel possible seulement apres validation :

```bash
php artisan migrate:rollback --step=1
```

## Restauration DB Et Fichiers

- Restaurer la DB depuis un backup valide.
- Restaurer `storage/app/public` si des medias sont concernes.
- Verifier la coherence DB/fichiers.
- Relancer les tests de fumee API.
