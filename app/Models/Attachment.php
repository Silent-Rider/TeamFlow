<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $task_comment_id
 * @property string name
 * @property string|null extension
 * @property string $filepath
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static create(array $array)
 */

#[Fillable(['task_comment_id', 'name', 'extension', 'filepath'])]
class Attachment extends Model
{
    use HasFactory;
    public function taskComment(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class);
    }
}
