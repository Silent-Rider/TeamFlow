<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Enums\TaskPriority;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_task_details(): void
    {
        $user = $this->createUserWithCompany();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'name' => 'Обновленное название',
                'priority' => TaskPriority::HIGH->value,
                'description' => 'Новое описание',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Обновленное название',
            'priority' => TaskPriority::HIGH->value,
            'description' => 'Новое описание',
        ]);
    }

    public function test_user_cannot_update_task_if_they_do_not_have_access(): void
    {
        $owner = $this->createUserWithCompany();
        $stranger = $this->createUserWithCompany();
        $task = $this->createTask($owner);

        $response = $this->actingAs($stranger)
            ->put(route('tasks.update', $task), [
                'name' => 'Попытка взлома',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['name' => 'Попытка взлома']);
    }

    public function test_update_fails_with_invalid_priority(): void
    {
        $user = $this->createUserWithCompany();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'priority' => 'invalid-value',
            ]);

        $response->assertSessionHasErrors(['priority']);
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
