<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{

    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'technology', 'science', 'health', 'education', 'business',
                'sports', 'entertainment', 'politics', 'travel', 'food',
                'fashion', 'music', 'art', 'history', 'nature',
                'photography', 'gaming', 'fitness', 'finance', 'marketing',
            ]),
        ];
    }
}
