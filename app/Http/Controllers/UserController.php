<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function posts(User $user)
    {
        $posts = $user->posts()->with(['tags', 'comments'])->latest()->paginate(12);
        return view('users.posts', compact('user', 'posts'));
    }
}
