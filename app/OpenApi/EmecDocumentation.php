<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="EMEC", description="Eglises, responsables, groupes, evenements et programmes")
 *
 * @OA\Schema(
 *     schema="ChurchLeader",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="church_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="name", type="string", example="Pasteur Jean"),
 *     @OA\Property(property="responsibility", type="string", nullable=true, example="Pasteur responsable"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/leaders/jean.jpg"),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true, example="2026-01-01"),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true, example=null),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdministrativeLeader",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Responsable EMEC"),
 *     @OA\Property(property="responsibility", type="string", nullable=true, example="Secretaire general"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Responsable administratif."),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/administration/responsable.jpg"),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true, example="2026-01-01"),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true, example=null),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="Church",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="EMEC Essos"),
 *     @OA\Property(property="slug", type="string", example="emec-essos"),
 *     @OA\Property(property="baptism_name", type="string", nullable=true, example="Temple de la Grace"),
 *     @OA\Property(property="city", type="string", nullable=true, example="Yaounde"),
 *     @OA\Property(property="address", type="string", nullable=true, example="Essos"),
 *     @OA\Property(property="neighborhood", type="string", nullable=true, example="Essos"),
 *     @OA\Property(property="locality", type="string", nullable=true, example="Yaounde"),
 *     @OA\Property(property="sector", type="string", nullable=true, example="Centre"),
 *     @OA\Property(property="district", type="string", nullable=true, example="Yaounde 5"),
 *     @OA\Property(property="circumscription", type="string", nullable=true, example="Mfoundi"),
 *     @OA\Property(property="mission_field", type="string", nullable=true, example="Champ missionnaire Centre"),
 *     @OA\Property(property="region", type="string", nullable=true, example="Centre"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Presentation de l'eglise locale."),
 *     @OA\Property(property="pastor_vision", type="string", nullable=true, example="Vision pastorale."),
 *     @OA\Property(property="contact", type="string", nullable=true, example="+237 600 000 000"),
 *     @OA\Property(property="map_url", type="string", nullable=true, example="https://maps.google.com"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/churches/essos.jpg"),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="leaders", type="array", @OA\Items(ref="#/components/schemas/ChurchLeader"))
 * )
 *
 * @OA\Schema(
 *     schema="GroupLeader",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="group_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="name", type="string", example="Responsable jeunesse"),
 *     @OA\Property(property="responsibility", type="string", nullable=true, example="President"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/groups/leader.jpg"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="Group",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Jeunesse EMEC"),
 *     @OA\Property(property="slug", type="string", example="jeunesse-emec"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Description du groupe."),
 *     @OA\Property(property="short_description", type="string", nullable=true, example="Groupe des jeunes."),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/groups/jeunesse.jpg"),
 *     @OA\Property(property="color", type="string", nullable=true, example="#1F7A4D"),
 *     @OA\Property(property="contact", type="string", nullable=true, example="+237 600 000 000"),
 *     @OA\Property(property="email", type="string", nullable=true, example="jeunesse@egliseemec.org"),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="leaders", type="array", @OA\Items(ref="#/components/schemas/GroupLeader"))
 * )
 *
 * @OA\Schema(
 *     schema="EventCategory",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Culte"),
 *     @OA\Property(property="slug", type="string", example="culte"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Evenements de culte."),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="Event",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="event_category_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", example="Culte dominical"),
 *     @OA\Property(property="slug", type="string", example="culte-dominical"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Culte de celebration."),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/events/culte.jpg"),
 *     @OA\Property(property="start_at", type="string", format="date-time", nullable=true, example="2026-08-23T09:00:00+01:00"),
 *     @OA\Property(property="end_at", type="string", format="date-time", nullable=true, example="2026-08-23T12:00:00+01:00"),
 *     @OA\Property(property="location", type="string", nullable=true, example="Temple EMEC Essos"),
 *     @OA\Property(property="city", type="string", nullable=true, example="Yaounde"),
 *     @OA\Property(property="featured", type="boolean", example=true),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="category", ref="#/components/schemas/EventCategory", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="WeeklyProgram",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Culte dominical"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Programme hebdomadaire."),
 *     @OA\Property(property="day_of_week", type="integer", minimum=1, maximum=7, example=7),
 *     @OA\Property(property="start_time", type="string", nullable=true, example="09:00:00"),
 *     @OA\Property(property="end_time", type="string", nullable=true, example="12:00:00"),
 *     @OA\Property(property="location", type="string", nullable=true, example="Temple EMEC Essos"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Get(
 *     path="/api/v1/churches",
 *     tags={"EMEC"},
 *     summary="Lister les eglises publiques",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="city", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="region", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","city","region","created_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des eglises", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Church")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/churches/{slug}", tags={"EMEC"}, summary="Afficher une eglise publique",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Eglise", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Church"))),
 *     @OA\Response(response=404, description="Eglise introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/church-leaders", tags={"EMEC"}, summary="Lister les responsables d'eglise",
 *     @OA\Parameter(name="church_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste des responsables", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ChurchLeader")))),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/church-leaders/{id}", tags={"EMEC"}, summary="Afficher un responsable d'eglise",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Responsable", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/ChurchLeader"))),
 *     @OA\Response(response=404, description="Responsable introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/administrative-leaders", tags={"EMEC"}, summary="Lister les responsables administratifs",
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste des responsables administratifs", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdministrativeLeader")))),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/administrative-leaders/{id}", tags={"EMEC"}, summary="Afficher un responsable administratif",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Responsable administratif", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdministrativeLeader"))),
 *     @OA\Response(response=404, description="Responsable introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/groups", tags={"EMEC"}, summary="Lister les groupes publics",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste paginee des groupes", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Group")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/groups/{slug}", tags={"EMEC"}, summary="Afficher un groupe public",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Groupe", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Group"))),
 *     @OA\Response(response=404, description="Groupe introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/group-leaders", tags={"EMEC"}, summary="Lister les responsables de groupe",
 *     @OA\Parameter(name="group_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste des responsables", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/GroupLeader")))),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/group-leaders/{id}", tags={"EMEC"}, summary="Afficher un responsable de groupe",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Responsable", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/GroupLeader"))),
 *     @OA\Response(response=404, description="Responsable introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/event-categories", tags={"EMEC"}, summary="Lister les categories d'evenements",
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste des categories", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/EventCategory")))),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/event-categories/{slug}", tags={"EMEC"}, summary="Afficher une categorie d'evenements",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Categorie", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/EventCategory"))),
 *     @OA\Response(response=404, description="Categorie introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/events", tags={"EMEC"}, summary="Lister les evenements publics",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="event_category_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="city", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"start_at","title","created_at","city"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des evenements", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Event")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/events/{slug}", tags={"EMEC"}, summary="Afficher un evenement public",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Evenement", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Event"))),
 *     @OA\Response(response=404, description="Evenement introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/weekly-programs", tags={"EMEC"}, summary="Lister les programmes hebdomadaires",
 *     @OA\Parameter(name="day_of_week", in="query", @OA\Schema(type="integer", minimum=1, maximum=7)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste des programmes", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/WeeklyProgram")))),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/weekly-programs/{id}", tags={"EMEC"}, summary="Afficher un programme hebdomadaire",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Programme", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/WeeklyProgram"))),
 *     @OA\Response(response=404, description="Programme introuvable")
 * )
 */
class EmecDocumentation
{
}
