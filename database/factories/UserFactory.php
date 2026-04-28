<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'country_id' => Country::inRandomOrder()->first()->id,  // ✅ Use existing country
            'role_id' => Role::inRandomOrder()->first()->id,        // ✅ Use existing role
        ];
    }
}