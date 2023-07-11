<?php

namespace Database\Seeders;

use App\Models\Travel;
use Illuminate\Database\Seeder;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Travel::factory(50)
            ->hasTours(rand(25, 50))
            ->create();
    }
}
