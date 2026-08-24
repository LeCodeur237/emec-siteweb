<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="DOSC", description="Projets sociaux, actions, impact, temoignages et dons DOSC")
 *
 * @OA\Schema(
 *     schema="DoscProjectSummary",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Soutien aux familles"),
 *     @OA\Property(property="slug", type="string", example="soutien-aux-familles")
 * )
 *
 * @OA\Schema(
 *     schema="SocialActionStat",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="label", type="string", example="Beneficiaires"),
 *     @OA\Property(property="value", type="string", example="80")
 * )
 *
 * @OA\Schema(
 *     schema="Testimonial",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", nullable=true, example="Temoin public"),
 *     @OA\Property(property="location", type="string", nullable=true, example="Douala"),
 *     @OA\Property(property="quote", type="string", example="Merci pour le soutien."),
 *     @OA\Property(property="avatar", type="string", nullable=true, example="https://api.egliseemec.org/storage/testimonials/avatar.jpg")
 * )
 *
 * @OA\Schema(
 *     schema="DonationCampaign",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="social_project_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", example="Campagne active"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Soutien public."),
 *     @OA\Property(property="goal_amount", type="string", nullable=true, example="500000.00"),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true, example="2026-08-01"),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true, example="2026-09-01"),
 *     @OA\Property(property="project", ref="#/components/schemas/DoscProjectSummary", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="SocialAction",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="social_project_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", example="Distribution de kits scolaires"),
 *     @OA\Property(property="slug", type="string", example="distribution-kits-scolaires"),
 *     @OA\Property(property="category", type="string", nullable=true, example="education"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Action sociale DOSC."),
 *     @OA\Property(property="location", type="string", nullable=true, example="Yaounde"),
 *     @OA\Property(property="action_date", type="string", format="date", nullable=true, example="2026-08-10"),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/dosc/action.jpg"),
 *     @OA\Property(property="youtube_video_id", type="string", nullable=true, example="abc123"),
 *     @OA\Property(property="beneficiaries_count", type="integer", nullable=true, example=80),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="project", ref="#/components/schemas/DoscProjectSummary", nullable=true),
 *     @OA\Property(property="stats", type="array", @OA\Items(ref="#/components/schemas/SocialActionStat")),
 *     @OA\Property(property="testimonials", type="array", @OA\Items(ref="#/components/schemas/Testimonial"))
 * )
 *
 * @OA\Schema(
 *     schema="SocialProject",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Soutien aux familles"),
 *     @OA\Property(property="slug", type="string", example="soutien-aux-familles"),
 *     @OA\Property(property="short_description", type="string", nullable=true, example="Aide sociale ciblee"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Projet de compassion pour familles vulnerables."),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/dosc/project.jpg"),
 *     @OA\Property(property="goal_amount", type="string", nullable=true, example="1000000.00"),
 *     @OA\Property(property="raised_amount", type="string", nullable=true, example="250000.00"),
 *     @OA\Property(property="beneficiaries_count", type="integer", nullable=true, example=120),
 *     @OA\Property(property="deadline", type="string", format="date", nullable=true, example="2026-12-31"),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="featured", type="boolean", example=true),
 *     @OA\Property(property="actions", type="array", @OA\Items(ref="#/components/schemas/SocialAction")),
 *     @OA\Property(property="donation_campaigns", type="array", @OA\Items(ref="#/components/schemas/DonationCampaign"))
 * )
 *
 * @OA\Schema(
 *     schema="ImpactStat",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="label", type="string", example="Familles"),
 *     @OA\Property(property="value", type="string", example="120"),
 *     @OA\Property(property="suffix", type="string", nullable=true, example="+"),
 *     @OA\Property(property="icon", type="string", nullable=true, example="heart"),
 *     @OA\Property(property="sort_order", type="integer", example=1)
 * )
 *
 * @OA\Schema(
 *     schema="DonationMethod",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Orange Money"),
 *     @OA\Property(property="type", type="string", example="mobile_money"),
 *     @OA\Property(property="provider", type="string", nullable=true, example="Orange"),
 *     @OA\Property(property="account_name", type="string", nullable=true, example="NTAP RUBEN"),
 *     @OA\Property(property="account_number", type="string", nullable=true, example="+237678660638"),
 *     @OA\Property(property="instructions", type="string", nullable=true, example="Indiquer DOSC en motif.")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/projects", tags={"DOSC"}, summary="Lister les projets sociaux publics",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="deadline_from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="deadline_to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"title","deadline","created_at","beneficiaries_count"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des projets", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SocialProject")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/projects/{slug}", tags={"DOSC"}, summary="Afficher un projet social public",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Projet social", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/SocialProject"))),
 *     @OA\Response(response=404, description="Projet introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/actions", tags={"DOSC"}, summary="Lister les actions sociales publiques",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="social_project_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="category", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="location", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"action_date","title","created_at","beneficiaries_count"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des actions", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SocialAction")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/actions/{slug}", tags={"DOSC"}, summary="Afficher une action sociale publique",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Action sociale", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/SocialAction"))),
 *     @OA\Response(response=404, description="Action introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/impact-stats", tags={"DOSC"}, summary="Lister les statistiques d'impact",
 *     @OA\Response(response=200, description="Liste des statistiques", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ImpactStat"))))
 * )
 *
 * @OA\Get(path="/api/v1/dosc/testimonials", tags={"DOSC"}, summary="Lister les temoignages publics",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="social_action_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="social_project_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des temoignages", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Testimonial")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/donation-campaigns", tags={"DOSC"}, summary="Lister les campagnes de dons actives",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="social_project_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"start_date","end_date","created_at","title"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee des campagnes", @OA\JsonContent(
 *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DonationCampaign")),
 *         @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *         @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *     )),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/donation-campaigns/{id}", tags={"DOSC"}, summary="Afficher une campagne de dons active",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Campagne de dons", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/DonationCampaign"))),
 *     @OA\Response(response=404, description="Campagne introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/dosc/donation-methods", tags={"DOSC"}, summary="Lister les moyens de dons actifs",
 *     @OA\Response(response=200, description="Liste des moyens de dons", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/DonationMethod"))))
 * )
 */
class DoscDocumentation
{
}
