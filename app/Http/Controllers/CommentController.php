<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        Comment::create([
            'user_id' => auth()->id(),  // ← Changed from hardcoded 1
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function destroy(Comment $comment)
    {
        // Admin or comment author can delete
        if (auth()->user()->isAdmin() || auth()->id() === $comment->user_id) {
            $comment->replies()->delete();
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully!');
        }
        abort(403, 'You cannot delete this comment.');
    }
}