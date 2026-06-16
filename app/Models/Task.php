<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $creator_id
 * @property int $assignee_id
 * @property string $name
 * @property TaskPriority $priority
 * @property bool $is_done
 * @property Carbon|null $due_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $project_id
 * @property string|null $description
 * @method static create(array $data)
 */
#[Fillable(['creator_id', 'project_id', 'assignee_id', 'name', 'description', 'priority', 'is_done', 'due_date'])]
class Task extends Model
{
    use HasFactory;

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taskComments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    protected function casts(): array
    {
        return [
            'is_done' => 'boolean',
            'due_date' => 'date',
            'priority' => TaskPriority::class
        ];
    }
}
