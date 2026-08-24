<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Admin Messages", description="Gestion back-office des messages et taxonomies")
 *
 * @OA\Schema(
 *     schema="AdminMessage",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Message"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true, example="2026-08-21T17:30:00.000000Z")
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminMessagePayload",
 *     type="object",
 *     @OA\Property(property="preacher_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="message_category_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="message_series_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, example="Marcher par la foi"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="marcher-par-la-foi"),
 *     @OA\Property(property="excerpt", type="string", nullable=true, example="Un message sur la foi active."),
 *     @OA\Property(property="content", type="string", nullable=true, example="Contenu du message."),
 *     @OA\Property(property="preached_at", type="string", format="date", nullable=true, example="2026-08-21"),
 *     @OA\Property(property="duration", type="string", nullable=true, maxLength=50, example="45 min"),
 *     @OA\Property(property="youtube_video_id", type="string", nullable=true, maxLength=100, example="abc123"),
 *     @OA\Property(property="youtube_url", type="string", nullable=true, example="https://www.youtube.com/watch?v=abc123"),
 *     @OA\Property(property="audio_url", type="string", nullable=true, example="https://api.egliseemec.org/storage/messages/audio.mp3"),
 *     @OA\Property(property="pdf_url", type="string", nullable=true, example="https://api.egliseemec.org/storage/messages/notes.pdf"),
 *     @OA\Property(property="thumbnail", type="string", nullable=true, example="https://api.egliseemec.org/storage/messages/thumb.jpg"),
 *     @OA\Property(property="featured", type="boolean", example=false),
 *     @OA\Property(property="status", type="string", enum={"draft","published","archived"}, example="draft")
 * )
 *
 * @OA\Schema(
 *     schema="AdminPreacher",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Preacher"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="messages_count", type="integer", example=4),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminPreacherPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Pasteur Jean"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="pasteur-jean"),
 *     @OA\Property(property="role", type="string", nullable=true, maxLength=255, example="Pasteur principal"),
 *     @OA\Property(property="bio", type="string", nullable=true, example="Biographie du predicateur."),
 *     @OA\Property(property="image", type="string", nullable=true, maxLength=255, example="https://api.egliseemec.org/storage/preachers/jean.jpg"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminMessageCategory",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/MessageCategory"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="messages_count", type="integer", example=8),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminMessageCategoryPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Foi"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="foi"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Messages sur la foi."),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminMessageSeries",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/MessageSeries"),
 *         @OA\Schema(type="object",
 *             @OA\Property(property="messages_count", type="integer", example=3),
 *             @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *             @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 *         )
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="AdminMessageSeriesPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Fondements"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="fondements"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Serie sur les fondements."),
 *     @OA\Property(property="cover_image", type="string", nullable=true, maxLength=255, example="https://api.egliseemec.org/storage/series/fondements.jpg"),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Get(path="/api/v1/admin/messages", tags={"Admin Messages"}, summary="Lister les messages admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=255)),
 *     @OA\Parameter(name="preacher_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="message_category_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="message_series_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"draft","published","archived"})),
 *     @OA\Parameter(name="featured", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"preached_at","title","created_at","updated_at","views"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminMessage")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise"), @OA\Response(response=422, description="Parametres invalides")
 * )
 *
 * @OA\Post(path="/api/v1/admin/messages", tags={"Admin Messages"}, summary="Creer un message", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"title"}, ref="#/components/schemas/AdminMessagePayload")),
 *     @OA\Response(response=201, description="Message cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessage"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.create requise"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Get(path="/api/v1/admin/messages/{message}", tags={"Admin Messages"}, summary="Afficher un message admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="message", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Message", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessage"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise"), @OA\Response(response=404, description="Message introuvable")
 * )
 *
 * @OA\Put(path="/api/v1/admin/messages/{message}", tags={"Admin Messages"}, summary="Remplacer un message", security={{"sanctum":{}}},
 *     @OA\Parameter(name="message", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminMessagePayload")),
 *     @OA\Response(response=200, description="Message mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessage"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Message introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Patch(path="/api/v1/admin/messages/{message}", tags={"Admin Messages"}, summary="Modifier partiellement un message", security={{"sanctum":{}}},
 *     @OA\Parameter(name="message", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminMessagePayload")),
 *     @OA\Response(response=200, description="Message mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessage"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Message introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Delete(path="/api/v1/admin/messages/{message}", tags={"Admin Messages"}, summary="Supprimer un message", security={{"sanctum":{}}},
 *     @OA\Parameter(name="message", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Message supprime"), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.delete requise"), @OA\Response(response=404, description="Message introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/admin/preachers", tags={"Admin Messages"}, summary="Lister les predicateurs admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=255)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","role","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminPreacher")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise")
 * )
 *
 * @OA\Post(path="/api/v1/admin/preachers", tags={"Admin Messages"}, summary="Creer un predicateur", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminPreacherPayload")),
 *     @OA\Response(response=201, description="Predicateur cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPreacher"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.create requise"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Get(path="/api/v1/admin/preachers/{preacher}", tags={"Admin Messages"}, summary="Afficher un predicateur admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="preacher", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Predicateur", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPreacher"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise"), @OA\Response(response=404, description="Predicateur introuvable")
 * )
 *
 * @OA\Put(path="/api/v1/admin/preachers/{preacher}", tags={"Admin Messages"}, summary="Remplacer un predicateur", security={{"sanctum":{}}},
 *     @OA\Parameter(name="preacher", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminPreacherPayload")),
 *     @OA\Response(response=200, description="Predicateur mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPreacher"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Predicateur introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Patch(path="/api/v1/admin/preachers/{preacher}", tags={"Admin Messages"}, summary="Modifier partiellement un predicateur", security={{"sanctum":{}}},
 *     @OA\Parameter(name="preacher", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminPreacherPayload")),
 *     @OA\Response(response=200, description="Predicateur mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPreacher"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Predicateur introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Delete(path="/api/v1/admin/preachers/{preacher}", tags={"Admin Messages"}, summary="Supprimer un predicateur", security={{"sanctum":{}}},
 *     @OA\Parameter(name="preacher", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Predicateur supprime"), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.delete requise"), @OA\Response(response=404, description="Predicateur introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/admin/message-categories", tags={"Admin Messages"}, summary="Lister les categories admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=255)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminMessageCategory")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise")
 * )
 *
 * @OA\Post(path="/api/v1/admin/message-categories", tags={"Admin Messages"}, summary="Creer une categorie", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminMessageCategoryPayload")),
 *     @OA\Response(response=201, description="Categorie creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageCategory"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.create requise"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Get(path="/api/v1/admin/message-categories/{messageCategory}", tags={"Admin Messages"}, summary="Afficher une categorie admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageCategory", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Categorie", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageCategory"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise"), @OA\Response(response=404, description="Categorie introuvable")
 * )
 *
 * @OA\Put(path="/api/v1/admin/message-categories/{messageCategory}", tags={"Admin Messages"}, summary="Remplacer une categorie", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageCategory", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminMessageCategoryPayload")),
 *     @OA\Response(response=200, description="Categorie mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageCategory"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Categorie introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Patch(path="/api/v1/admin/message-categories/{messageCategory}", tags={"Admin Messages"}, summary="Modifier partiellement une categorie", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageCategory", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminMessageCategoryPayload")),
 *     @OA\Response(response=200, description="Categorie mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageCategory"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Categorie introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Delete(path="/api/v1/admin/message-categories/{messageCategory}", tags={"Admin Messages"}, summary="Supprimer une categorie", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageCategory", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Categorie supprimee"), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.delete requise"), @OA\Response(response=404, description="Categorie introuvable")
 * )
 *
 * @OA\Get(path="/api/v1/admin/message-series", tags={"Admin Messages"}, summary="Lister les series admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", minimum=1)),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string", maxLength=255)),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminMessageSeries")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise")
 * )
 *
 * @OA\Post(path="/api/v1/admin/message-series", tags={"Admin Messages"}, summary="Creer une serie", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminMessageSeriesPayload")),
 *     @OA\Response(response=201, description="Serie creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageSeries"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.create requise"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Get(path="/api/v1/admin/message-series/{messageSeries}", tags={"Admin Messages"}, summary="Afficher une serie admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageSeries", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Serie", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageSeries"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.view requise"), @OA\Response(response=404, description="Serie introuvable")
 * )
 *
 * @OA\Put(path="/api/v1/admin/message-series/{messageSeries}", tags={"Admin Messages"}, summary="Remplacer une serie", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageSeries", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminMessageSeriesPayload")),
 *     @OA\Response(response=200, description="Serie mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageSeries"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Serie introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Patch(path="/api/v1/admin/message-series/{messageSeries}", tags={"Admin Messages"}, summary="Modifier partiellement une serie", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageSeries", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminMessageSeriesPayload")),
 *     @OA\Response(response=200, description="Serie mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminMessageSeries"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.update requise"), @OA\Response(response=404, description="Serie introuvable"), @OA\Response(response=422, description="Donnees invalides")
 * )
 *
 * @OA\Delete(path="/api/v1/admin/message-series/{messageSeries}", tags={"Admin Messages"}, summary="Supprimer une serie", security={{"sanctum":{}}},
 *     @OA\Parameter(name="messageSeries", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Serie supprimee"), @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Permission messages.delete requise"), @OA\Response(response=404, description="Serie introuvable")
 * )
 */
class AdminMessagesDocumentation
{
}
