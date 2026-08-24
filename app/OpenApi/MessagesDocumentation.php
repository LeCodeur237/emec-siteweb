<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Messages", description="Messages, predicateurs, categories et series")
 *
 * @OA\Schema(
 *     schema="Preacher",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Pasteur Jean"),
 *     @OA\Property(property="slug", type="string", example="pasteur-jean"),
 *     @OA\Property(property="role", type="string", nullable=true, example="Pasteur principal"),
 *     @OA\Property(property="bio", type="string", nullable=true, example="Predicateur invite."),
 *     @OA\Property(property="image", type="string", nullable=true, example="https://api.egliseemec.org/storage/preachers/pasteur-jean.jpg"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="MessageCategory",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Foi"),
 *     @OA\Property(property="slug", type="string", example="foi"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Messages sur la foi."),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="MessageSeries",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Fondements"),
 *     @OA\Property(property="slug", type="string", example="fondements"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Serie sur les fondements de la foi."),
 *     @OA\Property(property="cover_image", type="string", nullable=true, example="https://api.egliseemec.org/storage/series/fondements.jpg"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="Message",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="preacher_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="message_category_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="message_series_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", example="Marcher par la foi"),
 *     @OA\Property(property="slug", type="string", example="marcher-par-la-foi"),
 *     @OA\Property(property="excerpt", type="string", nullable=true, example="Un message sur la foi active."),
 *     @OA\Property(property="content", type="string", nullable=true, example="Contenu du message."),
 *     @OA\Property(property="preached_at", type="string", format="date", nullable=true, example="2026-08-21"),
 *     @OA\Property(property="duration", type="string", nullable=true, example="45 min"),
 *     @OA\Property(property="youtube_video_id", type="string", nullable=true, example="dQw4w9WgXcQ"),
 *     @OA\Property(property="youtube_url", type="string", nullable=true, example="https://www.youtube.com/watch?v=dQw4w9WgXcQ"),
 *     @OA\Property(property="audio_url", type="string", nullable=true, example="https://api.egliseemec.org/storage/messages/audio.mp3"),
 *     @OA\Property(property="pdf_url", type="string", nullable=true, example="https://api.egliseemec.org/storage/messages/notes.pdf"),
 *     @OA\Property(property="thumbnail", type="string", nullable=true, example="https://api.egliseemec.org/storage/messages/thumb.jpg"),
 *     @OA\Property(property="featured", type="boolean", example=true),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="views", type="integer", example=120),
 *     @OA\Property(property="preacher", ref="#/components/schemas/Preacher", nullable=true),
 *     @OA\Property(property="category", ref="#/components/schemas/MessageCategory", nullable=true),
 *     @OA\Property(property="series", ref="#/components/schemas/MessageSeries", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="PaginationLinks",
 *     type="object",
 *     @OA\Property(property="first", type="string", nullable=true),
 *     @OA\Property(property="last", type="string", nullable=true),
 *     @OA\Property(property="prev", type="string", nullable=true),
 *     @OA\Property(property="next", type="string", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="from", type="integer", nullable=true, example=1),
 *     @OA\Property(property="last_page", type="integer", example=1),
 *     @OA\Property(property="path", type="string", example="https://api.egliseemec.org/api/v1/messages"),
 *     @OA\Property(property="per_page", type="integer", example=20),
 *     @OA\Property(property="to", type="integer", nullable=true, example=20),
 *     @OA\Property(property="total", type="integer", example=50)
 * )
 *
 * @OA\Get(
 *     path="/api/v1/messages",
 *     tags={"Messages"},
 *     summary="Lister les messages publics publies",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="preacher_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="message_category_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="message_series_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"preached_at","title","created_at","views"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(
 *         response=200,
 *         description="Liste paginee des messages",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Message")),
 *             @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/messages/{slug}",
 *     tags={"Messages"},
 *     summary="Afficher un message public",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Message", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Message"))),
 *     @OA\Response(response=404, description="Message introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/preachers",
 *     tags={"Messages"},
 *     summary="Lister les predicateurs publics",
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=120)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(
 *         response=200,
 *         description="Liste paginee des predicateurs",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Preacher")),
 *             @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"),
 *             @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/preachers/{slug}",
 *     tags={"Messages"},
 *     summary="Afficher un predicateur public",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Predicateur", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/Preacher"))),
 *     @OA\Response(response=404, description="Predicateur introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/message-categories",
 *     tags={"Messages"},
 *     summary="Lister les categories de messages",
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des categories",
 *         @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/MessageCategory")))
 *     ),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/message-categories/{slug}",
 *     tags={"Messages"},
 *     summary="Afficher une categorie de messages",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Categorie", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/MessageCategory"))),
 *     @OA\Response(response=404, description="Categorie introuvable")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/message-series",
 *     tags={"Messages"},
 *     summary="Lister les series de messages",
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(
 *         response=200,
 *         description="Liste des series",
 *         @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/MessageSeries")))
 *     ),
 *     @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/message-series/{slug}",
 *     tags={"Messages"},
 *     summary="Afficher une serie de messages",
 *     @OA\Parameter(name="slug", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Serie", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/MessageSeries"))),
 *     @OA\Response(response=404, description="Serie introuvable")
 * )
 */
class MessagesDocumentation
{
}
