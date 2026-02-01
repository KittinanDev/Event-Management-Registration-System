<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 days', '+30 days');
        $endDate = (clone $startDate)->modify('+2 hours');

        return [
            'user_id' => \App\Models\User::factory(),
            'title' => fake()->catchPhrase(),
            'description' => fake()->sentences(3, true),
            'location' => fake()->address(),
            'start_datetime' => $startDate,
            'end_datetime' => $endDate,
            'max_participants' => fake()->randomElement([10, 20, 30, 50, 100, null]),
            'max_attendees' => fake()->randomElement([10, 20, 30, 50, 100, null]),
            'current_attendees' => 0,
            'status' => 'open',
            'image' => null,
        ];
    }
}
