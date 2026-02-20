<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    private static array $destinations = [
        ['name' => 'Gunung Kerinci', 'location' => 'Kayu Aro, Kerinci, Jambi'],
        ['name' => 'Danau Kerinci', 'location' => 'Kerinci, Jambi'],
        ['name' => 'Air Terjun Telun Berasap', 'location' => 'Kayu Aro, Kerinci, Jambi'],
        ['name' => 'Kebun Teh Kayu Aro', 'location' => 'Kayu Aro, Kerinci, Jambi'],
        ['name' => 'Danau Gunung Tujuh', 'location' => 'Kayu Aro, Kerinci, Jambi'],
        ['name' => 'Air Terjun Pancuran Rayo', 'location' => 'Sungai Penuh, Jambi'],
        ['name' => 'Bukit Kayangan', 'location' => 'Kerinci, Jambi'],
        ['name' => 'Talang Kemulun', 'location' => 'Gunung Raya, Kerinci, Jambi'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $item = self::$destinations[self::$index % count(self::$destinations)];
        self::$index++;

        $name = $item['name'];

        return [
            'name'         => $name,
            'slug'         => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 999),
            'description'  => $this->faker->paragraphs(3, true),
            'location'     => $item['location'],
            'map_url'      => 'https://maps.google.com/?q=' . urlencode($item['location']),
            'ticket_price' => $this->faker->randomElement([0, 5000, 10000, 15000, 20000, 25000]),
            'open_hours'   => $this->faker->randomElement(['07:00 - 17:00', '06:00 - 18:00', '08:00 - 16:00', 'Setiap hari 24 jam']),
            'status'       => $this->faker->randomElement(['active', 'active', 'active', 'draft']),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => 'active']);
    }
}
