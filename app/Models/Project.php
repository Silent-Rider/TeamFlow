<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 */
#[Fillable(['created_by', 'name', 'description'])]
class Project extends Model
{
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
