# EMEC Authorization

## Strategie

L'API privee utilise Laravel Sanctum pour l'authentification par token Bearer et les mecanismes Laravel `Gate` / `Policy` pour l'autorisation.

Le role `super_admin` est centralise via `Gate::before` et obtient l'acces a toutes les permissions admin sans condition dupliquee dans les controleurs.

## Roles

- `super_admin` : acces complet a tous les modules d'administration.
- `admin` : administration generale, incluant contenus, DOSC, medias, finance, communication, settings et utilisateurs.
- `editor` : edition des contenus publics EMEC, evenements, messages et medias, sans droits utilisateurs ni finance.
- `messages_editor` : gestion du module Messages EMEC et consultation media.
- `dosc_editor` : gestion des contenus DOSC et consultation media.
- `media_manager` : gestion de la bibliotheque media.
- `finance_manager` : consultation et gestion des dons.

## Matrice

```text
Role                 EMEC   Events   Messages   DOSC   Medias   Finance   Users   Notifs
----------------------------------------------------------------------------------------
super_admin           yes     yes       yes      yes     yes       yes      yes      yes
admin                 yes     yes       yes      yes     yes       yes      yes      yes
editor                yes     yes       yes       no     yes        no       no       no
messages_editor        no      no       yes       no     view       no       no       no
dosc_editor            no      no        no      yes     view       no       no       no
media_manager          no      no        no       no     yes        no       no       no
finance_manager        no      no        no       no      no       yes       no      yes
```

## Permissions EMEC

- `churches.view` : consulter les eglises, responsables d'eglise et responsables administratifs en administration.
- `churches.create` : creer ces ressources institutionnelles.
- `churches.update` : les modifier.
- `churches.delete` : les supprimer.
- `churches.manage` : permission historique conservee, donne acces complet au sous-module institutionnel EMEC.
- `groups.view` : consulter les groupes et responsables de groupe en administration.
- `groups.create` : creer les groupes et responsables.
- `groups.update` : les modifier.
- `groups.delete` : les supprimer.
- `groups.manage` : permission historique conservee, donne acces complet au sous-module groupes.
- `events.view` : consulter les categories d'evenements, evenements et programmes hebdomadaires.
- `events.create` : creer ces ressources.
- `events.update` : les modifier.
- `events.delete` : les supprimer.
- `events.manage` : permission historique conservee, donne acces complet au sous-module evenements.

Ces permissions couvrent la Phase 4B :

- `AdminChurchController`
- `AdminChurchLeaderController`
- `AdminAdministrativeLeaderController`
- `AdminGroupController`
- `AdminGroupLeaderController`
- `AdminEventCategoryController`
- `AdminEventController`
- `AdminWeeklyProgramController`

Policies associees :

- `ChurchPolicy`
- `ChurchLeaderPolicy`
- `AdministrativeLeaderPolicy`
- `GroupPolicy`
- `GroupLeaderPolicy`
- `EventCategoryPolicy`
- `EventPolicy`
- `WeeklyProgramPolicy`

## Permissions Messages

- `messages.view` : consulter les messages admin.
- `messages.create` : creer un message.
- `messages.update` : modifier un message.
- `messages.delete` : supprimer un message.
- `messages.publish` : publier ou de-publier un message.
- `messages.manage` : permission historique conservee, donne acces complet au module Messages.

Ces permissions couvrent la Phase 4A :

- `AdminMessageController`
- `AdminPreacherController`
- `AdminMessageCategoryController`
- `AdminMessageSeriesController`

Policies associees :

- `MessagePolicy`
- `PreacherPolicy`
- `MessageCategoryPolicy`
- `MessageSeriesPolicy`

## Permissions DOSC

- `dosc.projects.view`
- `dosc.projects.create`
- `dosc.projects.update`
- `dosc.projects.delete`
- `dosc.actions.view`
- `dosc.actions.create`
- `dosc.actions.update`
- `dosc.actions.delete`
- `dosc.manage` : permission historique conservee, donne acces au module DOSC.

Ces permissions couvrent la Phase 4C :

- `AdminSocialProjectController`
- `AdminSocialActionController`
- `AdminSocialActionStatController`
- `AdminTestimonialController`
- `AdminImpactStatController`

Policies associees :

- `SocialProjectPolicy`
- `SocialActionPolicy`
- `SocialActionStatPolicy`
- `TestimonialPolicy`
- `ImpactStatPolicy`

## Permissions Medias

- `media.view`
- `media.upload`
- `media.update`
- `media.delete`
- `media.manage` : permission historique conservee.

Ces permissions couvrent la Phase 5 :

- `AdminMediaController`

Policy associee :

- `MediaPolicy`

`media.manage` donne acces complet. `media.view`, `media.upload`, `media.update` et `media.delete` permettent une separation fine lecture/upload/metadonnees/suppression.

## Permissions Finance

- `donations.view`
- `donations.manage`

Ces permissions couvrent la Phase 4D :

- `AdminDonationCampaignController`
- `AdminDonationMethodController`
- `AdminDonationController`

Policies associees :

- `DonationCampaignPolicy`
- `DonationMethodPolicy`
- `DonationPolicy`

`donations.view` donne uniquement acces a la lecture. `donations.manage` donne acces complet aux campagnes, methodes et donations declarees. Aucun paiement reel ni webhook n'est autorise par ces permissions.

## Permissions Users

- `users.view`
- `users.create`
- `users.update`
- `users.delete`
- `users.manage` : permission historique conservee.
- `roles.manage`

Ces permissions couvrent la Phase 4G :

- `AdminUserController`
- `AdminRoleController`
- `AdminPermissionController`

Policies associees :

- `UserPolicy`
- `RolePolicy`
- `PermissionPolicy`

`users.view/create/update/delete` et `users.manage` concernent les comptes administrateurs. `roles.manage` donne acces complet aux roles et permissions. Les mots de passe et tokens ne sont jamais retournes dans les Resources admin.

## Permissions Communication

- `communication.manage`
- `notifications.view`

Ces permissions couvrent la Phase 4E et la Phase 7 :

- `AdminContactMessageController`
- `AdminNewsletterSubscriberController`
- `AdminNotificationController`

Policies associees :

- `ContactMessagePolicy`
- `NewsletterSubscriberPolicy`

`communication.manage` donne acces complet aux messages de contact et aux abonnes newsletter. `notifications.view` donne acces aux notifications administratives de l'utilisateur connecte. Les notifications ne permettent pas de lire celles d'un autre utilisateur.

## Permissions Configuration

- `settings.manage`

Cette permission couvre la Phase 4F :

- `AdminSiteSettingController`

Policy associee :

- `SiteSettingPolicy`

Elle donne acces complet aux reglages applicatifs stockes en base. Les secrets, credentials et variables `.env` restent hors perimetre.

## Regles Phase 3 et 4

Les CRUD complets actuellement implementes cote admin sont :

- Phase 3 : Messages.
- Phase 4A : predicateurs, categories de messages et series de messages.
- Phase 4B : eglises, responsables, groupes, categories d'evenements, evenements et programmes hebdomadaires.
- Phase 4C : projets sociaux DOSC, actions sociales, statistiques d'action, temoignages et statistiques d'impact.
- Phase 4D : campagnes de dons, methodes de don et donations declarees.
- Phase 4E : messages de contact et abonnes newsletter.
- Phase 4F : reglages applicatifs `site_settings`.
- Phase 4G : utilisateurs, roles et permissions.

Un utilisateur `inactive` ne peut pas s'authentifier. Les reponses publiques et privees ne doivent jamais exposer `password`, `remember_token`, tokens bruts deja emis, secrets, credentials, ni donnees de donations individuelles.
