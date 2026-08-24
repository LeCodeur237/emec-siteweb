<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Communication", description="Contact public et newsletter")
 *
 * @OA\Schema(
 *     schema="PublicContactMessage",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", example="new"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z")
 * )
 *
 * @OA\Schema(
 *     schema="PublicNewsletterSubscriber",
 *     type="object",
 *     @OA\Property(property="status", type="string", example="subscribed"),
 *     @OA\Property(property="subscribed_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z"),
 *     @OA\Property(property="unsubscribed_at", type="string", format="date-time", nullable=true, example=null)
 * )
 *
 * @OA\Post(
 *     path="/api/v1/contact",
 *     tags={"Communication"},
 *     summary="Envoyer un message de contact",
 *     description="Cree un message de contact public. Le champ website est un honeypot antispam et doit etre absent.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","message"},
 *             @OA\Property(property="name", type="string", maxLength=255, example="Visiteur EMEC"),
 *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="visiteur@example.org"),
 *             @OA\Property(property="phone", type="string", nullable=true, maxLength=40, example="+237699000000"),
 *             @OA\Property(property="subject", type="string", nullable=true, maxLength=255, example="Demande d'information"),
 *             @OA\Property(property="message", type="string", maxLength=5000, example="Bonjour EMEC."),
 *             @OA\Property(property="website", type="string", writeOnly=true, description="Honeypot antispam. Ne pas envoyer.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Message cree",
 *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/PublicContactMessage"))
 *     ),
 *     @OA\Response(response=422, description="Donnees invalides"),
 *     @OA\Response(response=429, description="Trop de requetes")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/newsletter/subscribe",
 *     tags={"Communication"},
 *     summary="S'abonner a la newsletter",
 *     description="Inscrit ou reactive un abonnement newsletter. L'email n'est pas expose dans la reponse publique.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="name", type="string", nullable=true, maxLength=255, example="Abonne EMEC"),
 *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="abonne@example.org"),
 *             @OA\Property(property="website", type="string", writeOnly=true, description="Honeypot antispam. Ne pas envoyer.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Nouvel abonnement cree",
 *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/PublicNewsletterSubscriber"))
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Abonnement deja existant ou reactive",
 *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/PublicNewsletterSubscriber"))
 *     ),
 *     @OA\Response(response=422, description="Donnees invalides"),
 *     @OA\Response(response=429, description="Trop de requetes")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/newsletter/unsubscribe",
 *     tags={"Communication"},
 *     summary="Se desabonner de la newsletter",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","unsubscribe_token"},
 *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="abonne@example.org"),
 *             @OA\Property(property="unsubscribe_token", type="string", minLength=64, maxLength=64, example="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Desabonnement effectue",
 *         @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/PublicNewsletterSubscriber"))
 *     ),
 *     @OA\Response(response=404, description="Abonnement introuvable"),
 *     @OA\Response(response=422, description="Donnees invalides"),
 *     @OA\Response(response=429, description="Trop de requetes")
 * )
 */
class CommunicationDocumentation
{
}
