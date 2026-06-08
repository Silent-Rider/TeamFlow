<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Database\Seeder;

class TaskCommentSeeder extends Seeder
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function run(): void
    {
        $tasks = Task::with('creator')->get();
        foreach ($tasks as $task) {
            TaskComment::factory()->create([
                'task_id' => $task->id,
                'user_id' => $task->creator->id,
                'created_at' => fake()->dateTimeBetween($task->created_at),
            ]);
        }
    }
}
