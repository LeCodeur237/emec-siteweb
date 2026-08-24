<?php

namespace App\OpenApi;

/**
 * @OA\Tag(name="Admin Dons", description="Gestion back-office des campagnes, methodes et dons")
 * @OA\Tag(name="Admin Communication", description="Gestion back-office des messages de contact et abonnes newsletter")
 * @OA\Tag(name="Admin Configuration", description="Gestion back-office des parametres du site")
 *
 * @OA\Schema(
 *     schema="AdminDonationCampaign",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="social_project_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", example="Campagne soutien scolaire"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="goal_amount", type="number", format="float", example=1500000),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="donations_count", type="integer", example=12),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="project", type="object", nullable=true,
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="title", type="string", example="Soutien scolaire DOSC"),
 *         @OA\Property(property="slug", type="string", example="soutien-scolaire-dosc"),
 *         @OA\Property(property="status", type="string", example="active")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="AdminDonationCampaignPayload",
 *     type="object",
 *     @OA\Property(property="social_project_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="title", type="string", maxLength=255, example="Campagne soutien scolaire"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="goal_amount", type="number", format="float", minimum=0, example=1500000),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="start_date", type="string", format="date", nullable=true),
 *     @OA\Property(property="end_date", type="string", format="date", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminDonationMethod",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Orange Money"),
 *     @OA\Property(property="type", type="string", enum={"mobile_money","bank_transfer","cash","other"}, example="mobile_money"),
 *     @OA\Property(property="provider", type="string", nullable=true, example="Orange"),
 *     @OA\Property(property="account_name", type="string", nullable=true, example="EMEC DOSC"),
 *     @OA\Property(property="account_number", type="string", nullable=true, example="690000000"),
 *     @OA\Property(property="instructions", type="string", nullable=true),
 *     @OA\Property(property="active", type="boolean", example=true),
 *     @OA\Property(property="donations_count", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminDonationMethodPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Orange Money"),
 *     @OA\Property(property="type", type="string", enum={"mobile_money","bank_transfer","cash","other"}, example="mobile_money"),
 *     @OA\Property(property="provider", type="string", nullable=true, maxLength=255, example="Orange"),
 *     @OA\Property(property="account_name", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="account_number", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="instructions", type="string", nullable=true),
 *     @OA\Property(property="active", type="boolean", example=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminDonation",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="donation_campaign_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="donation_method_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="donor_name", type="string", nullable=true, example="Jean N."),
 *     @OA\Property(property="donor_email", type="string", nullable=true, format="email"),
 *     @OA\Property(property="donor_phone", type="string", nullable=true),
 *     @OA\Property(property="amount", type="number", format="float", example=25000),
 *     @OA\Property(property="currency", type="string", example="XAF"),
 *     @OA\Property(property="transaction_reference", type="string", nullable=true),
 *     @OA\Property(property="status", type="string", enum={"pending","paid","failed","cancelled","refunded"}, example="paid"),
 *     @OA\Property(property="anonymous", type="boolean", example=false),
 *     @OA\Property(property="paid_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminDonationPayload",
 *     type="object",
 *     @OA\Property(property="donation_campaign_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="donation_method_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="donor_name", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="donor_email", type="string", nullable=true, format="email", maxLength=255),
 *     @OA\Property(property="donor_phone", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="amount", type="number", format="float", minimum=0, example=25000),
 *     @OA\Property(property="currency", type="string", minLength=3, maxLength=3, example="XAF"),
 *     @OA\Property(property="transaction_reference", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="status", type="string", enum={"pending","paid","failed","cancelled","refunded"}, example="paid"),
 *     @OA\Property(property="anonymous", type="boolean", example=false),
 *     @OA\Property(property="paid_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminContactMessage",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Marie N."),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="subject", type="string", nullable=true),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="status", type="string", enum={"new","read","answered","archived"}, example="new"),
 *     @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="answered_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminContactMessagePayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", maxLength=255, example="Marie N."),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255),
 *     @OA\Property(property="phone", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="subject", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="status", type="string", enum={"new","read","answered","archived"}, example="new"),
 *     @OA\Property(property="read_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="answered_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminNewsletterSubscriber",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", nullable=true),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="status", type="string", enum={"subscribed","unsubscribed"}, example="subscribed"),
 *     @OA\Property(property="subscribed_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="unsubscribed_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminNewsletterSubscriberPayload",
 *     type="object",
 *     @OA\Property(property="name", type="string", nullable=true, maxLength=255),
 *     @OA\Property(property="email", type="string", format="email", maxLength=255),
 *     @OA\Property(property="status", type="string", enum={"subscribed","unsubscribed"}, example="subscribed"),
 *     @OA\Property(property="subscribed_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="unsubscribed_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminSiteSetting",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="key", type="string", example="site.name"),
 *     @OA\Property(property="value", type="string", nullable=true, example="EMEC"),
 *     @OA\Property(property="type", type="string", enum={"string","text","integer","float","boolean","json","url","email"}, example="string"),
 *     @OA\Property(property="group", type="string", nullable=true, example="general"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="AdminSiteSettingPayload",
 *     type="object",
 *     @OA\Property(property="key", type="string", maxLength=255, pattern="^[a-z0-9_.-]+$", example="site.name"),
 *     @OA\Property(property="value", type="string", nullable=true, example="EMEC"),
 *     @OA\Property(property="type", type="string", enum={"string","text","integer","float","boolean","json","url","email"}, example="string"),
 *     @OA\Property(property="group", type="string", nullable=true, maxLength=255, example="general")
 * )
 *
 * @OA\Get(path="/api/v1/admin/donation-campaigns", tags={"Admin Dons"}, summary="Lister les campagnes de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="social_project_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"title","start_date","end_date","created_at","updated_at","goal_amount"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminDonationCampaign")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/donation-campaigns", tags={"Admin Dons"}, summary="Creer une campagne de dons", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"title","goal_amount"}, ref="#/components/schemas/AdminDonationCampaignPayload")),
 *     @OA\Response(response=201, description="Campagne creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationCampaign"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/donation-campaigns/{donationCampaign}", tags={"Admin Dons"}, summary="Afficher une campagne de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationCampaign", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Campagne", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationCampaign"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"), @OA\Response(response=404, description="Campagne introuvable"))
 * @OA\Put(path="/api/v1/admin/donation-campaigns/{donationCampaign}", tags={"Admin Dons"}, summary="Remplacer une campagne de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationCampaign", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminDonationCampaignPayload")),
 *     @OA\Response(response=200, description="Campagne mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationCampaign"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/donation-campaigns/{donationCampaign}", tags={"Admin Dons"}, summary="Modifier une campagne de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationCampaign", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminDonationCampaignPayload")),
 *     @OA\Response(response=200, description="Campagne mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationCampaign"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/donation-campaigns/{donationCampaign}", tags={"Admin Dons"}, summary="Supprimer une campagne de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationCampaign", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Campagne supprimee"), @OA\Response(response=404, description="Campagne introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/donation-methods", tags={"Admin Dons"}, summary="Lister les methodes de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="type", in="query", @OA\Schema(type="string", enum={"mobile_money","bank_transfer","cash","other"})),
 *     @OA\Parameter(name="provider", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="active", in="query", @OA\Schema(type="boolean")),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminDonationMethod")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/donation-methods", tags={"Admin Dons"}, summary="Creer une methode de dons", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name","type"}, ref="#/components/schemas/AdminDonationMethodPayload")),
 *     @OA\Response(response=201, description="Methode creee", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationMethod"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/donation-methods/{donationMethod}", tags={"Admin Dons"}, summary="Afficher une methode de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationMethod", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Methode", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationMethod"))), @OA\Response(response=404, description="Methode introuvable"))
 * @OA\Put(path="/api/v1/admin/donation-methods/{donationMethod}", tags={"Admin Dons"}, summary="Remplacer une methode de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationMethod", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminDonationMethodPayload")),
 *     @OA\Response(response=200, description="Methode mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationMethod"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/donation-methods/{donationMethod}", tags={"Admin Dons"}, summary="Modifier une methode de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationMethod", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminDonationMethodPayload")),
 *     @OA\Response(response=200, description="Methode mise a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonationMethod"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/donation-methods/{donationMethod}", tags={"Admin Dons"}, summary="Supprimer une methode de dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donationMethod", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Methode supprimee"), @OA\Response(response=404, description="Methode introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/donations", tags={"Admin Dons"}, summary="Lister les dons", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"pending","paid","failed","cancelled","refunded"})),
 *     @OA\Parameter(name="donation_campaign_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="donation_method_id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="anonymous", in="query", @OA\Schema(type="boolean")),
 *     @OA\Parameter(name="paid_from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="paid_to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminDonation")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/donations", tags={"Admin Dons"}, summary="Creer un don", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"amount"}, ref="#/components/schemas/AdminDonationPayload")),
 *     @OA\Response(response=201, description="Don cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonation"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/donations/{donation}", tags={"Admin Dons"}, summary="Afficher un don", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donation", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Don", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonation"))), @OA\Response(response=404, description="Don introuvable"))
 * @OA\Put(path="/api/v1/admin/donations/{donation}", tags={"Admin Dons"}, summary="Remplacer un don", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donation", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminDonationPayload")),
 *     @OA\Response(response=200, description="Don mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonation"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/donations/{donation}", tags={"Admin Dons"}, summary="Modifier un don", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donation", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminDonationPayload")),
 *     @OA\Response(response=200, description="Don mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminDonation"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/donations/{donation}", tags={"Admin Dons"}, summary="Supprimer un don", security={{"sanctum":{}}},
 *     @OA\Parameter(name="donation", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Don supprime"), @OA\Response(response=404, description="Don introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/contact-messages", tags={"Admin Communication"}, summary="Lister les messages de contact", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"new","read","answered","archived"})),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"created_at","updated_at","read_at","answered_at","name","email"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminContactMessage")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/contact-messages", tags={"Admin Communication"}, summary="Creer un message de contact", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"name","email","message"}, ref="#/components/schemas/AdminContactMessagePayload")),
 *     @OA\Response(response=201, description="Message cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminContactMessage"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/contact-messages/{contactMessage}", tags={"Admin Communication"}, summary="Afficher un message de contact", security={{"sanctum":{}}},
 *     @OA\Parameter(name="contactMessage", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Message", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminContactMessage"))), @OA\Response(response=404, description="Message introuvable"))
 * @OA\Put(path="/api/v1/admin/contact-messages/{contactMessage}", tags={"Admin Communication"}, summary="Remplacer un message de contact", security={{"sanctum":{}}},
 *     @OA\Parameter(name="contactMessage", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminContactMessagePayload")),
 *     @OA\Response(response=200, description="Message mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminContactMessage"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/contact-messages/{contactMessage}", tags={"Admin Communication"}, summary="Modifier un message de contact", security={{"sanctum":{}}},
 *     @OA\Parameter(name="contactMessage", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminContactMessagePayload")),
 *     @OA\Response(response=200, description="Message mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminContactMessage"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/contact-messages/{contactMessage}", tags={"Admin Communication"}, summary="Supprimer un message de contact", security={{"sanctum":{}}},
 *     @OA\Parameter(name="contactMessage", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Message supprime"), @OA\Response(response=404, description="Message introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/newsletter-subscribers", tags={"Admin Communication"}, summary="Lister les abonnes newsletter", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string", enum={"subscribed","unsubscribed"})),
 *     @OA\Parameter(name="from", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Parameter(name="to", in="query", @OA\Schema(type="string", format="date")),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminNewsletterSubscriber")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/newsletter-subscribers", tags={"Admin Communication"}, summary="Creer un abonne newsletter", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"email"}, ref="#/components/schemas/AdminNewsletterSubscriberPayload")),
 *     @OA\Response(response=201, description="Abonne cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminNewsletterSubscriber"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/newsletter-subscribers/{newsletterSubscriber}", tags={"Admin Communication"}, summary="Afficher un abonne newsletter", security={{"sanctum":{}}},
 *     @OA\Parameter(name="newsletterSubscriber", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Abonne", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminNewsletterSubscriber"))), @OA\Response(response=404, description="Abonne introuvable"))
 * @OA\Put(path="/api/v1/admin/newsletter-subscribers/{newsletterSubscriber}", tags={"Admin Communication"}, summary="Remplacer un abonne newsletter", security={{"sanctum":{}}},
 *     @OA\Parameter(name="newsletterSubscriber", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminNewsletterSubscriberPayload")),
 *     @OA\Response(response=200, description="Abonne mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminNewsletterSubscriber"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/newsletter-subscribers/{newsletterSubscriber}", tags={"Admin Communication"}, summary="Modifier un abonne newsletter", security={{"sanctum":{}}},
 *     @OA\Parameter(name="newsletterSubscriber", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminNewsletterSubscriberPayload")),
 *     @OA\Response(response=200, description="Abonne mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminNewsletterSubscriber"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/newsletter-subscribers/{newsletterSubscriber}", tags={"Admin Communication"}, summary="Supprimer un abonne newsletter", security={{"sanctum":{}}},
 *     @OA\Parameter(name="newsletterSubscriber", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Abonne supprime"), @OA\Response(response=404, description="Abonne introuvable"))
 *
 * @OA\Get(path="/api/v1/admin/site-settings", tags={"Admin Configuration"}, summary="Lister les parametres du site", security={{"sanctum":{}}},
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="type", in="query", @OA\Schema(type="string", enum={"string","text","integer","float","boolean","json","url","email"})),
 *     @OA\Parameter(name="group", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="sort", in="query", @OA\Schema(type="string", enum={"key","type","group","created_at","updated_at"})),
 *     @OA\Response(response=200, description="Liste paginee", @OA\JsonContent(@OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AdminSiteSetting")), @OA\Property(property="links", ref="#/components/schemas/PaginationLinks"), @OA\Property(property="meta", ref="#/components/schemas/PaginationMeta"))),
 *     @OA\Response(response=401, description="Non authentifie"), @OA\Response(response=403, description="Acces refuse"))
 * @OA\Post(path="/api/v1/admin/site-settings", tags={"Admin Configuration"}, summary="Creer un parametre du site", security={{"sanctum":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(required={"key"}, ref="#/components/schemas/AdminSiteSettingPayload")),
 *     @OA\Response(response=201, description="Parametre cree", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSiteSetting"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Get(path="/api/v1/admin/site-settings/{siteSetting}", tags={"Admin Configuration"}, summary="Afficher un parametre du site", security={{"sanctum":{}}},
 *     @OA\Parameter(name="siteSetting", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Parametre", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSiteSetting"))), @OA\Response(response=404, description="Parametre introuvable"))
 * @OA\Put(path="/api/v1/admin/site-settings/{siteSetting}", tags={"Admin Configuration"}, summary="Remplacer un parametre du site", security={{"sanctum":{}}},
 *     @OA\Parameter(name="siteSetting", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSiteSettingPayload")),
 *     @OA\Response(response=200, description="Parametre mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSiteSetting"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Patch(path="/api/v1/admin/site-settings/{siteSetting}", tags={"Admin Configuration"}, summary="Modifier un parametre du site", security={{"sanctum":{}}},
 *     @OA\Parameter(name="siteSetting", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AdminSiteSettingPayload")),
 *     @OA\Response(response=200, description="Parametre mis a jour", @OA\JsonContent(@OA\Property(property="data", ref="#/components/schemas/AdminSiteSetting"))), @OA\Response(response=422, description="Validation echouee"))
 * @OA\Delete(path="/api/v1/admin/site-settings/{siteSetting}", tags={"Admin Configuration"}, summary="Supprimer un parametre du site", security={{"sanctum":{}}},
 *     @OA\Parameter(name="siteSetting", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=204, description="Parametre supprime"), @OA\Response(response=404, description="Parametre introuvable"))
 */
final class AdminDonationsCommunicationSettingsDocumentation
{
}
