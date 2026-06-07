<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function run(): void
    {
        $assignee = User::find(2);
        Task::factory()->count(7)->pending()->create(['assignee_id' => $assignee->id]);
        Task::factory()->count(3)->done()->create(['assignee_id' => $assignee->id]);


    }
}
