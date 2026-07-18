<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_their_assigned_tasks(): void
    {
        $user = $this->createUserWithCompany();

        Task::factory()->count(3)->create([
            'assignee_id' => $user->id,
            'is_done' => false,
            'project_id' => $this->createProject($user)->id,
            'creator_id' => $user->id,
        ]);

        $otherUser = $this->createUserWithCompany();
        Task::factory()->create([
            'assignee_id' => $otherUser->id,
            'project_id' => $this->createProject($otherUser)->id,
            'creator_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->get(route('tasks'));

        $response->assertOk();
        $response->assertViewIs('task.tasks');
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 3;
        });
    }

    public function test_user_can_see_project_tasks_if_has_access(): void
    {
        $owner = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        Task::factory()->count(2)->create([
            'project_id' => $project->id,
            'assignee_id' => $owner->id,
            'creator_id' => $owner->id,
        ]);

        $response = $this->actingAs($owner)
            ->get(route('tasks', ['project_id' => $project->id]));

        $response->assertOk();
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 2;
        });
    }

    public function test_user_cannot_see_tasks_of_project_without_access(): void
    {
        $owner = $this->createUserWithCompany();
        $stranger = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $owner->id,
            'creator_id' => $owner->id,
        ]);

        $response = $this->actingAs($stranger)
            ->get(route('tasks', ['project_id' => $project->id]));

        $response->assertForbidden();
    }

    public function test_tasks_are_filtered_by_status_correctly(): void
    {
        $user = $this->createUserWithCompany();
        $project = $this->createProject($user);

        Task::factory()->create([
            'assignee_id' => $user->id,
            'is_done' => true,
            'name' => 'Done Task',
            'project_id' => $project->id,
            'creator_id' => $user->id,
        ]);

        Task::factory()->create([
            'assignee_id' => $user->id,
            'is_done' => false,
            'name' => 'Active Task',
            'project_id' => $project->id,
            'creator_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('tasks', ['filter' => 'active']));
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 1 && $tasks->first()->name === 'Active Task';
        });

        $response = $this->actingAs($user)->get(route('tasks', ['filter' => 'done']));
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 1 && $tasks->first()->name === 'Done Task';
        });
    }

    public function test_index_tasks_fails_with_invalid_per_page(): void
    {
        $user = $this->createUserWithCompany();

        $response = $this->actingAs($user)->get(route('tasks', [
            'per_page' => 101,
        ]));

        $response->assertSessionHasErrors(['per_page']);
    }

    private function createUserWithCompany(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        return $user;
    }

    private function createProject(User $owner): Project
    {
        /** @var Project $project */
        $project = Project::factory()->create(['creator_id' => $owner->id, 'company_id' => $owner->company_id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);
        return $project;
    }
}
