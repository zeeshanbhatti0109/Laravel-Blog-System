<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function posts(Tag $tag)
    {
        $posts = $tag->posts()->with(['tags', 'comments'])->latest()->paginate(12);
        return view('tags.posts', compact('tag', 'posts'));
    }
}
