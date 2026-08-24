<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Admin Core", description="Profil, dashboard, notifications et medias admin")
 *
 * @OA\Schema(
 *     schema="AdminRole",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Messages Editor"),
 *     @OA\Property(property="slug", type="string", example="messages_editor")
 * )
 *
 * @OA\Schema(
 *     schema="AdminPermission",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="View messages"),
 *     @OA\Property(property="slug", type="string", example="messages.view")
 * )
 *
 * @OA\Schema(
 *     schema="AuthenticatedUser",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Admin EMEC"),
 *     @OA\Property(property="email", type="string", format="email", example="admin@egliseemec.org"),
 *     @OA\Property(property="phone", type="string", nullable=true, example="+237699000000"),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://api.egliseemec.org/storage/users/avatar.jpg"),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/AdminRole")),
 *     @OA\Property(property="permissions", type="array", @OA\Items(ref="#/components/schemas/AdminPermission"))
 * )
 *
 * @OA\Schema(
 *     schema="AdminDashboard",
 *     type="object",
 *     @OA\Property(property="messages_count", type="integer", example=12),
 *     @OA\Property(
 *         property="latest_messages",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Vivre par la foi"),
 *             @OA\Property(property="status", type="string", example="published"),
 *             @OA\Property(property="views", type="integer", example=120),
 *             @OA\Property(property="preached_at", type="string", format="date", nullable=true, example="2026-08-24"),
 *             @OA\Property(property="preacher_name", type="string", nullable=true, example="Pasteur Alpha")
 *         )
 *     ),
 *     @OA\Property(
 *         property="dashboard_preachers",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="name", type="string", example="Pasteur Alpha"),
 *             @OA\Property(property="role", type="string", nullable=true, example="Predicateur"),
 *             @OA\Property(property="active", type="boolean", example=true),
 *             @OA\Property(property="messages_count", type="integer", example=8)
 *         )
 *     ),
 *     @OA\Property(
 *         property="daily_message_views",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="date", type="string", format="date", example="2026-08-24"),
 *             @OA\Property(property="label", type="string", example="24/08"),
 *             @OA\Property(property="value", type="integer", example=120)
 *         )
 *     ),
 *     @OA\Property(
 *         property="daily_paid_donations",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="date", type="string", format="date", example="2026-08-24"),
 *             @OA\Property(property="label", type="string", example="24/08"),
 *             @OA\Property(property="value", type="number", format="float", example=50000)
 *         )
 *     ),
 *     @OA\Property(property="published_messages_count", type="integer", example=9),
 *     @OA\Property(property="draft_messages_count", type="integer", example=3),
 *     @OA\Property(property="featured_messages_count", type="integer", example=2),
 *     @OA\Property(property="preachings_count", type="integer", example=10),
 *     @OA\Property(property="main_site_publications_count", type="integer", example=21),
 *     @OA\Property(property="preachers_count", type="integer", example=4),
 *     @OA\Property(property="message_categories_count", type="integer", example=5),
 *     @OA\Property(property="message_series_count", type="integer", example=3),
 *     @OA\Property(property="events_count", type="integer", example=4),
 *     @OA\Property(property="published_events_count", type="integer", example=3),
 *     @OA\Property(property="upcoming_events_count", type="integer", example=2),
 *     @OA\Property(property="weekly_programs_count", type="integer", example=4),
 *     @OA\Property(property="active_weekly_programs_count", type="integer", example=3),
 *     @OA\Property(property="churches_count", type="integer", example=2),
 *     @OA\Property(property="active_churches_count", type="integer", example=2),
 *     @OA\Property(property="published_churches_count", type="integer", example=1),
 *     @OA\Property(property="groups_count", type="integer", example=5),
 *     @OA\Property(property="active_groups_count", type="integer", example=4),
 *     @OA\Property(property="social_projects_count", type="integer", example=3),
 *     @OA\Property(property="active_social_projects_count", type="integer", example=2),
 *     @OA\Property(property="featured_social_projects_count", type="integer", example=1),
 *     @OA\Property(property="social_projects_goal_amount", type="number", format="float", example=2500000),
 *     @OA\Property(property="social_projects_raised_amount", type="number", format="float", example=850000),
 *     @OA\Property(property="social_actions_count", type="integer", example=8),
 *     @OA\Property(property="published_social_actions_count", type="integer", example=6),
 *     @OA\Property(property="social_actions_beneficiaries_count", type="integer", example=320),
 *     @OA\Property(property="impact_stats_count", type="integer", example=5),
 *     @OA\Property(property="active_impact_stats_count", type="integer", example=4),
 *     @OA\Property(property="testimonials_count", type="integer", example=7),
 *     @OA\Property(property="published_testimonials_count", type="integer", example=5),
 *     @OA\Property(property="donation_campaigns_count", type="integer", example=3),
 *     @OA\Property(property="active_donation_campaigns_count", type="integer", example=2),
 *     @OA\Property(property="donation_methods_count", type="integer", example=4),
 *     @OA\Property(property="active_donation_methods_count", type="integer", example=3),
 *     @OA\Property(property="donations_count", type="integer", example=12),
 *     @OA\Property(property="paid_donations_count", type="integer", example=8),
 *     @OA\Property(property="pending_donations_count", type="integer", example=4),
 *     @OA\Property(property="paid_donations_amount", type="number", format="float", example=475000),
 *     @OA\Property(property="contact_messages_count", type="integer", example=14),
 *     @OA\Property(property="new_contact_messages_count", type="integer", example=3),
 *     @OA\Property(property="answered_contact_messages_count", type="integer", example=6),
 *     @OA\Property(property="newsletter_subscribers_count", type="integer", example=150),
 *     @OA\Property(property="active_newsletter_subscribers_count", type="integer", example=142),
 *     @OA\Property(property="media_count", type="integer", example=30),
 *     @OA\Property(property="image_media_count", type="integer", example=24),
 *     @OA\Property(property="document_media_count", type="integer", example=6),
 *     @OA\Property(property="site_settings_count", type="integer", example=12),
 *     @OA\Property(property="users_count", type="integer", example=6),
 *     @OA\Property(property="active_users_count", type="integer", example=5),
 *     @OA\Property(property="roles_count", type="integer", example=7),
 *     @OA\Property(property="permissions_count", type="integer", example=46),
 *     @OA\Property(property="notifications_count", type="integer", example=5),
 *     @OA\Property(property="unread_notifications_count", type="integer", example=2)
 * )
 *
 * @OA\Schema(
 *     schema="AdminNotification",
 *     type="object",
 *     @OA\Property(property="id", type="string", example="9b5f1b51-5ef6-4f10-a9fa-2b0ef58c4a20"),
 *     @OA\Property(property="type", type="string", example="App\\Notifications\\ContactMessageNotification"),
 *     @OA\Property(property="data", type="object", additionalProperties=true),
 *     @OA\Property(property="read_at", type="string", format="date-time", nullable=true, example=null),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z")
 * )
 *
 * @OA\Schema(
 *     schema="AdminMediaUploader",
 *     type="object",
 *     nullable=true,
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Admin EMEC"),
 *     @OA\Property(property="email", type="string", format="email", example="admin@egliseemec.org")
 * )
 *
 * @OA\Schema(
 *     schema="AdminMedia",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="file_name", type="string", example="cover.jpg"),
 *     @OA\Property(property="file_path", type="string", example="media/2026/08/cover.jpg"),
 *     @OA\Property(property="url", type="string", example="https://api.egliseemec.org/storage/media/2026/08/cover.jpg"),
 *     @OA\Property(property="file_type", type="string", enum={"image","document"}, example="image"),
 *     @OA\Property(property="mime_type", type="string", example="image/jpeg"),
 *     @OA\Property(property="alt_text", type="string", nullable=true, example="Couverture du message"),
 *     @OA\Property(property="title", type="string", nullable=true, example="Image message"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Image de couverture."),
 *     @OA\Property(property="size", type="integer", nullable=true, example=524288),
 *     @OA\Property(property="uploaded_by", type="integer", nullable=true, example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z"),
 *     @OA\Property(property="uploader", ref="#/components/schemas/AdminMediaUploader")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/me",
 *     tags={"Admin Core"},
 *     summary="Afficher le profil admin connecte",
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Profil admin", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AuthenticatedUser"))),
 *     @OA\Response(response=401, description="Non authentifie")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dashboard",
 *     tags={"Admin Core"},
 *     summary="Afficher les compteurs du dashboard selon les permissions",
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Compteurs autorises", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDashboard"))),
 *     @OA\Response(response=401, description="Non authentifie")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/notifications",
 *     tags={"Admin Core"},
 *     summary="Lister les notifications admin",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Response(response=200, description="Liste paginee des notifications", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminNotification")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission notifications.view requise")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/notifications/unread",
 *     tags={"Admin Core"},
 *     summary="Lister les notifications admin non lues",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Response(response=200, description="Liste paginee des notifications non lues", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminNotification")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission notifications.view requise")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/notifications/{notification}/read",
 *     tags={"Admin Core"},
 *     summary="Marquer une notification comme lue",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="notification", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Notification mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminNotification"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission notifications.view requise"),
 *     @OA\Response(response=404, description="Notification introuvable")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/notifications/{notification}",
 *     tags={"Admin Core"},
 *     summary="Supprimer une notification",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="notification", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=204, description="Notification supprimee"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission notifications.view requise"),
 *     @OA\Response(response=404, description="Notification introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/media",
 *     tags={"Admin Core"},
 *     summary="Lister les medias admin",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=255)),
 *     @OA\Parameter(name="mime_type", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="file_type", in="query", @OA\Schema(type="string", enum={"image","document"})),
 *     @OA\Parameter(name="uploaded_by", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="orphaned", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"created_at","file_name","size","mime_type"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des medias", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminMedia")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission media.view requise"),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/media",
 *     tags={"Admin Core"},
 *     summary="Televerser un media",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\MediaType(
 *         mediaType="multipart/form-data",
 *         @OA\Schema(
 *             required={"file"},
 *             @OA\Property(property="file", type="string", format="binary"),
 *             @OA\Property(property="alt_text", type="string", nullable=true, maxLength=255),
 *             @OA\Property(property="title", type="string", nullable=true, maxLength=255),
 *             @OA\Property(property="description", type="string", nullable=true),
 *             @OA\Property(property="mediaable_type", type="string", nullable=true, example="message"),
 *             @OA\Property(property="mediaable_id", type="integer", nullable=true, example=1)
 *         )
 *     )),
 *     @OA\Response(response=201, description="Media cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMedia"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission media.create requise"),
 *     @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/media/{media}",
 *     tags={"Admin Core"},
 *     summary="Afficher un media admin",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="media", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Media", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMedia"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission media.view requise"),
 *     @OA\Response(response=404, description="Media introuvable")
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/media/{media}",
 *     tags={"Admin Core"},
 *     summary="Remplacer les metadonnees d'un media",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="media", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         @OA\Property(property="alt_text", type="string", nullable=true, maxLength=255),
 *         @OA\Property(property="title", type="string", nullable=true, maxLength=255),
 *         @OA\Property(property="description", type="string", nullable=true),
 *         @OA\Property(property="mediaable_type", type="string", nullable=true, example="church"),
 *         @OA\Property(property="mediaable_id", type="integer", nullable=true, example=1)
 *     )),
 *     @OA\Response(response=200, description="Media mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMedia"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission media.update requise"),
 *     @OA\Response(response=404, description="Media introuvable"),
 *     @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/media/{media}",
 *     tags={"Admin Core"},
 *     summary="Mettre a jour les metadonnees d'un media",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="media", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         @OA\Property(property="alt_text", type="string", nullable=true, maxLength=255),
 *         @OA\Property(property="title", type="string", nullable=true, maxLength=255),
 *         @OA\Property(property="description", type="string", nullable=true),
 *         @OA\Property(property="mediaable_type", type="string", nullable=true, example="church"),
 *         @OA\Property(property="mediaable_id", type="integer", nullable=true, example=1)
 *     )),
 *     @OA\Response(response=200, description="Media mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMedia"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission media.update requise"),
 *     @OA\Response(response=404, description="Media introuvable"),
 *     @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/media/{media}",
 *     tags={"Admin Core"},
 *     summary="Supprimer un media",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="media", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Media supprime"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Permission media.delete requise"),
 *     @OA\Response(response=404, description="Media introuvable")
 * )
 */
class AdminCoreDocumentation
{
}
