<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        
        $this->command->info('========================================');
        $this->command->info('STARTING LARGE DATA SEEDING');
        $this->command->info('========================================');
        
        DB::transaction(function () {
            
            // ========== STEP 1: COUNTRIES & ROLES ==========
            $this->command->info('Step 1: Setting up countries and roles...');
            
            if (DB::table('countries')->count() == 0) {
                for ($i = 1; $i <= 10; $i++) {
                    DB::table('countries')->insert([
                        'name' => fake()->country(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            if (DB::table('roles')->count() == 0) {
                $roles = ['admin', 'editor', 'user'];
                foreach ($roles as $role) {
                    DB::table('roles')->insert([
                        'name' => $role,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            
            // ========== STEP 2: CREATE 1,000 USERS WITH SLUGS ==========
            $this->command->info('Step 2: Creating 1,000 users with slugs...');
            
            $users = [];
            for ($i = 1; $i <= 1000; $i++) {
                $name = fake()->name();
                $users[] = [
                    'name' => $name,
                    'slug' => Str::slug($name),  // ← GENERATE SLUG HERE
                    'email' => fake()->unique()->email(),
                    'country_id' => rand(1, 10),
                    'role_id' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                if ($i % 100 == 0) {
                    DB::table('users')->insert($users);
                    $users = [];
                    $this->command->info("  → Created $i users...");
                }
            }
            if (!empty($users)) {
                DB::table('users')->insert($users);
            }
            $this->command->info("  ✓ Total users: " . DB::table('users')->count());
            
            // ========== STEP 3: CREATE 50,000 POSTS ==========
            $this->command->info('Step 3: Creating 50,000 posts (50 per user)...');
            
            $userIds = DB::table('users')->pluck('id')->toArray();
            $posts = [];
            $postCount = 0;
            
            foreach ($userIds as $userId) {
                for ($i = 1; $i <= 50; $i++) {
                    $title = fake()->sentence();
                    $posts[] = [
                        'user_id' => $userId,
                        'title' => $title,
                        'slug' => Str::slug($title) . '-' . uniqid(),
                        'body' => fake()->paragraphs(3, true),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $postCount++;
                    
                    if ($postCount % 1000 == 0) {
                        DB::table('posts')->insert($posts);
                        $posts = [];
                        $this->command->info("  → Created $postCount posts...");
                    }
                }
            }
            if (!empty($posts)) {
                DB::table('posts')->insert($posts);
            }
            $this->command->info("  ✓ Total posts: " . DB::table('posts')->count());
            
            // ========== STEP 4: CREATE 100 TAGS ==========
            $this->command->info('Step 4: Creating 100 tags...');
            
            $tags = [];
            for ($i = 1; $i <= 100; $i++) {
                $tagName = fake()->unique()->word();
                $tags[] = [
                    'name' => $tagName,
                    'slug' => Str::slug($tagName),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('tags')->insert($tags);
            $tagIds = DB::table('tags')->pluck('id')->toArray();
            $this->command->info("  ✓ Total tags: " . DB::table('tags')->count());
            
            // ========== STEP 5: ATTACH TAGS TO POSTS ==========
            $this->command->info('Step 5: Attaching tags to posts...');
            
            $postIds = DB::table('posts')->pluck('id')->toArray();
            $pivotData = [];
            $pivotCount = 0;
            
            foreach ($postIds as $postId) {
                $numTags = rand(2, 5);
                $randomTagIds = (array)array_rand(array_flip($tagIds), $numTags);
                foreach ($randomTagIds as $tagId) {
                    $pivotData[] = [
                        'post_id' => $postId,
                        'tag_id' => $tagId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $pivotCount++;
                    
                    if ($pivotCount % 5000 == 0) {
                        DB::table('post_tag')->insert($pivotData);
                        $pivotData = [];
                        $this->command->info("  → Attached $pivotCount relations...");
                    }
                }
            }
            if (!empty($pivotData)) {
                DB::table('post_tag')->insert($pivotData);
            }
            $this->command->info("  ✓ Total relations: " . DB::table('post_tag')->count());
            
            // ========== STEP 6: CREATE 250,000 COMMENTS ==========
            $this->command->info('Step 6: Creating 250,000 comments...');
            
            $comments = [];
            $commentCount = 0;
            
            for ($i = 1; $i <= 250000; $i++) {
                $comments[] = [
                    'user_id' => $userIds[array_rand($userIds)],
                    'post_id' => $postIds[array_rand($postIds)],
                    'parent_id' => null,
                    'body' => fake()->paragraph(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $commentCount++;
                
                if ($commentCount % 5000 == 0) {
                    DB::table('comments')->insert($comments);
                    $comments = [];
                    $this->command->info("  → Created $commentCount comments...");
                }
            }
            if (!empty($comments)) {
                DB::table('comments')->insert($comments);
            }
            $this->command->info("  ✓ Total comments: " . DB::table('comments')->count());
            
            // ========== FINAL SUMMARY ==========
            $this->command->info('========================================');
            $this->command->info('SEEDING COMPLETED SUCCESSFULLY!');
            $this->command->info('========================================');
            $this->command->info("📊 FINAL COUNTS:");
            $this->command->info("   Users:    " . DB::table('users')->count());
            $this->command->info("   Posts:    " . DB::table('posts')->count());
            $this->command->info("   Comments: " . DB::table('comments')->count());
            $this->command->info("   Tags:     " . DB::table('tags')->count());
            $this->command->info("   Relations:" . DB::table('post_tag')->count());
            $this->command->info('========================================');
        });
    }
}