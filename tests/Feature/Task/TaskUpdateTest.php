<?php

namespace Task;

use App\Enums\ProjectRole;
use App\Enums\TaskPriority;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_task_details()
    {
        $user = User::factory()->create();
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

    public function test_user_cannot_update_task_if_they_do_not_have_access()
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $task = $this->createTask($owner);

        $response = $this->actingAs($stranger)
            ->put(route('tasks.update', $task), [
                'name' => 'Попытка взлома',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('tasks', ['name' => 'Попытка взлома']);
    }

    public function test_update_fails_with_invalid_priority()
    {
        $user = User::factory()->create();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'priority' => 'invalid-value',
            ]);

        $response->assertSessionHasErrors(['priority']);
    }

    private function createTask(User $user): Model
    {
        $project = Project::factory()->create(['creator_id' => $user->id]);
        $project->users()->attach($user->id, ['role' => ProjectRole::OWNER]);

        return Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $user->id,
            'creator_id' => $user->id,
            'is_done' => false,
            'priority' => TaskPriority::LOW->value
        ]);
    }
}
