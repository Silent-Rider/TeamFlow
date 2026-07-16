<?php

namespace Database\Seeders;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return;
        }

        $firstUser = $users->first();
        $firstProject = Project::factory()
            ->create([
                'creator_id' => $firstUser->id,
                'created_at' => fake()->dateTimeBetween($firstUser->created_at),
                'company_id' => $firstUser->company_id,
            ]);

        $projects = Project::factory()
            ->count(6)
            ->state(function () use ($users) {
                $randomUser = $users->random();
                return [
                    'creator_id' => $randomUser->id,
                    'created_at' => fake()->dateTimeBetween($randomUser->created_at),
                    'company_id' => $randomUser->company_id,
                ];
            })
            ->create();
        $projects->prepend($firstProject);

        $projects->each(function (Project $project) use ($users) {
            $companyUsers = $users->where('company_id', $project->company_id);
            $this->createProjectUserPivot($companyUsers, $project);
        });
    }

    private function createProjectUserPivot(Collection $users, Project $project): void
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
            $project->users()->attach($userId, $attributes);
        }
    }
}
