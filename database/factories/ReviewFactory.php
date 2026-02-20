<?php

namespace Database\Factories;

use App\Models\Destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $comments = [
            'Pemandangannya luar biasa indah, sangat recommended!',
            'Tempatnya bersih dan terawat, petugas ramah.',
            'Wisata yang wajib dikunjungi saat ke Kerinci.',
            'Udara sejuk, cocok untuk liburan keluarga.',
            'Akses jalannya masih perlu diperbaiki tapi destinasinya bagus.',
            'Tiket masuk terjangkau, pengalaman yang tak terlupakan.',
            'Spot foto yang keren, banyak pilihan angle.',
            'Sarana dan prasarana lengkap, aman untuk anak-anak.',
        ];

        return [
            'user_id'        => User::factory(),
            'destination_id' => Destination::factory(),
            'rating'         => $this->faker->numberBetween(3, 5),
            'comment'        => $this->faker->randomElement($comments),
            'approved_at'    => $this->faker->optional(0.8)->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function approved(): static
    {
        return $this->state(['approved_at' => now()]);
    }
}
