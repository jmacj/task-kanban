<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'       => $this->faker->word,
            'description' => $this->faker->sentence,
            'status'      => $this->faker->randomElement(['TODO', 'IN-PROGRESS', 'COMPLETED']),
            'due_date'    => Date::today()->format('Y-m-d'),
            'position'    => $this->faker->randomNumber(2),
            'user_id'     => User::factory()->create()->id,
        ];
    }
}
