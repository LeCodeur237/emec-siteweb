<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Admin RBAC", description="Gestion back-office des utilisateurs, roles et permissions")
 *
 * @OA\Schema(
 *     schema="AdminPermissionDetail",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Voir les messages"),
 *     @OA\Property(property="slug", type="string", example="messages.view"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="roles_count", type="integer", example=2),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminRoleDetail",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Administrateur"),
 *     @OA\Property(property="slug", type="string", example="admin"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="users_count", type="integer", example=4),
 *     @OA\Property(property="permissions_count", type="integer", example=12),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="permissions", type="array", @OA\Items(ref="#/components/schemas/AdminPermissionDetail"))
 * )
 *
 * @OA\Schema(
 *     schema="AdminUser",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Admin EMEC"),
 *     @OA\Property(property="email", type="string", format="email", example="admin@egliseemec.org"),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="avatar", type="string", nullable=true),
 *     @OA\Property(property="status", type="string", enum={"active","inactive"}, example="active"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/AdminRoleDetail"))
 * )
 *
 * @OA\Schema(
 *     schema="AdminUserPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Nouvel Admin"),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255, example="nouvel-admin@example.test"),
 *     @OA\Property(property="password", type="string", nullable=true, minLength=8, example="password-secret"),
 *     @OA\Property(property="phone", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="avatar", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="status", type="string", enum={"active","inactive"}, example="active"),
 *     @OA\Property(property="role_ids", type="array", @OA\Items(type="integer"), example={1,2})
 * )
 *
 * @OA\Schema(
 *     schema="AdminRolePayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Editeur messages"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="messages_editor"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="permission_ids", type="array", @OA\Items(type="integer"), example={1,2,3})
 * )
 *
 * @OA\Schema(
 *     schema="AdminPermissionPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Voir les messages"),
 *     @OA\Property(property="slug", type="string", nullable=true, maxLength=255, pattern="^[a-z0-9_.-]+$", example="messages.view"),
 *     @OA\Property(property="description", type="string", nullable=true)
 * )
 *
 * @OA\Get(path="/api/v1/admin/users", tags={"Admin RBAC"}, summary="Lister les utilisateurs admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"active","inactive"})),
 *     @OA\Parameter(name="role_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="permission_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","email","status","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminUser")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/users", tags={"Admin RBAC"}, summary="Creer un utilisateur admin", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name","email","password"}, ref="#/components/schemas/AdminUserPayload")),
 *     @OA\Response(response=201, description="Utilisateur cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminUser"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/users/{user}", tags={"Admin RBAC"}, summary="Afficher un utilisateur admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Utilisateur", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminUser"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"), @OA\Response(response=404, description="Utilisateur introuvable"))
 * @OA\Put(path="/api/v1/admin/users/{user}", tags={"Admin RBAC"}, summary="Remplacer un utilisateur admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminUserPayload")),
 *     @OA\Response(response=200, description="Utilisateur mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminUser"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/users/{user}", tags={"Admin RBAC"}, summary="Modifier un utilisateur admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminUserPayload")),
 *     @OA\Response(response=200, description="Utilisateur mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminUser"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/users/{user}", tags={"Admin RBAC"}, summary="Supprimer un utilisateur admin", security={{"sanctum":{}}},
 *     @OA\Parameter(name="user", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Utilisateur supprime"), @OA\Response(response=404, description="Utilisateur introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/roles", tags={"Admin RBAC"}, summary="Lister les roles", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="permission_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","slug","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminRoleDetail")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/roles", tags={"Admin RBAC"}, summary="Creer un role", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminRolePayload")),
 *     @OA\Response(response=201, description="Role cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminRoleDetail"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/roles/{role}", tags={"Admin RBAC"}, summary="Afficher un role", security={{"sanctum":{}}},
 *     @OA\Parameter(name="role", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Role", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminRoleDetail"))), @OA\Response(response=404, description="Role introuvable"))
 * @OA\Put(path="/api/v1/admin/roles/{role}", tags={"Admin RBAC"}, summary="Remplacer un role", security={{"sanctum":{}}},
 *     @OA\Parameter(name="role", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminRolePayload")),
 *     @OA\Response(response=200, description="Role mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminRoleDetail"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/roles/{role}", tags={"Admin RBAC"}, summary="Modifier un role", security={{"sanctum":{}}},
 *     @OA\Parameter(name="role", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminRolePayload")),
 *     @OA\Response(response=200, description="Role mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminRoleDetail"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/roles/{role}", tags={"Admin RBAC"}, summary="Supprimer un role", security={{"sanctum":{}}},
 *     @OA\Parameter(name="role", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Role supprime"), @OA\Response(response=404, description="Role introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/permissions", tags={"Admin RBAC"}, summary="Lister les permissions", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"name","slug","created_at","updated_at"})),
 *     @OA\Parameter(name="direction", in="query", @OA\Schema(type="string", enum={"asc","desc"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminPermissionDetail")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/permissions", tags={"Admin RBAC"}, summary="Creer une permission", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name"}, ref="#/components/schemas/AdminPermissionPayload")),
 *     @OA\Response(response=201, description="Permission creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPermissionDetail"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/permissions/{permission}", tags={"Admin RBAC"}, summary="Afficher une permission", security={{"sanctum":{}}},
 *     @OA\Parameter(name="permission", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Permission", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPermissionDetail"))), @OA\Response(response=404, description="Permission introuvable"))
 * @OA\Put(path="/api/v1/admin/permissions/{permission}", tags={"Admin RBAC"}, summary="Remplacer une permission", security={{"sanctum":{}}},
 *     @OA\Parameter(name="permission", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminPermissionPayload")),
 *     @OA\Response(response=200, description="Permission mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPermissionDetail"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/permissions/{permission}", tags={"Admin RBAC"}, summary="Modifier une permission", security={{"sanctum":{}}},
 *     @OA\Parameter(name="permission", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminPermissionPayload")),
 *     @OA\Response(response=200, description="Permission mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminPermissionDetail"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/permissions/{permission}", tags={"Admin RBAC"}, summary="Supprimer une permission", security={{"sanctum":{}}},
 *     @OA\Parameter(name="permission", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Permission supprimee"), @OA\Response(response=404, description="Permission introuvable"))
 */
final class AdminRbacDocumentation
{
}
