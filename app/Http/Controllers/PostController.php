<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'tags', 'comments'])->latest()->paginate(12);
        return view('posts.index', compact('posts'));
    }

    // CREATE POST - Anyone logged in can access
    public function create()
    {
        // No restriction - all logged-in users can create posts
        return view('posts.create');
    }

    // STORE POST - Anyone logged in can create
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'body' => 'required|min:10',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        // Handle tags
        if ($request->tags) {
            $tagNames = explode(',', $request->tags);
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $post->tags()->attach($tag->id);
            }
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post created successfully');
    }

    public function show(Post $post)
    {
        $post->load([
            'user',
            'tags',
            'comments' => fn($q) => $q->whereNull('parent_id'),
            'comments.user',
            'comments.replies',
            'comments.replies.user'
        ]);
        return view('posts.show', compact('post'));
    }

    // EDIT POST - Check permission
    public function edit(Post $post)
    {
        if (!auth()->user()->canEditPost($post)) {
            abort(403, 'You do not have permission to edit this post.');
        }
        return view('posts.edit', compact('post'));
    }

    // UPDATE POST - Check permission
    public function update(Request $request, Post $post)
    {
        if (!auth()->user()->canEditPost($post)) {
            abort(403, 'You do not have permission to edit this post.');
        }

        $request->validate([
            'title' => 'required|min:3|max:255',
            'body' => 'required|min:10',
        ]);

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        // Sync tags
        if ($request->tags) {
            $tagNames = explode(',', $request->tags);
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully');
    }

    // DELETE POST - Check permission
    public function destroy(Post $post)
    {
        if (!auth()->user()->canDeletePost($post)) {
            abort(403, 'You do not have permission to delete this post.');
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }
}
