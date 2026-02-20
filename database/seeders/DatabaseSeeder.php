<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Destination;
use App\Models\DestinationImage;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin & Sample Users ──────────────────────────────────────────────
        User::factory()->create([
            'name'     => 'Admin Wisker',
            'email'    => 'admin@wisker.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $users = User::factory(10)->create();

        // ── Categories ────────────────────────────────────────────────────────
        $categoryData = [
            ['name' => 'Alam & Pegunungan', 'slug' => 'alam-pegunungan'],
            ['name' => 'Air Terjun',        'slug' => 'air-terjun'],
            ['name' => 'Danau & Sungai',    'slug' => 'danau-sungai'],
            ['name' => 'Wisata Budaya',     'slug' => 'wisata-budaya'],
            ['name' => 'Agrowisata',        'slug' => 'agrowisata'],
            ['name' => 'Ekowisata',         'slug' => 'ekowisata'],
            ['name' => 'Petualangan',       'slug' => 'petualangan'],
        ];

        foreach ($categoryData as $cat) {
            Category::create($cat);
        }

        $categories = Category::all();

        // ── Destinations ──────────────────────────────────────────────────────
        $destinationData = [
            [
                'name'         => 'Gunung Kerinci',
                'slug'         => 'gunung-kerinci',
                'description'  => 'Gunung Kerinci adalah gunung berapi tertinggi di Indonesia dengan ketinggian 3.805 mdpl. Terletak di Pegunungan Bukit Barisan, gunung ini menawarkan pemandangan alam yang spektakuler dan menjadi surga bagi para pendaki.',
                'location'     => 'Kayu Aro, Kerinci, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-1.6970,101.2641',
                'ticket_price' => 25000,
                'open_hours'   => '06:00 - 18:00',
                'status'       => 'active',
                'categories'   => ['alam-pegunungan', 'petualangan'],
            ],
            [
                'name'         => 'Danau Kerinci',
                'slug'         => 'danau-kerinci',
                'description'  => 'Danau Kerinci adalah danau alami yang indah di Kabupaten Kerinci. Dengan luas sekitar 4.200 hektar, danau ini menjadi habitat berbagai jenis ikan dan satwa liar serta menawarkan pemandangan yang memukau.',
                'location'     => 'Kerinci, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-2.1833,101.5417',
                'ticket_price' => 10000,
                'open_hours'   => '07:00 - 17:00',
                'status'       => 'active',
                'categories'   => ['danau-sungai', 'ekowisata'],
                'primary_image'=> 'destinations/danaukerinci.jpg',
            ],
            [
                'name'         => 'Air Terjun Telun Berasap',
                'slug'         => 'air-terjun-telun-berasap',
                'description'  => 'Air Terjun Telun Berasap merupakan salah satu air terjun paling fotogenik di Kerinci. Airnya jatuh dari ketinggian sekitar 50 meter menciptakan kabut air yang indah seperti asap.',
                'location'     => 'Kayu Aro, Kerinci, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-1.6517,101.2661',
                'ticket_price' => 15000,
                'open_hours'   => '07:00 - 17:00',
                'status'       => 'active',
                'categories'   => ['air-terjun', 'ekowisata'],
                'primary_image'=> 'destinations/airterjun.jpg',
            ],
            [
                'name'         => 'Kebun Teh Kayu Aro',
                'slug'         => 'kebun-teh-kayu-aro',
                'description'  => 'Kebun Teh Kayu Aro adalah salah satu perkebunan teh tertua dan tertinggi di dunia, berada pada ketinggian 1.400–1.600 mdpl. Hamparan hijau perkebunan teh dengan latar belakang Gunung Kerinci menjadikan surga fotografi.',
                'location'     => 'Kayu Aro, Kerinci, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-1.6784,101.3167',
                'ticket_price' => 5000,
                'open_hours'   => '08:00 - 17:00',
                'status'       => 'active',
                'categories'   => ['agrowisata', 'ekowisata'],
                'primary_image'=> 'destinations/kayuaro.jpg',
            ],
            [
                'name'         => 'Danau Gunung Tujuh',
                'slug'         => 'danau-gunung-tujuh',
                'description'  => 'Danau Gunung Tujuh adalah danau kaldera tertinggi di Asia Tenggara pada ketinggian 1.996 mdpl. Untuk mencapainya, pengunjung perlu trekking 2–3 jam melalui hutan tropis yang lebat.',
                'location'     => 'Kayu Aro, Kerinci, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-1.8333,101.3333',
                'ticket_price' => 20000,
                'open_hours'   => '06:00 - 18:00',
                'status'       => 'active',
                'categories'   => ['danau-sungai', 'alam-pegunungan', 'petualangan'],
                'primary_image'=> 'destinations/gunungtujuh.jpg',
            ],
            [
                'name'         => 'Air Terjun Pancuran Rayo',
                'slug'         => 'air-terjun-pancuran-rayo',
                'description'  => 'Air Terjun Pancuran Rayo terletak tidak jauh dari pusat Kota Sungai Penuh. Air terjun ini memiliki debit air yang besar sepanjang tahun dan suasana sekitarnya sangat menyegarkan.',
                'location'     => 'Sungai Penuh, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-2.0500,101.3900',
                'ticket_price' => 10000,
                'open_hours'   => '07:00 - 16:00',
                'status'       => 'active',
                'categories'   => ['air-terjun'],
                'primary_image'=> 'destinations/pancuranrayo.jpg',
            ],
            [
                'name'         => 'Bukit Kayangan',
                'slug'         => 'bukit-kayangan',
                'description'  => 'Bukit Kayangan adalah destinasi wisata alam berupa perbukitan dengan pemandangan indah di Kerinci. Dari puncaknya, pengunjung dapat menikmati hamparan alam hijau dan panorama sekitar yang memukau.',
                'location'     => 'Kerinci, Jambi',
                'map_url'      => 'https://maps.google.com/?q=-2.1000,101.4000',
                'ticket_price' => 10000,
                'open_hours'   => '06:00 - 18:00',
                'status'       => 'active',
                'categories'   => ['alam-pegunungan'],
                'primary_image'=> 'destinations/bukitkayangan1.jpg',
            ],
        ];

        foreach ($destinationData as $data) {
            $catSlugs    = $data['categories'];
            $primaryImage = $data['primary_image'] ?? null;
            unset($data['categories'], $data['primary_image']);

            $destination = Destination::create($data);

            // Attach categories
            $catIds = $categories->whereIn('slug', $catSlugs)->pluck('id');
            $destination->categories()->attach($catIds);

            // Create images (use real image if provided, otherwise placeholder)
            $primaryPath = $primaryImage ?? 'destinations/placeholder-' . $destination->slug . '-1.jpg';
            DestinationImage::create([
                'destination_id' => $destination->id,
                'image_path'     => $primaryPath,
                'is_primary'     => true,
            ]);
            for ($i = 2; $i <= 3; $i++) {
                DestinationImage::create([
                    'destination_id' => $destination->id,
                    'image_path'     => 'destinations/placeholder-' . $destination->slug . '-' . $i . '.jpg',
                    'is_primary'     => false,
                ]);
            }

            // Create 5 approved reviews per destination
            Review::factory(5)
                ->approved()
                ->create([
                    'destination_id' => $destination->id,
                    'user_id'        => $users->random()->id,
                ]);
        }
    }
}

