<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //save new comments to databse

    public function store(Request $request, Post $post)
    {

        $request->validate([
            'body' => 'required|min:3|max:1000'
        ]);

        Comment::create([
            'user_id' => 1,
            'post_id' => $post->id,
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    //delete comment to databse

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}
