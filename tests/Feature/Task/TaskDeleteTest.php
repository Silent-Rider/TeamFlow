<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_task(): void
    {
        $user = $this->createUserWithCompany();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_task_if_they_do_not_have_access(): void
    {
        $owner = $this->createUserWithCompany();
        $stranger = $this->createUserWithCompany();
        $task = $this->createTask($owner);

        $response = $this->actingAs($stranger)
            ->delete(route('tasks.destroy', $task));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
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
