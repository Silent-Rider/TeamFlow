<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null logo
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static create(array $data)
 * @method static where(string $string, string $code)
 */
#[Fillable(['name', 'code', 'logo', 'description'])]
class Company extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->code)) {
                do {
                    $code = strtoupper(Str::random(7));
                } while (static::where('code', $code)->exists());

                $company->code = $code;
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
