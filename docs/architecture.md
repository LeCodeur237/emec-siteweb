# Architecture EMEC Backend

## Objectif

Le backend EMEC est une application Laravel centralisee qui alimente plusieurs frontends publics et pourra ensuite porter une interface admin separee.

```text
egliseemec.org          Vue
messages.egliseemec.org React
dosc.egliseemec.org     React
        |
        v
Laravel API
        |
        v
MySQL 8+
```

Evolution prevue :

```text
api.egliseemec.org
admin.egliseemec.org
```

## Principes

- Une seule base MySQL pour les contenus institutionnels, messages, actions DOSC, dons, medias, communications et reglages.
- Les frontends consomment progressivement une API REST versionnee.
- Le socle actuel couvre la base de donnees, les modeles Eloquent, les factories utiles, les seeders de base, les endpoints publics, les CRUD d'administration privee, les medias et les notifications transactionnelles.
- Aucun dashboard frontend admin, paiement reel, upload cloud, SMS, WhatsApp, push ou integration YouTube API n'est prevu dans cette phase.

## Modules

- Administration : utilisateurs, roles, permissions.
- EMEC : eglises, responsables, groupes.
- Evenements : categories, evenements, programmes hebdomadaires.
- Messages : predicateurs, categories, series, messages.
- DOSC : projets sociaux, actions, statistiques, temoignages.
- Dons : campagnes, methodes, donations declarees.
- Medias : fichiers et relations polymorphiques.
- Communication : messages de contact, newsletter.
- Notifications : events, listeners queueables, emails transactionnels et notifications admin en base.
- Configuration : reglages du site.

## Mapping frontend futur

Les tables restent normalisees meme si certains frontends utilisent aujourd'hui des structures statiques ou des noms differents. Le mapping vers les formats attendus par chaque frontend sera traite dans la couche API.

Exemples :

- `messages` + `preachers` + `message_categories` alimenteront `messages.egliseemec.org`.
- `social_projects` + `social_actions` + `impact_stats` alimenteront `dosc.egliseemec.org`.
- `churches` + `groups` + `weekly_programs` + `events` alimenteront `egliseemec.org`.

## API Architecture

Toutes les routes REST publiques sont versionnees sous `/api/v1`. Les endpoints publics restent en lecture seule dans cette phase.

```text
EMEC API
├── /api/v1/...
├── /api/v1/messages/...
├── /api/v1/dosc/...
├── /api/v1/auth/...
└── /api/v1/admin/...
```

Structure mise en place :

- Controleurs : `app/Http/Controllers/Api/V1`
- Form Requests : `app/Http/Requests/Api/V1`
- Resources : `app/Http/Resources/Api/V1`
- Helpers API : `app/Support/Api`
- Services metier : `app/Services`
- Events : `app/Events`
- Listeners : `app/Listeners`
- Mailables : `app/Mail`
- Notifications : `app/Notifications`
- Tests API : `tests/Feature/Api`

### Reponses

Ressource unique :

```json
{
  "data": {
    "id": 1
  }
}
```

Collections paginees : utiliser la pagination native Laravel, avec `data`, `meta` et `links`.

Suppression reussie : convention future `204 No Content`, sauf besoin explicite d'une reponse JSON.

### Erreurs

Les erreurs API retournent JSON. Les routes absentes retournent :

```json
{
  "message": "Resource not found."
}
```

Les erreurs de validation retourneront :

```json
{
  "message": "Validation failed.",
  "errors": {}
}
```

En production, les erreurs serveur sont masquees par une reponse generique.

### Pagination Et Query Parameters

Convention future :

```text
?page=1
&per_page=20
&search=mot
&sort=created_at
&direction=desc
```

`per_page` vaut `20` par defaut et est limite a `100`. Ces valeurs sont configurables via `.env`.

Le tri dynamique doit passer par une whitelist de colonnes autorisees. Le helper `ApiQueryParameters` prepare cette convention.

### CORS

CORS est configure pour les sites publics EMEC et les frontends locaux. Les domaines viennent de `.env` :

- `FRONTEND_URL`
- `FRONTEND_WWW_URL`
- `MESSAGES_FRONTEND_URL`
- `DOSC_FRONTEND_URL`
- `LOCAL_FRONTEND_URL`
- `LOCAL_REACT_FRONTEND_URL`
- `CORS_ALLOWED_ORIGINS` pour surcharger la liste complete

Aucune configuration de production ne doit utiliser `*`.

### Rate Limiting

Le groupe `api` utilise le rate limiter natif Laravel. La limite est configuree par `API_RATE_LIMIT_PER_MINUTE`, avec `120` requetes par minute par defaut.

### Endpoints Techniques

- `GET /api/v1` : identite de l'API.
- `GET /api/v1/health` : statut minimal sans information sensible.

### Endpoints Publics

- Site principal : eglises, responsables, groupes, categories d'evenements, evenements et programmes hebdomadaires.
- Messages : predicateurs, categories, series et messages publics.
- DOSC : projets sociaux, actions sociales, statistiques d'impact, temoignages, campagnes de dons publiques et methodes de dons actives.

### Endpoints Prives

- Auth : login, logout et utilisateur courant via Sanctum Bearer token.
- Admin : profil admin, dashboard de compteurs permissionnes, CRUD Messages, predicateurs, categories de messages, series de messages, eglises, responsables, groupes, categories d'evenements, evenements, programmes hebdomadaires, projets sociaux DOSC, actions sociales, statistiques d'action, temoignages, statistiques d'impact, campagnes de dons, methodes de don, donations declarees, messages de contact, abonnes newsletter, reglages applicatifs, utilisateurs, roles et permissions.
- Media admin : upload securise, metadonnees, URLs Storage, relations polymorphiques et suppression controlee via `MediaService`.
- Notifications admin : lecture, non-lus, marquage lu idempotent et suppression des notifications de l'utilisateur connecte.

### Evenements Et Notifications

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
Event
      |
      v
Listener
      |
      v
Mail / Notification
      |
      v
Queue
```

Les notifications actuelles couvrent contact et newsletter. Les evenements de paiement restent volontairement hors perimetre tant que la phase paiement reel est reportee.

### Frontieres De Securite

Les APIs publiques restent limitees aux donnees utiles. Les endpoints publics contact/newsletter acceptent des ecritures controlees, rate limitees et sans exposition de donnees personnelles en reponse. La phase admin actuelle contient un upload media local via Laravel Filesystem, mais pas de paiement reel, pas de webhook paiement, pas de CDN et pas de stockage cloud impose. Les donations individuelles sont reservees aux routes privees admin et restent absentes des endpoints publics, car elles peuvent contenir nom, email, telephone, reference de transaction, montant et etat de paiement. Les secrets et credentials restent hors `site_settings`. Les mots de passe et tokens des utilisateurs ne sont jamais exposes par les Resources admin.
