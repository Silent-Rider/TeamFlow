<?php

namespace Task;

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_task()
    {
        $user = User::factory()->create();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_delete_task_if_they_do_not_have_access()
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $task = $this->createTask($owner);

        $response = $this->actingAs($stranger)
            ->delete(route('tasks.destroy', $task));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    private function createTask(User $user): Model
    {
        $project = Project::factory()->create(['creator_id' => $user->id]);
        $project->users()->attach($user->id, ['role' => ProjectRole::OWNER]);

        return Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $user->id,
            'creator_id' => $user->id
        ]);
    }
}
