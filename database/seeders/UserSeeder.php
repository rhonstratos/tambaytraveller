<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $user = User::create([
                'name' => 'Demo User',
                'email' => 'demo@user.test',
                'password' => 'password',
            ]);
            $user->roles()->attach(Roles::where('name', 'demo')->first()->id);
        });
    }
}
