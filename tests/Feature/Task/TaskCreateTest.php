<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Enums\TaskPriority;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_in_project_where_he_is_owner(): void
    {
        $owner = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        $payload = $this->getTaskPayload($owner->id, $project->id, ['name' => 'Новая задача']);

        $response = $this->actingAs($owner)
            ->post(route('tasks.create'), $payload);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'name' => 'Новая задача',
            'project_id' => $project->id,
            'creator_id' => $owner->id
        ]);
    }

    public function test_user_cannot_create_task_if_not_in_project(): void
    {
        $stranger = $this->createUserWithCompany();
        $owner = $this->createUserWithCompany();
        $project = $this->createProject($owner);

        $payload = $this->getTaskPayload($stranger->id, $project->id, ['name' => 'Шпионская задача', 'assignee_id' => $stranger->id]);

        $response = $this->actingAs($stranger)->post(route('tasks.create'), $payload);

        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['name' => 'Шпионская задача']);
    }

    public function test_task_creation_fails_with_invalid_priority(): void
    {
        $user = $this->createUserWithCompany();
        $project = $this->createProject($user);

        $payload = $this->getTaskPayload($user->id, $project->id, ['priority' => 'Неверный приоритет']);

        $response = $this->actingAs($user)->post(route('tasks.create'), $payload);

        $response->assertSessionHasErrors(['priority']);
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

    private function getTaskPayload(int $userId, int $projectId, array $overrides = []): array
    {
        $taskArray = [
            'creator_id' => $userId,
            'assignee_id' => $userId,
            'project_id' => $projectId,
            'name' => 'Test Task',
            'priority' => TaskPriority::MEDIUM->value,
            'due_date' => null,
        ];
        return array_merge($taskArray, $overrides);
    }
}
