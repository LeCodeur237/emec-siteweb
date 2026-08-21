# EMEC API

## Versioning

La version publique actuelle est :

```text
/api/v1
```

Les routes publiques metier exposees dans cette version sont en lecture seule, sauf contact et newsletter qui acceptent des ecritures publiques controlees.

## Endpoints

### GET /api/v1

Identifie l'API sans exposer les versions internes de PHP, MySQL ou Laravel.

Reponse `200 OK` :

```json
{
  "name": "EMEC API",
  "version": "v1"
}
```

### GET /api/v1/health

Health check minimal pour verifier la connexion frontend vers API.

Reponse `200 OK` :

```json
{
  "status": "ok"
}
```

### POST /api/v1/contact

Route publique rate limitee par `contact`.

Parametres :

- `name`, requis
- `email`, requis
- `phone`, optionnel
- `subject`, optionnel
- `message`, requis
- `website`, honeypot, doit etre absent/vide

Reponse `201 Created` minimale, sans email public :

```json
{
  "data": {
    "id": 1,
    "status": "new",
    "created_at": "..."
  }
}
```

### POST /api/v1/newsletter/subscribe

Route publique rate limitee par `newsletter`.

Parametres :

- `email`, requis
- `name`, optionnel
- `website`, honeypot

L'inscription est idempotente : un email deja inscrit ne cree pas de doublon.

### POST /api/v1/newsletter/unsubscribe

Route publique rate limitee par `newsletter`.

Parametres :

- `email`, requis
- `unsubscribe_token`, requis

La desinscription ne se fait jamais via id sequentiel.

## Site Principal EMEC

Les endpoints de cette section sont publics, en lecture seule, et n'exposent que les ressources publiques.

### GET /api/v1/churches

Liste paginee des eglises publiees et actives par defaut.

Parametres :

- `page`
- `per_page`, limite par `API_MAX_PER_PAGE`
- `search`
- `city`
- `region`
- `status`, valeurs publiques autorisees : `published`, `archived`
- `active`
- `sort`, valeurs autorisees : `name`, `city`, `region`, `created_at`
- `direction`, valeurs : `asc`, `desc`

Exemple :

```text
GET /api/v1/churches?search=Yaounde&city=Yaounde&sort=name&direction=asc
```

### GET /api/v1/churches/{slug}

Detail d'une eglise publiee et active. Charge les responsables actifs dans `leaders`.

### GET /api/v1/church-leaders

Liste non paginee des responsables d'eglise actifs par defaut.

Parametres :

- `church_id`
- `active`

### GET /api/v1/church-leaders/{id}

Detail d'un responsable actif.

### GET /api/v1/administrative-leaders

Liste non paginee des responsables administratifs actifs par defaut.

Parametres :

- `active`

### GET /api/v1/administrative-leaders/{id}

Detail d'un responsable administratif actif.

### GET /api/v1/groups

Liste paginee des groupes actifs par defaut.

Parametres :

- `page`
- `per_page`
- `search`
- `active`

### GET /api/v1/groups/{slug}

Detail d'un groupe actif. Charge uniquement les responsables actifs dans `leaders`.

### GET /api/v1/group-leaders

Liste non paginee des responsables de groupe actifs par defaut.

Parametres :

- `group_id`
- `active`

### GET /api/v1/group-leaders/{id}

Detail d'un responsable de groupe actif.

### GET /api/v1/event-categories

Liste non paginee des categories d'evenements actives par defaut.

Parametres :

- `active`

### GET /api/v1/event-categories/{slug}

Detail d'une categorie active.

### GET /api/v1/events

Liste paginee des evenements publics. Seuls les evenements `status=published` sont exposes.

Parametres :

- `page`
- `per_page`
- `event_category_id`
- `city`
- `featured`
- `from`, date valide
- `to`, date valide superieure ou egale a `from`
- `search`
- `sort`, valeurs autorisees : `start_at`, `title`, `created_at`, `city`
- `direction`, valeurs : `asc`, `desc`

Tri par defaut : `start_at asc`, adapte au calendrier et aux evenements a venir.

Exemple :

```text
GET /api/v1/events?featured=true&from=2026-09-01&event_category_id=2
```

### GET /api/v1/events/{slug}

Detail d'un evenement publie. Charge `category` et `media`.

### GET /api/v1/weekly-programs

Liste non paginee des programmes hebdomadaires actifs par defaut.

Parametres :

- `day_of_week`
- `active`

Convention `day_of_week` : `1 = lundi`, `7 = dimanche`.

Tri par defaut : `day_of_week`, puis `start_time`.

### GET /api/v1/weekly-programs/{id}

Detail d'un programme actif.

## Mapping Frontend Principal

Le frontend Vue utilise actuellement des champs camelCase comme `baptismName` ou `missionField`. L'API expose les champs DB en snake_case pour rester coherente avec Laravel. Le mapping camelCase pourra etre fait dans la couche frontend ou dans une Resource dediee si une compatibilite stricte devient necessaire.

## Messages EMEC

Les endpoints de cette section sont publics, en lecture seule, et n'exposent que les messages publies avec leurs relations publiques actives.

### GET /api/v1/preachers

Liste paginee des predicateurs actifs par defaut.

Parametres :

- `page`
- `per_page`
- `search`
- `active`

### GET /api/v1/preachers/{slug}

Detail d'un predicateur actif. Charge `media`.

### GET /api/v1/message-categories

Liste non paginee des categories de messages actives par defaut.

Parametres :

- `active`

### GET /api/v1/message-categories/{slug}

Detail d'une categorie active.

### GET /api/v1/message-series

Liste non paginee des series de messages actives par defaut.

Parametres :

- `active`

### GET /api/v1/message-series/{slug}

Detail d'une serie active. Charge `media`.

### GET /api/v1/messages

Liste paginee des messages publies. Un message lie a un predicateur, une categorie ou une serie inactive n'est pas expose.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `title`, `excerpt`, `content`
- `preacher_id`
- `message_category_id`
- `message_series_id`
- `featured`
- `from`, date valide
- `to`, date valide superieure ou egale a `from`
- `sort`, valeurs autorisees : `preached_at`, `title`, `created_at`, `views`
- `direction`, valeurs : `asc`, `desc`

Tri par defaut : `preached_at desc`, avec les dates nulles apres les dates renseignees.

Exemple :

```text
GET /api/v1/messages?featured=true&preacher_id=3&sort=views&direction=desc
```

### GET /api/v1/messages/{slug}

Detail d'un message publie. Charge `preacher`, `category`, `series` et `media`. La lecture publique ne modifie pas le compteur `views`.

## Mapping Frontend Messages

L'API expose les champs DB en snake_case. Les champs YouTube et medias sont retournes tels que stockes (`youtube_video_id`, `youtube_url`, `audio_url`, `pdf_url`, `thumbnail`) sans appel externe a YouTube. Une recherche full-text pourra etre ajoutee plus tard si la recherche `LIKE` devient couteuse.

## Authentication

L'administration utilise Laravel Sanctum avec token Bearer. Le dashboard devra envoyer :

```text
Authorization: Bearer {access_token}
```

Cette phase ne met pas en place de refresh token custom et n'utilise pas l'authentification SPA par cookie.

### POST /api/v1/auth/login

Authentifie un utilisateur actif.

Parametres :

- `email`, requis
- `password`, requis

Reponse `200 OK` :

```json
{
  "message": "Authenticated.",
  "token_type": "Bearer",
  "access_token": "...",
  "user": {}
}
```

Un utilisateur inexistant, inactif ou avec un mauvais mot de passe recoit une erreur generique `422 Validation failed.` pour eviter l'enumeration des comptes.

### GET /api/v1/auth/me

Permission : utilisateur authentifie.

Retourne l'utilisateur connecte avec `roles` et `permissions`. Ne retourne jamais `password`, `remember_token` ou `tokens`.

### POST /api/v1/auth/logout

Permission : utilisateur authentifie.

Revoque le token Sanctum courant.

## Admin

Toutes les routes admin sont sous `/api/v1/admin` et necessitent `auth:sanctum`.

### GET /api/v1/admin/me

Permission : utilisateur authentifie.

Retourne la meme Resource que `/api/v1/auth/me`.

### GET /api/v1/admin/dashboard

Permission : utilisateur authentifie.

Retourne uniquement les compteurs que l'utilisateur a le droit de voir :

- `messages_count` si `messages.view` ou `messages.manage`
- `events_count` si `events.view` ou `events.manage`
- `churches_count` si `churches.manage`
- `groups_count` si `groups.manage`
- `social_projects_count` si `dosc.projects.view` ou `dosc.manage`
- `social_actions_count` si `dosc.actions.view` ou `dosc.manage`
- `users_count` si `users.view` ou `users.manage`

### GET /api/v1/admin/messages

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Liste paginee des messages admin, y compris les brouillons et archives.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `title`, `slug`, `excerpt`, `content`
- `preacher_id`
- `message_category_id`
- `message_series_id`
- `status`, valeurs : `draft`, `published`, `archived`
- `featured`
- `sort`, valeurs : `preached_at`, `title`, `created_at`, `updated_at`, `views`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/admin/messages/{id}

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Retourne les champs d'administration : statut, featured, views, timestamps, relations et medias.

### POST /api/v1/admin/messages

Permission : `messages.create`, `messages.manage` ou role `super_admin`.

Champs acceptes :

- `preacher_id`
- `message_category_id`
- `message_series_id`
- `title`, requis
- `slug`, optionnel, genere depuis `title` si absent
- `excerpt`
- `content`
- `preached_at`
- `duration`
- `youtube_video_id`
- `youtube_url`
- `audio_url`
- `pdf_url`
- `thumbnail`
- `featured`
- `status`, valeurs : `draft`, `published`, `archived`

Publier directement avec `status=published` necessite `messages.publish`, `messages.manage` ou `super_admin`.

### PUT /api/v1/admin/messages/{id}

Permission : `messages.update`, `messages.manage` ou role `super_admin`.

Met a jour les champs envoyes. `id`, `views`, `created_at` et `updated_at` ne sont pas modifiables par l'API.

### PATCH /api/v1/admin/messages/{id}

Permission : `messages.update`, `messages.manage` ou role `super_admin`.

Meme validation que `PUT`, adaptee aux mises a jour partielles.

### DELETE /api/v1/admin/messages/{id}

Permission : `messages.delete`, `messages.manage` ou role `super_admin`.

Detache les medias lies puis supprime le message. Le modele `Message` n'utilise pas `SoftDeletes` dans cette phase.

### GET /api/v1/admin/preachers

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Liste paginee des predicateurs administrables.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `name`, `slug`, `role`, `bio`
- `active`
- `sort`, valeurs : `name`, `role`, `created_at`, `updated_at`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/admin/preachers/{id}

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Retourne le predicateur, `messages_count`, timestamps et medias.

### POST /api/v1/admin/preachers

Permission : `messages.create`, `messages.manage` ou role `super_admin`.

Champs acceptes : `name`, `slug`, `role`, `bio`, `image`, `active`. Le slug est genere depuis `name` si absent.

### PUT/PATCH /api/v1/admin/preachers/{id}

Permission : `messages.update`, `messages.manage` ou role `super_admin`.

Met a jour les champs envoyes. Le slug reste inchange si non envoye.

### DELETE /api/v1/admin/preachers/{id}

Permission : `messages.delete`, `messages.manage` ou role `super_admin`.

Detache les medias du predicateur puis supprime la fiche. Les messages lies conservent leur historique avec `preacher_id = null` via la contrainte DB existante.

### GET /api/v1/admin/message-categories

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Liste paginee des categories de messages.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `name`, `slug`, `description`
- `active`
- `sort`, valeurs : `name`, `created_at`, `updated_at`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/admin/message-categories/{id}

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Retourne la categorie et `messages_count`.

### POST /api/v1/admin/message-categories

Permission : `messages.create`, `messages.manage` ou role `super_admin`.

Champs acceptes : `name`, `slug`, `description`, `active`. Le slug est genere depuis `name` si absent.

### PUT/PATCH /api/v1/admin/message-categories/{id}

Permission : `messages.update`, `messages.manage` ou role `super_admin`.

Met a jour les champs envoyes.

### DELETE /api/v1/admin/message-categories/{id}

Permission : `messages.delete`, `messages.manage` ou role `super_admin`.

Supprime la categorie. Les messages lies conservent leur historique avec `message_category_id = null`.

### GET /api/v1/admin/message-series

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Liste paginee des series de messages.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `name`, `slug`, `description`
- `active`
- `sort`, valeurs : `name`, `created_at`, `updated_at`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/admin/message-series/{id}

Permission : `messages.view`, `messages.manage` ou role `super_admin`.

Retourne la serie, `messages_count`, timestamps et medias.

### POST /api/v1/admin/message-series

Permission : `messages.create`, `messages.manage` ou role `super_admin`.

Champs acceptes : `name`, `slug`, `description`, `cover_image`, `active`. Le slug est genere depuis `name` si absent.

### PUT/PATCH /api/v1/admin/message-series/{id}

Permission : `messages.update`, `messages.manage` ou role `super_admin`.

Met a jour les champs envoyes.

### DELETE /api/v1/admin/message-series/{id}

Permission : `messages.delete`, `messages.manage` ou role `super_admin`.

Detache les medias de la serie puis supprime la fiche. Les messages lies conservent leur historique avec `message_series_id = null`.

### Admin EMEC

Les CRUD admin EMEC suivent le meme contrat REST :

- `GET /api/v1/admin/{resource}`
- `GET /api/v1/admin/{resource}/{id}`
- `POST /api/v1/admin/{resource}`
- `PUT/PATCH /api/v1/admin/{resource}/{id}`
- `DELETE /api/v1/admin/{resource}/{id}`

Ressources disponibles en Phase 4B :

- `churches`
- `church-leaders`
- `administrative-leaders`
- `groups`
- `group-leaders`
- `event-categories`
- `events`
- `weekly-programs`

Permissions :

- Eglises et responsables d'eglise : `churches.view`, `churches.create`, `churches.update`, `churches.delete`, `churches.manage` ou role `super_admin`.
- Responsables administratifs : memes permissions `churches.*`, car ils appartiennent au module institutionnel EMEC.
- Groupes et responsables de groupe : `groups.view`, `groups.create`, `groups.update`, `groups.delete`, `groups.manage` ou role `super_admin`.
- Categories d'evenements, evenements et programmes hebdomadaires : `events.view`, `events.create`, `events.update`, `events.delete`, `events.manage` ou role `super_admin`.

Filtres principaux :

- `churches` : `search`, `city`, `region`, `status`, `active`, `sort`, `direction`.
- `church-leaders` : `search`, `church_id`, `active`, `sort`, `direction`.
- `administrative-leaders` : `search`, `active`, `sort`, `direction`.
- `groups` : `search`, `active`, `sort`, `direction`.
- `group-leaders` : `search`, `group_id`, `active`, `sort`, `direction`.
- `event-categories` : `search`, `active`, `sort`, `direction`.
- `events` : `search`, `event_category_id`, `city`, `status`, `featured`, `from`, `to`, `sort`, `direction`.
- `weekly-programs` : `search`, `day_of_week`, `active`, `sort`, `direction`.

Les suppressions des eglises et groupes suppriment leurs responsables via les contraintes DB. Les suppressions d'eglises, responsables administratifs, groupes et evenements detachent les medias lies avant suppression. Les evenements conservent `event_category_id = null` si leur categorie est supprimee.

### Admin DOSC

Les CRUD admin DOSC sont sous `/api/v1/admin/dosc` et suivent le meme contrat REST :

- `GET /api/v1/admin/dosc/{resource}`
- `GET /api/v1/admin/dosc/{resource}/{id}`
- `POST /api/v1/admin/dosc/{resource}`
- `PUT/PATCH /api/v1/admin/dosc/{resource}/{id}`
- `DELETE /api/v1/admin/dosc/{resource}/{id}`

Ressources disponibles en Phase 4C :

- `projects`
- `actions`
- `action-stats`
- `testimonials`
- `impact-stats`

Permissions :

- Projets sociaux : `dosc.projects.view`, `dosc.projects.create`, `dosc.projects.update`, `dosc.projects.delete`, `dosc.manage` ou role `super_admin`.
- Actions sociales, statistiques d'action et temoignages : `dosc.actions.view`, `dosc.actions.create`, `dosc.actions.update`, `dosc.actions.delete`, `dosc.manage` ou role `super_admin`.
- Statistiques d'impact globales : `dosc.manage` ou role `super_admin`.

Filtres principaux :

- `projects` : `search`, `status`, `featured`, `deadline_from`, `deadline_to`, `sort`, `direction`.
- `actions` : `search`, `social_project_id`, `category`, `location`, `status`, `from`, `to`, `sort`, `direction`.
- `action-stats` : `search`, `social_action_id`, `sort`, `direction`.
- `testimonials` : `search`, `social_action_id`, `social_project_id`, `published`, `direction`.
- `impact-stats` : `search`, `active`, `sort`, `direction`.

Statuts admin acceptes :

- Projets sociaux : `draft`, `active`, `archived`.
- Actions sociales : `draft`, `published`, `archived`.

La suppression d'un projet detache ses medias et laisse les actions liees avec `social_project_id = null`. La suppression d'une action detache ses medias, supprime ses statistiques via cascade DB et laisse les temoignages avec `social_action_id = null`.

### Admin Dons

Les CRUD admin Dons sont sous `/api/v1/admin` :

- `donation-campaigns`
- `donation-methods`
- `donations`

Contrat REST :

- `GET /api/v1/admin/{resource}`
- `GET /api/v1/admin/{resource}/{id}`
- `POST /api/v1/admin/{resource}`
- `PUT/PATCH /api/v1/admin/{resource}/{id}`
- `DELETE /api/v1/admin/{resource}/{id}`

Permissions :

- Lecture : `donations.view`, `donations.manage` ou role `super_admin`.
- Creation, modification et suppression : `donations.manage` ou role `super_admin`.

Filtres principaux :

- `donation-campaigns` : `search`, `social_project_id`, `active`, `from`, `to`, `sort`, `direction`.
- `donation-methods` : `search`, `active`, `type`, `provider`, `sort`, `direction`.
- `donations` : `search`, `donation_campaign_id`, `donation_method_id`, `social_project_id`, `status`, `anonymous`, `from`, `to`, `paid_from`, `paid_to`, `sort`, `direction`.

Statuts admin acceptes pour les donations declarees : `pending`, `paid`, `failed`, `cancelled`, `refunded`.

Types de methodes de don acceptes : `mobile_money`, `bank_transfer`, `cash`, `other`.

Cette API ne fait aucun paiement reel, aucune verification operateur et aucun webhook. Elle enregistre uniquement des campagnes, methodes de paiement et donations declarees. Les donations restent privees et ne sont jamais exposees dans les endpoints publics.

La suppression d'une campagne ou d'une methode de don conserve les donations existantes avec `donation_campaign_id = null` ou `donation_method_id = null`, selon la contrainte DB.

### Admin Communication

Les CRUD admin Communication sont sous `/api/v1/admin` :

- `contact-messages`
- `newsletter-subscribers`

Contrat REST :

- `GET /api/v1/admin/{resource}`
- `GET /api/v1/admin/{resource}/{id}`
- `POST /api/v1/admin/{resource}`
- `PUT/PATCH /api/v1/admin/{resource}/{id}`
- `DELETE /api/v1/admin/{resource}/{id}`

Permission : `communication.manage` ou role `super_admin`.

Filtres principaux :

- `contact-messages` : `search`, `status`, `from`, `to`, `sort`, `direction`.
- `newsletter-subscribers` : `search`, `status`, `from`, `to`, `sort`, `direction`.

Statuts acceptes :

- Messages de contact : `new`, `read`, `answered`, `archived`.
- Abonnes newsletter : `subscribed`, `unsubscribed`.

Les creations publiques declenchent des events et des listeners queueables pour les emails transactionnels et notifications admin. Les CRUD admin restent reserves a la gestion des enregistrements stockes en base et n'integrent aucun fournisseur marketing.

### Admin Notifications

Routes :

- `GET /api/v1/admin/notifications`
- `GET /api/v1/admin/notifications/unread`
- `PATCH /api/v1/admin/notifications/{id}/read`
- `DELETE /api/v1/admin/notifications/{id}`

Permission : `notifications.view`.

Chaque utilisateur admin consulte uniquement ses propres notifications. `PATCH read` est idempotent.

### Admin Configuration

Le CRUD admin Configuration est sous `/api/v1/admin/site-settings`.

Contrat REST :

- `GET /api/v1/admin/site-settings`
- `GET /api/v1/admin/site-settings/{id}`
- `POST /api/v1/admin/site-settings`
- `PUT/PATCH /api/v1/admin/site-settings/{id}`
- `DELETE /api/v1/admin/site-settings/{id}`

Permission : `settings.manage` ou role `super_admin`.

Filtres principaux :

- `search`
- `type`
- `group`
- `sort`, valeurs : `key`, `type`, `group`, `created_at`, `updated_at`
- `direction`, valeurs : `asc`, `desc`

Types acceptes : `string`, `text`, `integer`, `float`, `boolean`, `json`, `url`, `email`.

Les cles doivent rester en minuscules avec lettres, chiffres, tirets, underscores et points, par exemple `site.name` ou `contact.email`. Cette API ne modifie pas `.env`, ne stocke pas de secrets et ne gere pas les credentials.

### Admin Utilisateurs, Roles Et Permissions

Les CRUD admin RBAC sont sous `/api/v1/admin` :

- `users`
- `roles`
- `permissions`

Contrat REST :

- `GET /api/v1/admin/{resource}`
- `GET /api/v1/admin/{resource}/{id}`
- `POST /api/v1/admin/{resource}`
- `PUT/PATCH /api/v1/admin/{resource}/{id}`
- `DELETE /api/v1/admin/{resource}/{id}`

Permissions :

- Utilisateurs : `users.view`, `users.create`, `users.update`, `users.delete`, `users.manage` ou role `super_admin`.
- Roles et permissions : `roles.manage` ou role `super_admin`.

Filtres principaux :

- `users` : `search`, `status`, `role_id`, `permission_id`, `sort`, `direction`.
- `roles` : `search`, `permission_id`, `sort`, `direction`.
- `permissions` : `search`, `sort`, `direction`.

Champs sensibles :

- Les reponses admin utilisateurs ne retournent jamais `password`, `remember_token` ni tokens Sanctum.
- Le champ `password` est accepte uniquement en creation ou mise a jour. En mise a jour, `password: null` laisse le mot de passe existant inchange.
- La suppression d'un utilisateur supprime aussi ses tokens Sanctum.

Les relations sont synchronisees via `role_ids` pour les utilisateurs et `permission_ids` pour les roles.

### Admin Media

La bibliotheque media admin est sous `/api/v1/admin/media`.

Routes :

- `GET /api/v1/admin/media`
- `GET /api/v1/admin/media/{id}`
- `POST /api/v1/admin/media`
- `PATCH /api/v1/admin/media/{id}`
- `DELETE /api/v1/admin/media/{id}`

Permissions :

- `media.view` ou `media.manage` pour lister et consulter.
- `media.upload` ou `media.manage` pour uploader.
- `media.update` ou `media.manage` pour modifier les metadonnees.
- `media.delete` ou `media.manage` pour supprimer.

`POST /api/v1/admin/media` utilise le rate limiter `media-upload`.

Upload `multipart/form-data` :

- `file`, requis
- `alt_text`
- `title`
- `description`
- `mediaable_type`
- `mediaable_id`

Filtres : `search`, `mime_type`, `file_type`, `uploaded_by`, `from`, `to`, `orphaned`.

Tri : `created_at`, `file_name`, `size`, `mime_type`.

Les URLs sont generees par `Storage::disk(...)->url(...)`. Aucun endpoint public ne liste automatiquement toute la bibliotheque media.

## DOSC

Les endpoints DOSC sont publics, en lecture seule, et namespaces sous `/api/v1/dosc`. Aucun endpoint de donation individuelle, paiement reel, webhook ou transaction n'est expose.

### GET /api/v1/dosc/projects

Liste paginee des projets sociaux publics. Le statut public retenu depuis l'existant est `status=active`.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `title`, `short_description`, `description`
- `status`, valeur publique autorisee : `active`
- `featured`
- `deadline_from`, date valide
- `deadline_to`, date valide superieure ou egale a `deadline_from`
- `sort`, valeurs autorisees : `title`, `deadline`, `created_at`, `beneficiaries_count`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/dosc/projects/{slug}

Detail d'un projet `active`. Charge uniquement les relations utiles au detail :

- `actions` avec `status=published`
- `donation_campaigns` actives et en cours
- `media`

### GET /api/v1/dosc/actions

Liste paginee des actions sociales publiques. Les actions exposees ont `status=published` et, si elles sont liees a un projet, ce projet doit etre `active`.

Parametres :

- `page`
- `per_page`
- `search`, recherche dans `title`, `description`, `category`, `location`
- `social_project_id`
- `category`
- `location`
- `status`, valeur publique autorisee : `published`
- `from`, date valide filtrant `action_date >= from`
- `to`, date valide superieure ou egale a `from`, filtrant `action_date <= to`
- `sort`, valeurs autorisees : `action_date`, `title`, `created_at`, `beneficiaries_count`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/dosc/actions/{slug}

Detail d'une action `published`. Charge :

- `project`, representation minimale
- `stats`
- `testimonials` publies
- `media`

Les statistiques d'action sont integrees au detail pour eviter un endpoint supplementaire tant que le frontend detail a besoin de les afficher avec l'action.

### GET /api/v1/dosc/impact-stats

Liste non paginee des statistiques globales actives, triees par `sort_order asc`.

Champs publics : `id`, `label`, `value`, `suffix`, `icon`, `sort_order`.

### GET /api/v1/dosc/testimonials

Liste paginee des temoignages publies.

Parametres :

- `page`
- `per_page`
- `social_action_id`
- `social_project_id`, resolu via l'action sociale liee
- `sort`, valeur autorisee : `created_at`
- `direction`, valeurs : `asc`, `desc`

Les temoignages anonymes restent anonymes : si `name` est `null`, l'API retourne `name: null` et ne deduit aucune identite.

### GET /api/v1/dosc/donation-campaigns

Liste paginee des campagnes de dons publiques. Une campagne publique doit etre :

- `active = true`
- `start_date` vide ou inferieure/egale a aujourd'hui
- `end_date` vide ou superieure/egale a aujourd'hui
- liee a aucun projet ou a un projet `active`

Parametres :

- `page`
- `per_page`
- `social_project_id`
- `active`; `false` retourne une liste vide car les campagnes inactives ne sont pas publiques
- `from` et `to`, dates valides utilisees comme filtre de chevauchement de periode : campagne non terminee avant `from`, campagne non commencee apres `to`
- `sort`, valeurs autorisees : `start_date`, `end_date`, `created_at`, `title`
- `direction`, valeurs : `asc`, `desc`

### GET /api/v1/dosc/donation-campaigns/{id}

Detail d'une campagne de dons publique. Charge `project` sous forme minimale.

### GET /api/v1/dosc/donation-methods

Liste non paginee des methodes de don actives.

Champs publics : `id`, `name`, `type`, `provider`, `account_name`, `account_number`, `instructions`.

Types conserves tels que stockes : `mobile_money`, `bank_transfer`, `cash`, `other`.

## Mapping Frontend DOSC

Le frontend social existant utilise surtout des contenus statiques autour des orientations DOSC. L'API fournit les champs necessaires pour remplacer ces donnees progressivement :

- Frontend `project.image` -> API `data.image`
- Frontend `project.goal` -> API `data.goal_amount`
- Frontend `project.raised` -> API `data.raised_amount`
- Frontend `project.beneficiaries` -> API `data.beneficiaries_count`
- Frontend `action.date` -> API `data.action_date`
- Frontend `action.youtubeId` -> API `data.youtube_video_id`
- Frontend boutons de don -> API `donation_methods` et `donation_campaigns`

Les medias utilisent la Resource existante : `id`, `file_name`, `file_path`, `file_type`, `mime_type`, `alt_text`, `title`, `description`.

## Donnees Non Publiques

Les routes publiques ne retournent jamais les donations individuelles ni leurs champs sensibles : `donor_name`, `donor_email`, `donor_phone`, `transaction_reference`, `amount`, `paid_at`. Elles n'exposent pas non plus `password`, `remember_token`, secrets, tokens ou credentials.

## Erreurs

Les endpoints API retournent JSON pour les erreurs HTTP.

Exemple `404 Not Found` :

```json
{
  "message": "Resource not found."
}
```

Exemple `422 Unprocessable Entity` :

```json
{
  "message": "Validation failed.",
  "errors": {
    "title": [
      "The title field is required."
    ]
  }
}
```

## Pagination

Les futurs endpoints de collection utiliseront la pagination Laravel.

Parametres standards :

- `page`
- `per_page`
- `search`
- `sort`
- `direction`

Valeurs par defaut :

- `API_DEFAULT_PER_PAGE=20`
- `API_MAX_PER_PAGE=100`

## Tri

Le tri dynamique doit toujours passer par une whitelist de colonnes autorisees. Les colonnes non autorisees sont ignorees au profit du tri par defaut.

## CORS

Les origines autorisees sont configurees dans `.env`. `CORS_ALLOWED_ORIGINS` peut surcharger la liste complete, mais ne doit pas valoir `*` en production.

## Rate Limiting

Le rate limiter API utilise `API_RATE_LIMIT_PER_MINUTE`, avec `120` requetes par minute par defaut.
