<?php

use App\Models\AdministrativeLeader;
use App\Models\Church;
use App\Models\ChurchLeader;
use App\Models\Event;
use App\Models\Group;
use App\Models\GroupLeader;
use App\Models\Message;
use App\Models\Preacher;
use App\Models\SocialAction;
use App\Models\SocialProject;
use App\Models\Testimonial;
use App\Models\User;

return [
    'disk' => env('MEDIA_DISK', 'public'),

    'max_size_kb' => [
        'image' => (int) env('MEDIA_MAX_IMAGE_KB', 10240),
        'document' => (int) env('MEDIA_MAX_DOCUMENT_KB', 20480),
    ],

    'upload_rate_limit_per_minute' => (int) env('MEDIA_UPLOAD_RATE_LIMIT_PER_MINUTE', 30),

    'allowed_extensions' => [
        'jpg',
        'jpeg',
        'png',
        'webp',
        'pdf',
    ],

    'allowed_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
    ],

    'image_mime_types' => [
        'image/jpeg',
        'image/png',
        'image/webp',
    ],

    'document_mime_types' => [
        'application/pdf',
    ],

    'attachable_models' => [
        'church' => Church::class,
        'group' => Group::class,
        'event' => Event::class,
        'administrative_leader' => AdministrativeLeader::class,
        'church_leader' => ChurchLeader::class,
        'group_leader' => GroupLeader::class,
        'preacher' => Preacher::class,
        'message' => Message::class,
        'social_project' => SocialProject::class,
        'social_action' => SocialAction::class,
        'testimonial' => Testimonial::class,
        'user' => User::class,
    ],
];
