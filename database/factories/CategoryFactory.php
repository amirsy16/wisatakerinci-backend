<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Alam & Pegunungan',
            'Air Terjun',
            'Danau & Sungai',
            'Wisata Budaya',
            'Agrowisata',
            'Ekowisata',
            'Petualangan',
        ];

        $name = $this->faker->unique()->randomElement($names);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
