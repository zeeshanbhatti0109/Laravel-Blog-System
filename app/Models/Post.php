<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

#[Fillable(['user_id', 'title', 'slug', 'body'])]

class Post extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Generate initial slug from title
            $slug = Str::slug($post->title);
            $originalSlug = $slug;
            $count = 1;

            // Check if slug already exists in database
            while (static::where('slug', $slug)->exists()) {
                // If exists, append number: slug-1, slug-2, etc.
                $slug = $originalSlug . '-' . $count++;
            }

            // Assign the unique slug
            $post->slug = $slug;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }
}
