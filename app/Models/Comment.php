<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['user_id', 'post_id', 'parent_id', 'body'])]

class Comment extends Model
{
    use HasFactory;

    public function parent()
    {

        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies', 'user');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function isTopLevel()
    {
        return is_null($this->parent_id);
    }

    public function isReply()
    {
        return !is_null($this->parent_id);
    }
}
