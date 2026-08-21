# Database EMEC

## Conventions

- MySQL 8+ avec `utf8mb4_unicode_ci`.
- Toutes les tables utilisent `$table->id()` et `timestamps()`.
- Les statuts sont des chaines controlees par Laravel, jamais des ENUM MySQL.
- Les montants utilisent toujours `decimal(15, 2)`.
- `cascadeOnDelete()` est reserve aux pivots et aux donnees enfants sans valeur historique autonome.
- `nullOnDelete()` est utilise lorsqu'une suppression parent ne doit pas effacer l'historique.
- `restrictOnDelete()` ou l'absence de cascade est utilise quand la suppression parent doit etre empechee par les donnees liees.
- Convention `weekly_programs.day_of_week` : entier de `1` a `7`, avec `1 = lundi` et `7 = dimanche`.
- Ne pas creer de table `official_documents`.

## Ordre Des Migrations

1. `users`
2. `roles`
3. `permissions`
4. `role_user`
5. `permission_role`
6. `churches`
7. `church_leaders`
8. `administrative_leaders`
9. `groups`
10. `group_leaders`
11. `event_categories`
12. `events`
13. `weekly_programs`
14. `preachers`
15. `message_categories`
16. `message_series`
17. `messages`
18. `social_projects`
19. `social_actions`
20. `social_action_stats`
21. `testimonials`
22. `impact_stats`
23. `donation_campaigns`
24. `donation_methods`
25. `donations`
26. `media`
27. `mediaables`
28. `contact_messages`
29. `newsletter_subscribers`
30. `site_settings`

## Administration

### users

Role : comptes administratifs Laravel.

Champs : `id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `avatar`, `status`, `remember_token`, `created_at`, `updated_at`.

Contraintes et index :

- `email` unique.
- `status` indexe.
- Statuts : `active`, `inactive`.

Relations :

- `users` N-N `roles` via `role_user`.
- `users` 1-N `media` via `media.uploaded_by`.

### roles

Role : profils d'acces.

Champs : `id`, `name`, `slug`, `description`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `roles` N-N `users`.
- `roles` N-N `permissions`.

Roles initiaux :

- `super_admin`
- `admin`
- `editor`
- `messages_editor`
- `dosc_editor`
- `media_manager`
- `finance_manager`

### permissions

Role : actions autorisees par module.

Champs : `id`, `name`, `slug`, `description`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `permissions` N-N `roles`.

### role_user

Role : pivot utilisateurs-roles.

Champs : `user_id`, `role_id`.

Contraintes :

- FK `user_id` vers `users.id` avec `cascadeOnDelete()`.
- FK `role_id` vers `roles.id` avec `cascadeOnDelete()`.
- Unique composite `user_id`, `role_id`.

### permission_role

Role : pivot roles-permissions.

Champs : `permission_id`, `role_id`.

Contraintes :

- FK `permission_id` vers `permissions.id` avec `cascadeOnDelete()`.
- FK `role_id` vers `roles.id` avec `cascadeOnDelete()`.
- Unique composite `permission_id`, `role_id`.

## EMEC

### churches

Role : assemblees locales et points d'implantation EMEC.

Champs : `id`, `name`, `slug`, `baptism_name`, `city`, `address`, `neighborhood`, `locality`, `sector`, `district`, `circumscription`, `mission_field`, `region`, `description`, `pastor_vision`, `contact`, `map_url`, `image`, `status`, `active`, `created_at`, `updated_at`.

Contraintes et index :

- `slug` unique.
- Index : `city`, `region`, `status`, `active`.
- Statuts proposes : `draft`, `published`, `archived`.

Relations :

- `churches` 1-N `church_leaders`.
- `churches` morph N-N `media` via `mediaables`.

### church_leaders

Role : responsables attaches a une eglise locale.

Champs : `id`, `church_id`, `name`, `responsibility`, `image`, `start_date`, `end_date`, `active`, `created_at`, `updated_at`.

Contraintes :

- FK `church_id` vers `churches.id` avec `cascadeOnDelete()`.

Relations :

- `church_leaders` N-1 `churches`.

### administrative_leaders

Role : responsables administratifs institutionnels.

Champs : `id`, `name`, `responsibility`, `description`, `image`, `start_date`, `end_date`, `active`, `created_at`, `updated_at`.

Relations :

- `administrative_leaders` morph N-N `media` via `mediaables`.

### groups

Role : groupes, cellules ou departements d'activite EMEC.

Champs : `id`, `name`, `slug`, `description`, `short_description`, `image`, `color`, `contact`, `email`, `active`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `groups` 1-N `group_leaders`.
- `groups` morph N-N `media` via `mediaables`.

### group_leaders

Role : responsables de groupes.

Champs : `id`, `group_id`, `name`, `responsibility`, `image`, `active`, `created_at`, `updated_at`.

Contraintes :

- FK `group_id` vers `groups.id` avec `cascadeOnDelete()`.

Relations :

- `group_leaders` N-1 `groups`.

## Evenements

### event_categories

Role : classification des evenements.

Champs : `id`, `name`, `slug`, `description`, `active`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `event_categories` 1-N `events`.

### events

Role : agenda et evenements publics.

Champs : `id`, `event_category_id`, `title`, `slug`, `description`, `image`, `start_at`, `end_at`, `location`, `city`, `featured`, `status`, `created_at`, `updated_at`.

Contraintes et index :

- `slug` unique.
- FK nullable `event_category_id` vers `event_categories.id` avec `nullOnDelete()`.
- Index : `event_category_id`, `start_at`, `status`, `featured`, `city`.
- Statuts proposes : `draft`, `published`, `cancelled`, `archived`.

Relations :

- `events` N-1 `event_categories`.
- `events` morph N-N `media` via `mediaables`.

### weekly_programs

Role : programmes recurrents hebdomadaires.

Champs : `id`, `title`, `description`, `day_of_week`, `start_time`, `end_time`, `location`, `active`, `created_at`, `updated_at`.

Contraintes :

- `day_of_week` entier de `1` a `7`.
- Index : `day_of_week`, `active`.

## Messages

### preachers

Role : predicateurs.

Champs : `id`, `name`, `slug`, `role`, `bio`, `image`, `active`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `preachers` 1-N `messages`.
- `preachers` morph N-N `media` via `mediaables`.

### message_categories

Role : categories de messages.

Champs : `id`, `name`, `slug`, `description`, `active`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `message_categories` 1-N `messages`.

### message_series

Role : series de predications.

Champs : `id`, `name`, `slug`, `description`, `cover_image`, `active`, `created_at`, `updated_at`.

Contraintes :

- `slug` unique.

Relations :

- `message_series` 1-N `messages`.
- `message_series` morph N-N `media` via `mediaables`.

### messages

Role : predications et contenus audio/video.

Champs : `id`, `preacher_id`, `message_category_id`, `message_series_id`, `title`, `slug`, `excerpt`, `content`, `preached_at`, `duration`, `youtube_video_id`, `youtube_url`, `audio_url`, `pdf_url`, `thumbnail`, `featured`, `status`, `views`, `created_at`, `updated_at`.

Contraintes et index :

- `slug` unique.
- FKs nullable `preacher_id`, `message_category_id`, `message_series_id` avec `nullOnDelete()`.
- `views` unsigned integer, default `0`.
- Index : `preacher_id`, `message_category_id`, `message_series_id`, `preached_at`, `status`, `featured`.
- Statuts proposes : `draft`, `published`, `archived`.

Relations :

- `messages` N-1 `preachers`.
- `messages` N-1 `message_categories`.
- `messages` N-1 `message_series`.
- `messages` morph N-N `media` via `mediaables`.

## DOSC

### social_projects

Role : projets sociaux DOSC.

Champs : `id`, `title`, `slug`, `short_description`, `description`, `image`, `goal_amount`, `raised_amount`, `beneficiaries_count`, `deadline`, `status`, `featured`, `created_at`, `updated_at`.

Contraintes et index :

- `slug` unique.
- `goal_amount` nullable `decimal(15, 2)`.
- `raised_amount` `decimal(15, 2)` default `0`.
- Index : `status`, `featured`, `deadline`.
- Statuts proposes : `draft`, `active`, `completed`, `archived`.

Relations :

- `social_projects` 1-N `social_actions`.
- `social_projects` 1-N `donation_campaigns`.
- `social_projects` morph N-N `media` via `mediaables`.

### social_actions

Role : actions sociales realisees ou planifiees.

Champs : `id`, `social_project_id`, `title`, `slug`, `category`, `description`, `location`, `action_date`, `image`, `youtube_video_id`, `beneficiaries_count`, `status`, `created_at`, `updated_at`.

Contraintes et index :

- `slug` unique.
- FK nullable `social_project_id` vers `social_projects.id` avec `nullOnDelete()`.
- Index : `social_project_id`, `action_date`, `status`, `category`.
- Statuts proposes : `draft`, `published`, `archived`.

Relations :

- `social_actions` N-1 `social_projects`.
- `social_actions` 1-N `social_action_stats`.
- `social_actions` 1-N `testimonials`.
- `social_actions` morph N-N `media` via `mediaables`.

### social_action_stats

Role : statistiques rattachees a une action sociale.

Champs : `id`, `social_action_id`, `label`, `value`, `created_at`, `updated_at`.

Contraintes :

- FK `social_action_id` vers `social_actions.id` avec `cascadeOnDelete()`.

Relations :

- `social_action_stats` N-1 `social_actions`.

### testimonials

Role : temoignages publics ou anonymes.

Champs : `id`, `social_action_id`, `name`, `location`, `quote`, `avatar`, `published`, `created_at`, `updated_at`.

Contraintes :

- FK nullable `social_action_id` vers `social_actions.id` avec `nullOnDelete()`.
- Index : `published`.

Relations :

- `testimonials` N-1 `social_actions`.

### impact_stats

Role : chiffres globaux d'impact DOSC.

Champs : `id`, `label`, `value`, `suffix`, `icon`, `sort_order`, `active`, `created_at`, `updated_at`.

Contraintes :

- `sort_order` default `0`.
- Index : `active`, `sort_order`.

## Dons

### donation_campaigns

Role : campagnes de dons, possiblement rattachees a un projet social.

Champs : `id`, `social_project_id`, `title`, `description`, `goal_amount`, `active`, `start_date`, `end_date`, `created_at`, `updated_at`.

Contraintes :

- FK nullable `social_project_id` vers `social_projects.id` avec `nullOnDelete()`.
- `goal_amount` `decimal(15, 2)`.
- Index : `social_project_id`, `active`, `start_date`, `end_date`.

Relations :

- `donation_campaigns` N-1 `social_projects`.
- `donation_campaigns` 1-N `donations`.

### donation_methods

Role : moyens de don declares sans integration de paiement reelle.

Champs : `id`, `name`, `type`, `provider`, `account_name`, `account_number`, `instructions`, `active`, `created_at`, `updated_at`.

Contraintes :

- Index : `type`, `provider`, `active`.
- Types proposes : `mobile_money`, `bank_transfer`, `cash`, `other`.

Relations :

- `donation_methods` 1-N `donations`.

### donations

Role : intentions ou traces de dons declarees.

Champs : `id`, `donation_campaign_id`, `donation_method_id`, `donor_name`, `donor_email`, `donor_phone`, `amount`, `currency`, `transaction_reference`, `status`, `anonymous`, `paid_at`, `created_at`, `updated_at`.

Contraintes et index :

- `amount` `decimal(15, 2)`.
- FK nullable `donation_campaign_id` vers `donation_campaigns.id` avec `nullOnDelete()`.
- FK nullable `donation_method_id` vers `donation_methods.id` avec `nullOnDelete()`.
- `transaction_reference` unique nullable.
- Index : `donation_campaign_id`, `donation_method_id`, `status`, `paid_at`.
- Statuts proposes : `pending`, `successful`, `failed`, `cancelled`.

Relations :

- `donations` N-1 `donation_campaigns`.
- `donations` N-1 `donation_methods`.

## Medias

### media

Role : fichiers centralises.

Champs : `id`, `file_name`, `file_path`, `file_type`, `mime_type`, `alt_text`, `title`, `description`, `size`, `uploaded_by`, `created_at`, `updated_at`.

Contraintes :

- FK nullable `uploaded_by` vers `users.id` avec `nullOnDelete()`.
- Index : `file_type`, `mime_type`, `uploaded_by`.

Relations :

- `media` N-1 `users` via `uploaded_by`.
- `media` morph N-N entites via `mediaables`.

### mediaables

Role : pivot polymorphique entre medias et entites.

Champs : `media_id`, `mediaable_type`, `mediaable_id`.

Contraintes :

- FK `media_id` vers `media.id` avec `cascadeOnDelete()`.
- Index composite : `mediaable_type`, `mediaable_id`.
- Unique composite : `media_id`, `mediaable_type`, `mediaable_id`.

## Communication

### contact_messages

Role : messages entrants depuis les formulaires publics.

Champs : `id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `read_at`, `answered_at`, `created_at`, `updated_at`.

Contraintes :

- Index : `status`, `read_at`, `answered_at`.
- Statuts proposes : `new`, `read`, `answered`, `archived`.

### newsletter_subscribers

Role : abonnes newsletter.

Champs : `id`, `name`, `email`, `status`, `subscribed_at`, `unsubscribed_at`, `created_at`, `updated_at`.

Contraintes :

- `email` unique.
- Index : `status`, `subscribed_at`.
- Statuts proposes : `subscribed`, `unsubscribed`, `bounced`.

## Configuration

### site_settings

Role : reglages cle-valeur pour les sites.

Champs : `id`, `key`, `value`, `type`, `group`, `created_at`, `updated_at`.

Contraintes :

- `key` unique.
- Index : `group`, `type`.
- Types proposes : `string`, `text`, `boolean`, `integer`, `decimal`, `json`, `url`.

Exemples de cles :

- `contact_phone`
- `contact_email`
- `address`
- `facebook_url`
- `youtube_url`
- `whatsapp`
- `orange_money`
- `mtn_money`

## Controle Qualite Pour E3-E8

- Chaque migration doit etre separee.
- Chaque FK doit etre declaree explicitement.
- Chaque montant doit etre en `decimal(15, 2)`.
- Chaque statut doit etre une chaine indexee lorsque filtree.
- Chaque relation Eloquent doit avoir son inverse lorsque pertinent.
- Chaque cast doit etre ajoute sur les booleens, dates, datetimes, entiers et decimaux.
- `php artisan migrate:fresh --seed` devra passer sur MySQL a la fin du socle DB.
