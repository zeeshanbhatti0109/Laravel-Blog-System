<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'remember_token', 'email_verified_at', 'role', 'country_id', 'slug'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // AUTO-GENERATE SLUG WHEN USER IS CREATED OR UPDATED
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->slug = static::generateUniqueSlug($user->name);
        });

        static::updating(function ($user) {
            if ($user->isDirty('name')) {
                $user->slug = static::generateUniqueSlug($user->name);
            }
        });
    }

    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELATIONSHIPS ==========
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // ========== RBAC METHODS ==========
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEditor()
    {
        return $this->role === 'editor';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function canCreatePost()
    {
        return true;
    }

    public function canEditPost(Post $post)
    {
        if ($this->isAdmin()) return true;

        if ($this->isEditor()) return true;

        // User can edit only their own posts
        return $this->id === $post->user_id;
    }

    public function canDeletePost(Post $post)
    {
        if ($this->isAdmin()) return true;

        if ($this->isEditor()) return true;

        // User can delete only their own posts
        return $this->id === $post->user_id;
    }

    // Permission: Can view admin panel? (Only admin)

    public function canAccessAdminPanel()
    {
        return $this->isAdmin();
    }

    // Permission: Can manage users? (Only admin)

    public function canManageUsers()
    {
        return $this->isAdmin();
    }

    // Permission: Can assign roles? (Only admin)

    public function canAssignRoles()
    {
        return $this->isAdmin();
    }
}
