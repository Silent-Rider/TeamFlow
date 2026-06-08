<?php

namespace Database\Factories;

use App\Models\TaskComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskComment>
 */
class TaskCommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'content' => $this->faker->realText(),
        ];
    }
}
