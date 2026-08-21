# EMEC Backend

Socle backend Laravel centralise pour les sites EMEC :

- `egliseemec.org`
- `messages.egliseemec.org`
- `dosc.egliseemec.org`

Ce projet est prevu pour evoluer vers :

- `api.egliseemec.org`
- `admin.egliseemec.org`

## Stack cible

- Laravel 10.50.x
- PHP 8.3+ en environnement cible
- MySQL 8+
- Eloquent ORM
- Migrations Laravel
- Seeders et factories lorsque les donnees de base sont utiles

Note locale : la machine actuelle execute PHP 8.2.12 en CLI. Le socle reste compatible avec PHP 8.3+, mais il faudra aligner la version PHP locale avant de verrouiller strictement `php:^8.3` dans Composer.

## Installation locale

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

La base locale attendue par defaut est :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=emec_backend
DB_USERNAME=root
DB_PASSWORD=
```

## Frontends autorises en CORS

Les origines sont configurees via les variables frontend dediees ou via `CORS_ALLOWED_ORIGINS` pour surcharger toute la liste :

```env
FRONTEND_URL=https://egliseemec.org
MESSAGES_FRONTEND_URL=https://messages.egliseemec.org
DOSC_FRONTEND_URL=https://dosc.egliseemec.org
CORS_ALLOWED_ORIGINS=
```

## Documentation

- Architecture : `docs/architecture.md`
- API : `docs/api.md`
- Base de donnees : `docs/database.md`

## Perimetre de cette phase

Cette premiere phase pose le socle Laravel, la configuration MySQL et la preparation API multi-frontends.

Les elements suivants ne sont pas encore implementes dans cette phase :

- API REST metier complete
- dashboard admin
- paiements reels
- table `official_documents`
