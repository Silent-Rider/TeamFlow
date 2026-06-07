<?php

namespace Database\Seeders;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereNotIn('id', [3, 4])->get();
        if ($users->isEmpty()) {
            return;
        }
        $projects = Project::factory()
            ->count(7)
            ->state(function () use ($users) {
                $randomUser = $users->random();
                return [
                    'creator_id' => $randomUser->id,
                    'created_at' => fake()->dateTimeBetween($randomUser->created_at)
                ];
            })
            ->create();

        $projects->each(function (Project $project) use ($users) {
            $this->createProjectUserPivot($users, $project);
        });
    }

    private function createProjectUserPivot($users, $project): void
    {
        $owner = $users->firstWhere('id', $project->creator_id);
        $pivotData = [
            $owner->id => [
                'role' => ProjectRole::OWNER->value,
                'created_at' => $project->created_at
            ]
        ];
        $otherUsers = $users->where('id', '!=', $owner->id);
        $membersCount = min(fake()->numberBetween(1, 4), $otherUsers->count());
        if ($membersCount > 0) {
            $selectedMembers = $otherUsers->random($membersCount);
            foreach ($selectedMembers as $member) {
                $pivotData[$member->id] = [
                    'role' => ProjectRole::MEMBER->value,
                    'created_at' => $project->created_at
                ];
            }
        }
        foreach ($pivotData as $userId => $attributes) {
            DB::table('project_user')->insert([
                'user_id'    => $userId,
                'project_id' => $project->id,
                'role'       => $attributes['role'],
                'created_at' => $attributes['created_at'],
            ]);
        }
    }
}
