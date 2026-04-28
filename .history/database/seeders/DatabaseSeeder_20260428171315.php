<?php

namespace Database\Seeders;

use App\Models\Comment as ModelsComment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // create 30 users and each has 10 posts

        User::factory(30)->has(Post::factory(10), 'posts')->create();

        // Create 20 tags

        $tags = Tag::factory(20)->create();

        //attach random tags to each posts

        Post::all()->each(function ($post) use ($tags) {
            $post->tags()->attach(
                $tags->random(rand(2, 5))->pluck('id')->toArray()
            );
        });
            
        //create comments for each post

        Comment::factory(600)->create();
    }
}
