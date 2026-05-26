<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::find(2);
        Task::factory()->count(3)->pending()->create(['user_id' => $user->id]);
        Task::factory()->count(2)->done()->create(['user_id' => $user->id]);
    }
}
