<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DestinationImage>
 */
class DestinationImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'destination_id' => Destination::factory(),
            'image_path'     => 'destinations/' . $this->faker->uuid() . '.jpg',
            'is_primary'     => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(['is_primary' => true]);
    }
}
