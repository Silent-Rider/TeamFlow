<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_toggle_task_status(): void
    {
        $user = $this->createUserWithCompany();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->patch(route('tasks.toggle', $task));

        $this->assertTrue($task->fresh()->is_done);
    }

    public function test_user_cannot_toggle_task_if_they_do_not_have_access(): void
    {
        $owner = $this->createUserWithCompany();
        $stranger = $this->createUserWithCompany();
        $task = $this->createTask($owner);

        $response = $this->actingAs($stranger)
            ->patch(route('tasks.toggle', $task));

        $response->assertForbidden();
        $this->assertFalse($task->fresh()->is_done);
    }

    private function createUserWithCompany(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        return $user;
    }

    private function createTask(User $user): Task
    {
        $project = $this->createProject($user);
        /** @var Task $task */
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'is_done' => false,
            'assignee_id' => $user->id,
            'creator_id' => $user->id
        ]);
        return $task;
    }

    private function createProject(User $owner): Project
    {
        /** @var Project $project */
        $project = Project::factory()->create(['creator_id' => $owner->id, 'company_id' => $owner->company_id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);
        return $project;
    }
}
