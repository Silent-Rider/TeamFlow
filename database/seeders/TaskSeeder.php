<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /** @noinspection PhpPossiblePolymorphicInvocationInspection */
    public function run(): void
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $users = $project->users()->get();
            foreach ($users as $user) {
                Task::factory()->count(3)->create([
                    'creator_id' => $project->creator_id,
                    'project_id' => $project->id,
                    'assignee_id' => $user->id,
                    'created_at' => fake()->dateTimeBetween($project->created_at),
                ]);
            }
        }
    }
}
