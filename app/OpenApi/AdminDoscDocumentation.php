<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Admin DOSC", description="Gestion back-office des projets, actions sociales, temoignages et statistiques DOSC")
 *
 * @OA\Schema(
 *     schema="AdminDoscActionSummary",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Distribution de kits scolaires"),
 *     @OA\Property(property="slug", type="string", example="distribution-kits-scolaires")
 * )
 *
 * @OA\Schema(
 *     schema="AdminSocialProject",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/SocialProject"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="actions_count", type="integer", example=3),
 *             @OA\Property(property="donation_campaigns_count", type="integer", example=1),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="actions", type="array", @OA\Items(ref="#/components/schemas/SocialAction")),
 *             @OA\Property(property="media", type="array", @OA\Items(ref="#/components/schemas/AdminMedia"))
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminSocialProjectPayload",
 *     type="object",
 *     @OA\Property(property="title", type="string", maxLength=255, example="Soutien scolaire DOSC"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="soutien-scolaire-dosc"),
 *     @OA\Property(property="short_description", type="string", nullable=true),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="goal_amount", type="number", format="float", nullable=true, minimum=0, example=1500000),
 *     @OA\Property(property="raised_amount", type="number", format="float", minimum=0, example=250000),
 *     @OA\Property(property="beneficiaries_count", type="integer", minimum=0, example=120),
 *     @OA\Property(property="deadline", type="string", format="date", nullable=true, example="2026-12-31"),
 *     @OA\Property(property="status", type="string", enum={"draft","active","archived"}, example="active"),
 *     @OA\Property(property="featured", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminSocialAction",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/SocialAction"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="stats_count", type="integer", example=2),
 *             @OA\Property(property="testimonials_count", type="integer", example=4),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="project", type="object", nullable=true,
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Soutien scolaire DOSC"),
 *                 @OA\Property(property="slug", type="string", example="soutien-scolaire-dosc"),
 *                 @OA\Property(property="status", type="string", example="active")
 *             ),
 *             @OA\Property(property="stats", type="array", @OA\Items(ref="#/components/schemas/AdminSocialActionStat")),
 *             @OA\Property(property="testimonials", type="array", @OA\Items(ref="#/components/schemas/AdminTestimonial")),
 *             @OA\Property(property="media", type="array", @OA\Items(ref="#/components/schemas/AdminMedia"))
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminSocialActionPayload",
 *     type="object",
 *     @OA\Property(property="social_project_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, example="Distribution de kits scolaires"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="distribution-kits-scolaires"),
 *     @OA\Property(property="category", type="string", nullable=true, maxLength=255, example="education"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="location", type="string", nullable=true, maxLength=255, example="Yaounde"),
 *     @OA\Property(property="action_date", type="string", format="date", nullable=true, example="2026-09-15"),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="youtube_video_id", type="string", nullable=true, maxLength=100, example="dQw4w9WgXcQ"),
 *     @OA\Property(property="beneficiaries_count", type="integer", minimum=0, example=80),
 *     @OA\Property(property="status", type="string", enum={"draft","published","archived"}, example="published")
 * )
 *
 * @OA\Schema(
 *     schema="AdminSocialActionStat",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="social_action_id", type="integer", example=1),
 *     @OA\Property(property="label", type="string", example="Beneficiaires"),
 *     @OA\Property(property="value", type="string", example="80"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="action", ref="#/components/schemas/AdminDoscActionSummary", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminSocialActionStatPayload",
 *     type="object",
 *     @OA\Property(property="social_action_id", type="integer", example=1),
 *     @OA\Property(property="label", type="string", maxLength=255, example="Beneficiaires"),
 *     @OA\Property(property="value", type="string", maxLength=255, example="80")
 * )
 *
 * @OA\Schema(
 *     schema="AdminTestimonial",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="social_action_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="name", type="string", nullable=true, example="Marie N."),
 *     @OA\Property(property="location", type="string", nullable=true, example="Yaounde"),
 *     @OA\Property(property="quote", type="string", example="Le soutien recu a change notre quotidien."),
 *     @OA\Property(property="avatar", type="string", nullable=true),
 *     @OA\Property(property="published", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="action", ref="#/components/schemas/AdminDoscActionSummary", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminTestimonialPayload",
 *     type="object",
 *     @OA\Property(property="social_action_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="name", type="string", nullable=true, maxLength=255, example="Marie N."),
 *     @OA\Property(property="location", type="string", nullable=true, maxLength=255, example="Yaounde"),
 *     @OA\Property(property="quote", type="string", example="Le soutien recu a change notre quotidien."),
 *     @OA\Property(property="avatar", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="published", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminImpactStat",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ImpactStat"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="active", type="boolean", example=true),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminImpactStatPayload",
 *     type="object",
 *     @OA\Property(property="label", type="string", maxLength=255, example="Familles accompagnees"),
 *     @OA\Property(property="value", type="string", maxLength=255, example="250"),
 *     @OA\Property(property="suffix", type="string", nullable=true, maxLength=50, example="+"),
 *     @OA\Property(property="icon", type="string", nullable=true, maxLength=100, example="HeartHandshake"),
 *     @OA\Property(property="sort_order", type="integer", minimum=0, example=1),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/projects",
 *     tags={"Admin DOSC"},
 *     summary="Lister les projets sociaux DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"draft","active","archived"})),
 *     @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="deadline_from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="deadline_to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"title","deadline","created_at","updated_at","beneficiaries_count"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1, maximum=100)),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminSocialProject")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/dosc/projects",
 *     tags={"Admin DOSC"},
 *     summary="Creer un projet social DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"title"}, ref="#/components/schemas/AdminSocialProjectPayload")),
 *     @OA\Response(response=201, description="Projet cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialProject"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/projects/{project}",
 *     tags={"Admin DOSC"},
 *     summary="Afficher un projet social DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="project", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Projet", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialProject"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Projet introuvable")
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/dosc/projects/{project}",
 *     tags={"Admin DOSC"},
 *     summary="Remplacer un projet social DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="project", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSocialProjectPayload")),
 *     @OA\Response(response=200, description="Projet mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialProject"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Projet introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/dosc/projects/{project}",
 *     tags={"Admin DOSC"},
 *     summary="Modifier un projet social DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="project", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSocialProjectPayload")),
 *     @OA\Response(response=200, description="Projet mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialProject"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Projet introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/dosc/projects/{project}",
 *     tags={"Admin DOSC"},
 *     summary="Supprimer un projet social DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="project", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Projet supprime"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Projet introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/actions",
 *     tags={"Admin DOSC"},
 *     summary="Lister les actions sociales DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="social_project_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="category", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="location", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"draft","published","archived"})),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"action_date","title","created_at","updated_at","beneficiaries_count"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminSocialAction")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/dosc/actions",
 *     tags={"Admin DOSC"},
 *     summary="Creer une action sociale DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"title"}, ref="#/components/schemas/AdminSocialActionPayload")),
 *     @OA\Response(response=201, description="Action creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialAction"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/actions/{action}",
 *     tags={"Admin DOSC"},
 *     summary="Afficher une action sociale DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="action", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Action", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialAction"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Action introuvable")
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/dosc/actions/{action}",
 *     tags={"Admin DOSC"},
 *     summary="Remplacer une action sociale DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="action", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSocialActionPayload")),
 *     @OA\Response(response=200, description="Action mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialAction"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Action introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/dosc/actions/{action}",
 *     tags={"Admin DOSC"},
 *     summary="Modifier une action sociale DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="action", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSocialActionPayload")),
 *     @OA\Response(response=200, description="Action mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialAction"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Action introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/dosc/actions/{action}",
 *     tags={"Admin DOSC"},
 *     summary="Supprimer une action sociale DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="action", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Action supprimee"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Action introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/action-stats",
 *     tags={"Admin DOSC"},
 *     summary="Lister les statistiques des actions DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="social_action_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"label","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminSocialActionStat")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/dosc/action-stats",
 *     tags={"Admin DOSC"},
 *     summary="Creer une statistique d'action DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"social_action_id","label","value"}, ref="#/components/schemas/AdminSocialActionStatPayload")),
 *     @OA\Response(response=201, description="Statistique creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialActionStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/action-stats/{actionStat}",
 *     tags={"Admin DOSC"},
 *     summary="Afficher une statistique d'action DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="actionStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Statistique", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialActionStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable")
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/dosc/action-stats/{actionStat}",
 *     tags={"Admin DOSC"},
 *     summary="Remplacer une statistique d'action DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="actionStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSocialActionStatPayload")),
 *     @OA\Response(response=200, description="Statistique mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialActionStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/dosc/action-stats/{actionStat}",
 *     tags={"Admin DOSC"},
 *     summary="Modifier une statistique d'action DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="actionStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSocialActionStatPayload")),
 *     @OA\Response(response=200, description="Statistique mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSocialActionStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/dosc/action-stats/{actionStat}",
 *     tags={"Admin DOSC"},
 *     summary="Supprimer une statistique d'action DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="actionStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Statistique supprimee"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/testimonials",
 *     tags={"Admin DOSC"},
 *     summary="Lister les temoignages DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="social_action_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="social_project_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="published", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminTestimonial")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/dosc/testimonials",
 *     tags={"Admin DOSC"},
 *     summary="Creer un temoignage DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"quote"}, ref="#/components/schemas/AdminTestimonialPayload")),
 *     @OA\Response(response=201, description="Temoignage cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminTestimonial"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/testimonials/{testimonial}",
 *     tags={"Admin DOSC"},
 *     summary="Afficher un temoignage DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="testimonial", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Temoignage", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminTestimonial"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Temoignage introuvable")
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/dosc/testimonials/{testimonial}",
 *     tags={"Admin DOSC"},
 *     summary="Remplacer un temoignage DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="testimonial", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminTestimonialPayload")),
 *     @OA\Response(response=200, description="Temoignage mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminTestimonial"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Temoignage introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/dosc/testimonials/{testimonial}",
 *     tags={"Admin DOSC"},
 *     summary="Modifier un temoignage DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="testimonial", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminTestimonialPayload")),
 *     @OA\Response(response=200, description="Temoignage mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminTestimonial"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Temoignage introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/dosc/testimonials/{testimonial}",
 *     tags={"Admin DOSC"},
 *     summary="Supprimer un temoignage DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="testimonial", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Temoignage supprime"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Temoignage introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/impact-stats",
 *     tags={"Admin DOSC"},
 *     summary="Lister les statistiques d'impact DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"label","sort_order","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminImpactStat")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/admin/dosc/impact-stats",
 *     tags={"Admin DOSC"},
 *     summary="Creer une statistique d'impact DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"label","value"}, ref="#/components/schemas/AdminImpactStatPayload")),
 *     @OA\Response(response=201, description="Statistique creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminImpactStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/admin/dosc/impact-stats/{impactStat}",
 *     tags={"Admin DOSC"},
 *     summary="Afficher une statistique d'impact DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="impactStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Statistique", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminImpactStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable")
 * )
 *
 * @OA\Put(
 *     path="/api/v1/admin/dosc/impact-stats/{impactStat}",
 *     tags={"Admin DOSC"},
 *     summary="Remplacer une statistique d'impact DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="impactStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminImpactStatPayload")),
 *     @OA\Response(response=200, description="Statistique mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminImpactStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Patch(
 *     path="/api/v1/admin/dosc/impact-stats/{impactStat}",
 *     tags={"Admin DOSC"},
 *     summary="Modifier une statistique d'impact DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="impactStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminImpactStatPayload")),
 *     @OA\Response(response=200, description="Statistique mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminImpactStat"))),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable"),
 *     @OA\Response(response=422, description="Validation echouee")
 * )
 *
 * @OA\Delete(
 *     path="/api/v1/admin/dosc/impact-stats/{impactStat}",
 *     tags={"Admin DOSC"},
 *     summary="Supprimer une statistique d'impact DOSC",
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(name="impactStat", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Statistique supprimee"),
 *     @OA\Response(response=401, description="Non authentifie"),
 *     @OA\Response(response=403, description="Acces refuse"),
 *     @OA\Response(response=404, description="Statistique introuvable")
 * )
 */
final class AdminDoscDocumentation
{
}
