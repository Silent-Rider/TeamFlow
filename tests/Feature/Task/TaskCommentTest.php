<?php

namespace Tests\Feature\Task;

use App\Enums\ProjectRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskCommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_can_add_comment_to_task(): void
    {
        $user = $this->createUserWithProject();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post(route('tasks.comments', $task), [
                'content' => 'Это тестовый комментарий',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'content' => 'Это тестовый комментарий'
        ]);
    }

    public function test_user_can_add_comment_with_attachment(): void
    {
        $user = $this->createUserWithProject();
        $task = $this->createTask($user);
        $file = UploadedFile::fake()->image('photo.png');

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post(route('tasks.comments', $task), [
            'content' => 'Комментарий с файлом',
            'attachment' => $file,
        ]);

        $response->assertJson(['success' => true]);

        $comment = TaskComment::where('content', 'Комментарий с файлом')->first();
        $this->assertNotNull($comment);
        $this->assertEquals(1, $comment->attachments->count());

        Storage::disk('public')->assertExists('attachments/' . $task->id . '/' . $file->hashName());
    }

    public function test_comment_fails_without_content_and_attachment(): void
    {
        $user = $this->createUserWithProject();
        $task = $this->createTask($user);

        $response = $this->actingAs($user)->post(route('tasks.comments', $task));

        $response->assertSessionHasErrors(['content']);
    }

    public function test_comment_fails_with_invalid_file_type(): void
    {
        $user = $this->createUserWithProject();
        $task = $this->createTask($user);
        $file = UploadedFile::fake()->create('script.exe', 1000);

        $response = $this->actingAs($user)->post(route('tasks.comments', $task), [
            'content' => 'Тест',
            'attachment' => $file,
        ]);

        $response->assertSessionHasErrors(['attachment']);
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
