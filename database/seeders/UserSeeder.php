<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(10)->create();

        User::factory()->create([
            'email' => 'rogier.vandenberg+manager@paqt.com',
            'is_manager' => true,
        ]);

        User::factory()->create([
            'email' => 'rogier.vandenberg+employee@paqt.com',
            'is_manager' => false,
        ]);
    }
}
