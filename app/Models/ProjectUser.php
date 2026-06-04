<?php

namespace App\Models;

use App\Enums\ProjectRole;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $user_id
 * @property int $project_id
 * @property string $role
 * @property \Illuminate\Support\Carbon $created_at
 */
#[Fillable(['user_id', 'project_id', 'role'])]
class ProjectUser extends Pivot
{
    protected function casts(): array
    {
        return ['role' => ProjectRole::class];
    }
}
