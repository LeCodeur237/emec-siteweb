<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $slug): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('slug', $slug);
        }

        return $this->roles()->where('slug', $slug)->exists();
    }

    public function hasPermission(string $slug): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(function (Role $role) use ($slug) {
                if (! $role->relationLoaded('permissions')) {
                    return $role->permissions()->where('slug', $slug)->exists();
                }

                return $role->permissions->contains('slug', $slug);
            });
        }

        return $this->roles()
            ->whereHas('permissions', fn ($query) => $query->where('slug', $slug))
            ->exists();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
