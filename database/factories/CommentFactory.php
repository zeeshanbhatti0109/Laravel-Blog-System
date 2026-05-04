<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
   
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,  // ✅ Use existing user
            'post_id' => Post::inRandomOrder()->first()->id,  // ✅ Use existing post
            'body' => fake()->realText(rand(100, 300)),
        ];
    }
}