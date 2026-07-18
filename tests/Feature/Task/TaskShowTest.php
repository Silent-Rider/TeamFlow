<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_task_details(): void
    {
        $user = $this->createUserWithProject();
        $task = $this->createTask($user);

        TaskComment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'content' => 'Test task comment'
        ]);

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertJsonStructure([
            'task' => ['id', 'name'],
            'html'
        ]);
        $response->assertSee('Test task comment');
    }

    public function test_task_details_include_assignee_info(): void
    {
        $owner = $this->createUserWithProject();
        $assignee = User::factory()->create(['company_id' => $owner->company_id]);
        /** @var Project $project */
        $project = $owner->projects()->first();
        $project->users()->attach($assignee->id, ['role' => ProjectRole::MEMBER]);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $assignee->id,
            'creator_id' => $owner->id
        ]);

        $response = $this->actingAs($owner)
            ->withHeader('Accept', 'application/json')
            ->get(route('tasks.show', $task));

        $response->assertOk();
        $response->assertSee($assignee->name);
    }

    private function createUserWithProject(): User
    {
        $company = Company::factory()->create();
        /** @var User $user */
        $user = User::factory()->create(['company_id' => $company->id]);
        $project = Project::factory()->create(['creator_id' => $user->id, 'company_id' => $user->company_id]);
        $project->users()->attach($user->id, ['role' => ProjectRole::OWNER]);
        return $user;
    }

    private function createTask(User $user): Task
    {
        $project = $user->projects()->first();
        /** @var Task $task */
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assignee_id' => $user->id,
            'creator_id' => $user->id
        ]);
        return $task;
    }
}
