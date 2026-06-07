<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Silent Rider',
            'email' => 'silent.30.rider.10@gmail.com',
        ]);

        User::factory()->count(10)->create();
        User::factory()->unverified()->create();
    }
}
