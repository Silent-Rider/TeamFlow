<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property int|null $creator_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $description
 * @property int $company_id
 * @method static create(array $data)
 * @method static hydrate(mixed $items)
 * @method static find(int $projectId)
 * @method static where(string $string, string $string1)
 */
#[Fillable(['creator_id', 'name', 'description', 'company_id'])]
class Project extends Model
{
    use HasFactory;
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->using(ProjectUser::class)
            ->withPivot('role', 'created_at');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
