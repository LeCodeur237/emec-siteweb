<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Admin EMEC", description="Gestion back-office des eglises, responsables, groupes, evenements et programmes")
 *
 * @OA\Schema(
 *     schema="AdminChurch",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Church"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="leaders_count", type="integer", example=2),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminChurchPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="EMEC Essos"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="emec-essos"),
 *     @OA\Property(property="baptism_name", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="city", type="string", nullable=true, maxLength=255, example="Yaounde"),
 *     @OA\Property(property="address", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="neighborhood", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="locality", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="sector", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="district", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="circumscription", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="mission_field", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="region", type="string", nullable=true, maxLength=255, example="Centre"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="pastor_vision", type="string", nullable=true),
 *     @OA\Property(property="contact", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="map_url", type="string", nullable=true, example="https://maps.google.com"),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="status", type="string", enum={"draft","published","archived"}, example="published"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminChurchLeader",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ChurchLeader"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="church", type="object", nullable=true,
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="EMEC Essos"),
 *                 @OA\Property(property="slug", type="string", example="emec-essos")
 *             )
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminChurchLeaderPayload",
 *     type="object",
 *     @OA\Property(property="church_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", maxLength=255, example="Pasteur Beta"),
 *     @OA\Property(property="responsibility", type="string", maxLength=255, example="Pasteur principal"),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true, example="2026-01-01"),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true, example=null),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminAdministrativeLeader",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/AdministrativeLeader"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminAdministrativeLeaderPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Secretaire general"),
 *     @OA\Property(property="responsibility", type="string", maxLength=255, example="Administration"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminGroup",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Group"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="leaders_count", type="integer", example=2),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminGroupPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Intercession EMEC"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="intercession-emec"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="short_description", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="color", type="string", nullable=true, maxLength=30, example="#1F7A4D"),
 *     @OA\Property(property="contact", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="email", type="string", format="email", nullable=true, maxLength=255),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminGroupLeader",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/GroupLeader"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="group", type="object", nullable=true,
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Jeunesse EMEC"),
 *                 @OA\Property(property="slug", type="string", example="jeunesse-emec")
 *             )
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminGroupLeaderPayload",
 *     type="object",
 *     @OA\Property(property="group_id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", maxLength=255, example="Responsable intercession"),
 *     @OA\Property(property="responsibility", type="string", maxLength=255, example="Responsable"),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminEventCategory",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/EventCategory"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="events_count", type="integer", example=3),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminEventCategoryPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Formation"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="formation"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminEvent",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Event"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="category", ref="#/components/schemas/AdminEventCategory", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminEventPayload",
 *     type="object",
 *     @OA\Property(property="event_category_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, example="Conference jeunesse"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="conference-jeunesse"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="start_at", type="string", format="date-time", example="2026-10-10 10:00:00"),
 *     @OA\Property(property="end_at", type="string", format="date-time", nullable=true, example="2026-10-10 13:00:00"),
 *     @OA\Property(property="location", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="city", type="string", nullable=true, maxLength=255, example="Douala"),
 *     @OA\Property(property="featured", type="boolean", example=true),
 *     @OA\Property(property="status", type="string", enum={"draft","published","cancelled","completed"}, example="published")
 * )
 *
 * @OA\Schema(
 *     schema="AdminWeeklyProgram",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/WeeklyProgram"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminWeeklyProgramPayload",
 *     type="object",
 *     @OA\Property(property="title", type="string", maxLength=255, example="Culte de celebration"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="day_of_week", type="integer", minimum=1, maximum=7, example=7),
 *     @OA\Property(property="start_time", type="string", example="09:00"),
 *     @OA\Property(property="end_time", type="string", nullable=true, example="11:00"),
 *     @OA\Property(property="location", type="string", nullable=true, maxLength=255, example="Temple EMEC"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Get(path="/api/v1/admin/churches", tags={"Admin EMEC"}, summary="Lister les eglises admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)), @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)), @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=255)), @OA\Parameter(name="city", in="query", @OA\Schema(type="string")), @OA\Parameter(name="region", in="query", @OA\Schema(type="string")), @OA\Parameter(name="status", in="query", @OA\Schema(type="string")), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","city","region","created_at","updated_at"})), @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminChurch")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=422, description="Parametres invalides")
 * )
 * @OA\Post(path="/api/v1/admin/churches", tags={"Admin EMEC"}, summary="Creer une eglise", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminChurchPayload")), @OA\Response(response=201, description="Eglise creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurch"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/churches/{church}", tags={"Admin EMEC"}, summary="Afficher une eglise admin", security={{"sanctum":{}}}, @OA\Parameter(name="church", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Eglise", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurch"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=404, description="Eglise introuvable"))
 * @OA\Put(path="/api/v1/admin/churches/{church}", tags={"Admin EMEC"}, summary="Remplacer une eglise", security={{"sanctum":{}}}, @OA\Parameter(name="church", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminChurchPayload")), @OA\Response(response=200, description="Eglise mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurch"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=404, description="Eglise introuvable"), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/churches/{church}", tags={"Admin EMEC"}, summary="Modifier une eglise", security={{"sanctum":{}}}, @OA\Parameter(name="church", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminChurchPayload")), @OA\Response(response=200, description="Eglise mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurch"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=404, description="Eglise introuvable"), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/churches/{church}", tags={"Admin EMEC"}, summary="Supprimer une eglise", security={{"sanctum":{}}}, @OA\Parameter(name="church", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Eglise supprimee"), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=404, description="Eglise introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/church-leaders", tags={"Admin EMEC"}, summary="Lister les responsables d'eglise admin", security={{"sanctum":{}}}, @OA\Parameter(name="church_id", in="query", @OA\Schema(type="integer")), @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","responsibility","created_at","updated_at"})), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminChurchLeader")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"))
 * @OA\Post(path="/api/v1/admin/church-leaders", tags={"Admin EMEC"}, summary="Creer un responsable d'eglise", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"church_id","name","responsibility"}, ref="#/components/schemas/AdminChurchLeaderPayload")), @OA\Response(response=201, description="Responsable cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurchLeader"))), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission churches.manage requise"), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/church-leaders/{churchLeader}", tags={"Admin EMEC"}, summary="Afficher un responsable d'eglise admin", security={{"sanctum":{}}}, @OA\Parameter(name="churchLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Responsable", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurchLeader"))), @OA\Response(response=404, description="Responsable introuvable"))
 * @OA\Put(path="/api/v1/admin/church-leaders/{churchLeader}", tags={"Admin EMEC"}, summary="Remplacer un responsable d'eglise", security={{"sanctum":{}}}, @OA\Parameter(name="churchLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminChurchLeaderPayload")), @OA\Response(response=200, description="Responsable mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurchLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/church-leaders/{churchLeader}", tags={"Admin EMEC"}, summary="Modifier un responsable d'eglise", security={{"sanctum":{}}}, @OA\Parameter(name="churchLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminChurchLeaderPayload")), @OA\Response(response=200, description="Responsable mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminChurchLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/church-leaders/{churchLeader}", tags={"Admin EMEC"}, summary="Supprimer un responsable d'eglise", security={{"sanctum":{}}}, @OA\Parameter(name="churchLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Responsable supprime"))
 *
 * @OA\Get(path="/api/v1/admin/administrative-leaders", tags={"Admin EMEC"}, summary="Lister les responsables administratifs admin", security={{"sanctum":{}}}, @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","responsibility","created_at","updated_at"})), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminAdministrativeLeader")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))))
 * @OA\Post(path="/api/v1/admin/administrative-leaders", tags={"Admin EMEC"}, summary="Creer un responsable administratif", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"name","responsibility"}, ref="#/components/schemas/AdminAdministrativeLeaderPayload")), @OA\Response(response=201, description="Responsable cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminAdministrativeLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/administrative-leaders/{administrativeLeader}", tags={"Admin EMEC"}, summary="Afficher un responsable administratif", security={{"sanctum":{}}}, @OA\Parameter(name="administrativeLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Responsable", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminAdministrativeLeader"))), @OA\Response(response=404, description="Responsable introuvable"))
 * @OA\Put(path="/api/v1/admin/administrative-leaders/{administrativeLeader}", tags={"Admin EMEC"}, summary="Remplacer un responsable administratif", security={{"sanctum":{}}}, @OA\Parameter(name="administrativeLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminAdministrativeLeaderPayload")), @OA\Response(response=200, description="Responsable mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminAdministrativeLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/administrative-leaders/{administrativeLeader}", tags={"Admin EMEC"}, summary="Modifier un responsable administratif", security={{"sanctum":{}}}, @OA\Parameter(name="administrativeLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminAdministrativeLeaderPayload")), @OA\Response(response=200, description="Responsable mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminAdministrativeLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/administrative-leaders/{administrativeLeader}", tags={"Admin EMEC"}, summary="Supprimer un responsable administratif", security={{"sanctum":{}}}, @OA\Parameter(name="administrativeLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Responsable supprime"))
 *
 * @OA\Get(path="/api/v1/admin/groups", tags={"Admin EMEC"}, summary="Lister les groupes admin", security={{"sanctum":{}}}, @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","created_at","updated_at"})), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminGroup")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))))
 * @OA\Post(path="/api/v1/admin/groups", tags={"Admin EMEC"}, summary="Creer un groupe", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminGroupPayload")), @OA\Response(response=201, description="Groupe cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroup"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/groups/{group}", tags={"Admin EMEC"}, summary="Afficher un groupe admin", security={{"sanctum":{}}}, @OA\Parameter(name="group", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Groupe", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroup"))), @OA\Response(response=404, description="Groupe introuvable"))
 * @OA\Put(path="/api/v1/admin/groups/{group}", tags={"Admin EMEC"}, summary="Remplacer un groupe", security={{"sanctum":{}}}, @OA\Parameter(name="group", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminGroupPayload")), @OA\Response(response=200, description="Groupe mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroup"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/groups/{group}", tags={"Admin EMEC"}, summary="Modifier un groupe", security={{"sanctum":{}}}, @OA\Parameter(name="group", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminGroupPayload")), @OA\Response(response=200, description="Groupe mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroup"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/groups/{group}", tags={"Admin EMEC"}, summary="Supprimer un groupe", security={{"sanctum":{}}}, @OA\Parameter(name="group", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Groupe supprime"))
 *
 * @OA\Get(path="/api/v1/admin/group-leaders", tags={"Admin EMEC"}, summary="Lister les responsables de groupe admin", security={{"sanctum":{}}}, @OA\Parameter(name="group_id", in="query", @OA\Schema(type="integer")), @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminGroupLeader")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))))
 * @OA\Post(path="/api/v1/admin/group-leaders", tags={"Admin EMEC"}, summary="Creer un responsable de groupe", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"group_id","name","responsibility"}, ref="#/components/schemas/AdminGroupLeaderPayload")), @OA\Response(response=201, description="Responsable cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroupLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/group-leaders/{groupLeader}", tags={"Admin EMEC"}, summary="Afficher un responsable de groupe", security={{"sanctum":{}}}, @OA\Parameter(name="groupLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Responsable", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroupLeader"))), @OA\Response(response=404, description="Responsable introuvable"))
 * @OA\Put(path="/api/v1/admin/group-leaders/{groupLeader}", tags={"Admin EMEC"}, summary="Remplacer un responsable de groupe", security={{"sanctum":{}}}, @OA\Parameter(name="groupLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminGroupLeaderPayload")), @OA\Response(response=200, description="Responsable mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroupLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/group-leaders/{groupLeader}", tags={"Admin EMEC"}, summary="Modifier un responsable de groupe", security={{"sanctum":{}}}, @OA\Parameter(name="groupLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminGroupLeaderPayload")), @OA\Response(response=200, description="Responsable mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminGroupLeader"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/group-leaders/{groupLeader}", tags={"Admin EMEC"}, summary="Supprimer un responsable de groupe", security={{"sanctum":{}}}, @OA\Parameter(name="groupLeader", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Responsable supprime"))
 *
 * @OA\Get(path="/api/v1/admin/event-categories", tags={"Admin EMEC"}, summary="Lister les categories d'evenements admin", security={{"sanctum":{}}}, @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminEventCategory")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))))
 * @OA\Post(path="/api/v1/admin/event-categories", tags={"Admin EMEC"}, summary="Creer une categorie d'evenements", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminEventCategoryPayload")), @OA\Response(response=201, description="Categorie creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEventCategory"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/event-categories/{eventCategory}", tags={"Admin EMEC"}, summary="Afficher une categorie d'evenements", security={{"sanctum":{}}}, @OA\Parameter(name="eventCategory", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Categorie", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEventCategory"))), @OA\Response(response=404, description="Categorie introuvable"))
 * @OA\Put(path="/api/v1/admin/event-categories/{eventCategory}", tags={"Admin EMEC"}, summary="Remplacer une categorie d'evenements", security={{"sanctum":{}}}, @OA\Parameter(name="eventCategory", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminEventCategoryPayload")), @OA\Response(response=200, description="Categorie mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEventCategory"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/event-categories/{eventCategory}", tags={"Admin EMEC"}, summary="Modifier une categorie d'evenements", security={{"sanctum":{}}}, @OA\Parameter(name="eventCategory", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminEventCategoryPayload")), @OA\Response(response=200, description="Categorie mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEventCategory"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/event-categories/{eventCategory}", tags={"Admin EMEC"}, summary="Supprimer une categorie d'evenements", security={{"sanctum":{}}}, @OA\Parameter(name="eventCategory", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Categorie supprimee"))
 *
 * @OA\Get(path="/api/v1/admin/events", tags={"Admin EMEC"}, summary="Lister les evenements admin", security={{"sanctum":{}}}, @OA\Parameter(name="event_category_id", in="query", @OA\Schema(type="integer")), @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="city", in="query", @OA\Schema(type="string")), @OA\Parameter(name="status", in="query", @OA\Schema(type="string")), @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")), @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")), @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")), @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"title","start_at","created_at","updated_at"})), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminEvent")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))))
 * @OA\Post(path="/api/v1/admin/events", tags={"Admin EMEC"}, summary="Creer un evenement", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"title","start_at"}, ref="#/components/schemas/AdminEventPayload")), @OA\Response(response=201, description="Evenement cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEvent"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/events/{event}", tags={"Admin EMEC"}, summary="Afficher un evenement admin", security={{"sanctum":{}}}, @OA\Parameter(name="event", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Evenement", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEvent"))), @OA\Response(response=404, description="Evenement introuvable"))
 * @OA\Put(path="/api/v1/admin/events/{event}", tags={"Admin EMEC"}, summary="Remplacer un evenement", security={{"sanctum":{}}}, @OA\Parameter(name="event", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminEventPayload")), @OA\Response(response=200, description="Evenement mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEvent"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/events/{event}", tags={"Admin EMEC"}, summary="Modifier un evenement", security={{"sanctum":{}}}, @OA\Parameter(name="event", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminEventPayload")), @OA\Response(response=200, description="Evenement mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminEvent"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/events/{event}", tags={"Admin EMEC"}, summary="Supprimer un evenement", security={{"sanctum":{}}}, @OA\Parameter(name="event", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Evenement supprime"))
 *
 * @OA\Get(path="/api/v1/admin/weekly-programs", tags={"Admin EMEC"}, summary="Lister les programmes hebdomadaires admin", security={{"sanctum":{}}}, @OA\Parameter(name="search", in="query", @OA\Schema(type="string")), @OA\Parameter(name="day_of_week", in="query", @OA\Schema(type="integer", minimum=1, maximum=7)), @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")), @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminWeeklyProgram")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))))
 * @OA\Post(path="/api/v1/admin/weekly-programs", tags={"Admin EMEC"}, summary="Creer un programme hebdomadaire", security={{"sanctum":{}}}, @OA\RequestBody(required=true, @OA\JsonContent(required={"title","day_of_week","start_time"}, ref="#/components/schemas/AdminWeeklyProgramPayload")), @OA\Response(response=201, description="Programme cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminWeeklyProgram"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Get(path="/api/v1/admin/weekly-programs/{weeklyProgram}", tags={"Admin EMEC"}, summary="Afficher un programme hebdomadaire", security={{"sanctum":{}}}, @OA\Parameter(name="weeklyProgram", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=200, description="Programme", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminWeeklyProgram"))), @OA\Response(response=404, description="Programme introuvable"))
 * @OA\Put(path="/api/v1/admin/weekly-programs/{weeklyProgram}", tags={"Admin EMEC"}, summary="Remplacer un programme hebdomadaire", security={{"sanctum":{}}}, @OA\Parameter(name="weeklyProgram", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminWeeklyProgramPayload")), @OA\Response(response=200, description="Programme mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminWeeklyProgram"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Patch(path="/api/v1/admin/weekly-programs/{weeklyProgram}", tags={"Admin EMEC"}, summary="Modifier un programme hebdomadaire", security={{"sanctum":{}}}, @OA\Parameter(name="weeklyProgram", in="path", required=true, @OA\Schema(type="integer")), @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminWeeklyProgramPayload")), @OA\Response(response=200, description="Programme mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminWeeklyProgram"))), @OA\Response(response=422, description="Donnees invalides"))
 * @OA\Delete(path="/api/v1/admin/weekly-programs/{weeklyProgram}", tags={"Admin EMEC"}, summary="Supprimer un programme hebdomadaire", security={{"sanctum":{}}}, @OA\Parameter(name="weeklyProgram", in="path", required=true, @OA\Schema(type="integer")), @OA\Response(response=204, description="Programme supprime"))
 */
class AdminEmecDocumentation
{
}
