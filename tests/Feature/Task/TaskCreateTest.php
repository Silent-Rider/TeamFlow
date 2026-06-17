<?php

namespace Task;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_task_in_project_where_he_is_owner()
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(["creator_id" => $owner->id]);
        $project->users()->attach($owner->id, ['role' => ProjectRole::OWNER]);

        $response = $this->actingAs($owner)
            ->post(route('tasks.create'),
                $this->getTaskPayload($owner->id, $project->id, ['name' => 'Новая задача']));

        $response->assertRedirect();

        $this->assertDatabaseHas('tasks', [
            'name' => 'Новая задача',
            'project_id' => $project->id,
            'creator_id' => $owner->id
        ]);
    }

    public function test_user_cannot_create_task_if_not_in_project()
    {
        $stranger = User::factory()->create();
        $project = Project::factory()->create();

        $response = $this->actingAs($stranger)->post(route('tasks.create'),
            $this->getTaskPayload($stranger->id, $project->id, ['name' => 'Шпионская задача']));

        $response->assertForbidden();
        $this->assertDatabaseMissing('tasks', ['name' => 'Шпионская задача']);
    }

    public function test_task_creation_fails_with_invalid_priority()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['creator_id' => $user->id]);
        $project->users()->attach($user->id, ['role' => ProjectRole::OWNER]);

        $response = $this->actingAs($user)->post(route('tasks.create'),
            $this->getTaskPayload($user->id, $project->id, ['priority' => 'Неверный приоритет']));

        $response->assertSessionHasErrors(['priority']);
    }

    private function getTaskPayload(int $userId, int $projectId, array $overrides = []): array
    {
        $taskArray = Task::factory()->raw([
            'creator_id' => $userId,
            'assignee_id' => $userId,
            'project_id' => $projectId,
            'due_date' => null,
            'created_at' => null
        ]);
        return array_merge($taskArray, $overrides);
    }
}
