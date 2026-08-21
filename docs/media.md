# Gestion Des Medias EMEC

## Architecture

La Phase 5 centralise la gestion des fichiers dans Laravel :

```text
Frontends EMEC / Messages / DOSC
        |
        v
Laravel API
        |
        v
MediaService
        |
        v
Laravel Filesystem
        |
        v
media + mediaables
```

Les frontends n'ont pas de bibliotheque media publique globale. Ils consomment les URLs retournees dans les Resources des entites ou dans l'API admin.

## Tables

`media` contient les metadonnees :

- `file_name`
- `file_path`
- `file_type`
- `mime_type`
- `alt_text`
- `title`
- `description`
- `size`
- `uploaded_by`

`mediaables` relie un media a une ou plusieurs entites via pivot polymorphique :

- `media_id`
- `mediaable_type`
- `mediaable_id`

Les migrations existantes sont conservees. Aucune table media n'est recreee en Phase 5.

## Filesystem

Le stockage utilise `Storage` et le disk configure par :

```text
MEDIA_DISK=public
```

Par defaut, le disk `public` stocke les fichiers dans `storage/app/public` et expose les URLs via `/storage`. En local, executer :

```bash
php artisan storage:link
```

Aucun credential cloud n'est requis. L'architecture reste compatible avec un futur disk S3/R2 via configuration.

## Upload

Endpoint admin :

```text
POST /api/v1/admin/media
Content-Type: multipart/form-data
```

Champs :

- `file`, requis
- `alt_text`
- `title`
- `description`
- `mediaable_type`, optionnel
- `mediaable_id`, requis si `mediaable_type` est fourni

Les fichiers sont stockes sous :

```text
media/YYYY/MM/{uuid}.{extension}
```

Le chemin physique ne depend jamais du nom original, d'un slug, d'un titre ou d'une entree utilisateur.

## Validation

Extensions autorisees :

- `jpg`
- `jpeg`
- `png`
- `webp`
- `pdf`

MIME types autorises :

- `image/jpeg`
- `image/png`
- `image/webp`
- `application/pdf`

Types metier :

- `image`
- `document`

Limites configurees :

```text
MEDIA_MAX_IMAGE_KB=10240
MEDIA_MAX_DOCUMENT_KB=20480
```

Les executables, scripts, HTML et SVG ne sont pas autorises dans cette phase. Les noms contenant une extension executable avant l'extension finale sont egalement rejetes, par exemple `shell.php.jpg`, ainsi que les fichiers serveur sensibles comme `.htaccess`.

## Permissions

Routes protegees par `auth:sanctum`.

- `media.view` : lister et consulter.
- `media.upload` : uploader.
- `media.update` : modifier les metadonnees.
- `media.delete` : supprimer.
- `media.manage` : acces complet historique.

Le role `media_manager` possede l'acces complet.

## API Admin

```text
GET    /api/v1/admin/media
GET    /api/v1/admin/media/{id}
POST   /api/v1/admin/media
PATCH  /api/v1/admin/media/{id}
DELETE /api/v1/admin/media/{id}
```

Filtres :

- `search`
- `mime_type`
- `file_type`
- `uploaded_by`
- `from`
- `to`
- `orphaned=1`

Tri autorise :

- `created_at`
- `file_name`
- `size`
- `mime_type`

## Suppression

La suppression :

1. verifie la permission ;
2. detache les relations `mediaables` ;
3. supprime l'enregistrement `media` ;
4. supprime le fichier physique avec `Storage` si aucun autre enregistrement media ne reference le meme chemin.

Les medias orphelins peuvent etre listes avec :

```text
GET /api/v1/admin/media?orphaned=1
```

Aucune suppression automatique d'orphelins n'est faite.

## Polymorphisme

Types attachables :

- `church`
- `group`
- `event`
- `administrative_leader`
- `church_leader`
- `group_leader`
- `preacher`
- `message`
- `social_project`
- `social_action`
- `testimonial`
- `user`

## Mapping Legacy

Les champs existants restent en place pour migration progressive :

- `Church.image` -> media `image`
- `Group.image` -> media `image`
- `Event.image` -> media `image`
- `AdministrativeLeader.image` -> media `image`
- `ChurchLeader.image` -> media `image`
- `GroupLeader.image` -> media `image`
- `Preacher.image` -> media `image`
- `Message.thumbnail` -> media `image`
- `Message.pdf_url` -> media `document`
- `Message.audio_url` -> futur media `document` ou `audio` si ce type est ajoute plus tard
- `MessageSeries.cover_image` -> media `image`
- `SocialProject.image` -> media `image`
- `SocialAction.image` -> media `image`
- `Testimonial.avatar` -> media `image`
- `User.avatar` -> media `image`

Aucun champ legacy n'est supprime automatiquement en Phase 5.
