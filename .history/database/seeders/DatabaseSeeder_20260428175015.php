<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1: Create countries FIRST
        $countries = Country::factory(10)->create();
        
        // Step 2: Create roles FIRST  
        $roles = Role::factory(3)->create();
        
        // Step 3: Create users using existing countries and roles
        User::factory(30)
            ->has(Post::factory(10), 'posts')
            ->create();
        
        // Step 4: Create tags
        $tags = Tag::factory(20)->create();
        
        // Step 5: Attach tags to posts
        Post::all()->each(function ($post) use ($tags) {
            $post->tags()->attach(
                $tags->random(rand(2, 5))->pluck('id')->toArray()
            );
        });
        
        // Step 6: Create comments
        Comment::factory(600)->create();
    }
}