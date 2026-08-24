<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="EMEC API",
 *     version="1.0.0",
 *     description="Documentation OpenAPI du backend EMEC."
 * )
 *
 * @OA\Server(
 *     url="https://api.egliseemec.org",
 *     description="Production"
 * )
 * @OA\Server(
 *     url="https://staging-api.egliseemec.org",
 *     description="Staging"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Token Laravel Sanctum. Utiliser le format: Bearer {token}"
 * )
 *
 * @OA\Tag(name="System", description="Informations systeme")
 * @OA\Tag(name="Auth", description="Authentification administration")
 *
 * @OA\Get(
 *     path="/api/v1",
 *     tags={"System"},
 *     summary="Informations de l'API",
 *     @OA\Response(
 *         response=200,
 *         description="Informations de version",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="EMEC API"),
 *             @OA\Property(property="version", type="string", example="v1")
 *         )
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/v1/health",
 *     tags={"System"},
 *     summary="Verification de sante de l'API",
 *     @OA\Response(
 *         response=200,
 *         description="API disponible",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ok")
 *         )
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/v1/auth/login",
 *     tags={"Auth"},
 *     summary="Connexion administrateur",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="admin@egliseemec.org"),
 *             @OA\Property(property="password", type="string", format="password", example="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Authentification reussie",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Authenticated."),
 *             @OA\Property(property="token_type", type="string", example="Bearer"),
 *             @OA\Property(property="access_token", type="string", example="1|token")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Identifiants invalides")
 * )
 *
 * @OA\Get(
 *     path="/api/v1/auth/me",
 *     tags={"Auth"},
 *     summary="Utilisateur connecte",
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Profil utilisateur connecte"),
 *     @OA\Response(response=401, description="Non authentifie")
 * )
 *
 * @OA\Post(
 *     path="/api/v1/auth/logout",
 *     tags={"Auth"},
 *     summary="Deconnexion de l'utilisateur connecte",
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Token courant revoque", @OA\JsonContent(
 *         @OA\Property(property="message", type="string", example="Logged out.")
 *     )),
 *     @OA\Response(response=401, description="Non authentifie")
 * )
 */
class Documentation
{
}
