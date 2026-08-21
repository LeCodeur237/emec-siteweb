<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'mime_type',
        'alt_text',
        'title',
        'description',
        'size',
        'uploaded_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function socialProjects(): MorphToMany
    {
        return $this->morphedByMany(SocialProject::class, 'mediaable', 'mediaables');
    }

    public function socialActions(): MorphToMany
    {
        return $this->morphedByMany(SocialAction::class, 'mediaable', 'mediaables');
    }

    public function churches(): MorphToMany
    {
        return $this->morphedByMany(Church::class, 'mediaable', 'mediaables');
    }

    public function groups(): MorphToMany
    {
        return $this->morphedByMany(Group::class, 'mediaable', 'mediaables');
    }

    public function events(): MorphToMany
    {
        return $this->morphedByMany(Event::class, 'mediaable', 'mediaables');
    }

    public function administrativeLeaders(): MorphToMany
    {
        return $this->morphedByMany(AdministrativeLeader::class, 'mediaable', 'mediaables');
    }

    public function churchLeaders(): MorphToMany
    {
        return $this->morphedByMany(ChurchLeader::class, 'mediaable', 'mediaables');
    }

    public function groupLeaders(): MorphToMany
    {
        return $this->morphedByMany(GroupLeader::class, 'mediaable', 'mediaables');
    }

    public function preachers(): MorphToMany
    {
        return $this->morphedByMany(Preacher::class, 'mediaable', 'mediaables');
    }

    public function messages(): MorphToMany
    {
        return $this->morphedByMany(Message::class, 'mediaable', 'mediaables');
    }

    public function testimonials(): MorphToMany
    {
        return $this->morphedByMany(Testimonial::class, 'mediaable', 'mediaables');
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'mediaable', 'mediaables');
    }
}
