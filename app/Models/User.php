<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Sluggable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'slug',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value) && Str::startsWith($value, '$2y$') === false) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Sluggable configuration.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ],
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return strtolower(optional($this->role)->name) === strtolower($roleName);
    }

    public function isRole(string $roleName): bool
    {
        return $this->hasRole($roleName);
    }
}
