<?php

namespace Database\Seeders;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $faker = FakerFactory::create('en_US');

        $this->command->info('========================================');
        $this->command->info('STARTING LARGE DATA SEEDING (ENGLISH)');
        $this->command->info('========================================');

        DB::transaction(function () use ($faker) {

            // ========== STEP 1: COUNTRIES ==========
            $this->command->info('Step 1: Setting up countries...');
            
            $countries = ['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan', 'India', 'Brazil', 'Italy'];
            foreach ($countries as $country) {
                DB::table('countries')->insert([
                    'name' => $country,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ========== STEP 1b: ROLES ==========
            $this->command->info('Step 1b: Setting up roles...');
            
            $roles = ['admin', 'editor', 'user'];
            foreach ($roles as $role) {
                DB::table('roles')->insert([
                    'name' => $role,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ========== STEP 2: CREATE 1,000 USERS WITH UNIQUE SLUGS ==========
            $this->command->info('Step 2: Creating 1,000 users with unique slugs...');

            $users = [];
            $usedSlugs = [];

            for ($i = 1; $i <= 1000; $i++) {
                $name = $faker->name();
                $slug = Str::slug($name);
                
                // Make slug unique
                $originalSlug = $slug;
                $counter = 1;
                while (in_array($slug, $usedSlugs)) {
                    $slug = $originalSlug . '-' . $counter++;
                }
                $usedSlugs[] = $slug;
                
                $users[] = [
                    'name' => $name,
                    'slug' => $slug,
                    'email' => $faker->unique()->email(),
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
            $this->command->info('Step 3: Creating 50,000 posts...');

            $userIds = DB::table('users')->pluck('id')->toArray();
            $posts = [];
            $postCount = 0;

            foreach ($userIds as $userId) {
                for ($i = 1; $i <= 50; $i++) {
                    $title = $faker->realText(rand(30, 60));
                    $posts[] = [
                        'user_id' => $userId,
                        'title' => $title,
                        'slug' => Str::slug($title) . '-' . uniqid(),
                        'body' => $faker->realText(rand(500, 1000)),
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
            $usedTagSlugs = [];
            
            $englishTagWords = [
                'technology', 'science', 'health', 'education', 'business',
                'sports', 'entertainment', 'politics', 'travel', 'food',
                'fashion', 'music', 'art', 'history', 'nature',
                'photography', 'gaming', 'fitness', 'finance', 'marketing',
                'design', 'programming', 'security', 'lifestyle', 'culture',
                'environment', 'innovation', 'leadership', 'productivity', 'wellness',
                'cooking', 'gardening', 'parenting', 'relationships', 'motivation',
                'psychology', 'philosophy', 'economics', 'architecture', 'engineering',
                'astronomy', 'biology', 'chemistry', 'mathematics', 'literature',
                'poetry', 'journalism', 'cinema', 'theater', 'dance',
                'yoga', 'meditation', 'nutrition', 'sustainability', 'charity',
                'volunteering', 'community', 'startups', 'freelancing', 'remote-work',
                'artificial-intelligence', 'machine-learning', 'blockchain', 'cloud-computing', 'cybersecurity',
                'web-development', 'mobile-apps', 'data-science', 'robotics', 'automation',
                'social-media', 'content-creation', 'branding', 'advertising', 'analytics',
                'investing', 'cryptocurrency', 'real-estate', 'insurance', 'banking',
                'healthcare', 'mental-health', 'self-improvement', 'career', 'networking',
                'tutorials', 'reviews', 'interviews', 'case-studies', 'opinion',
                'breaking-news', 'features', 'how-to', 'tips', 'guides',
                'research', 'debates', 'events', 'awards', 'trends',
            ];

            for ($i = 1; $i <= 100; $i++) {
                $tagName = $englishTagWords[$i - 1];
                $tagSlug = Str::slug($tagName);
                
                // Make tag slug unique
                $originalSlug = $tagSlug;
                $counter = 1;
                while (in_array($tagSlug, $usedTagSlugs)) {
                    $tagSlug = $originalSlug . '-' . $counter++;
                }
                $usedTagSlugs[] = $tagSlug;
                
                $tags[] = [
                    'name' => $tagName,
                    'slug' => $tagSlug,
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
                shuffle($tagIds);
                $randomTagIds = array_slice($tagIds, 0, $numTags);
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
                    'body' => $faker->realText(rand(100, 300)),
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