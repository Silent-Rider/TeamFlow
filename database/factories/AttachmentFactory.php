<?php

namespace Database\Factories;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attachment>
 */
class AttachmentFactory extends Factory
{
    public function definition(): array
    {
        $extension = fake()->randomElement(['jpg', 'png', 'pdf', 'docx']);

        return [
            'name' => fake()->word() . '.' . $extension,
            'extension' => $extension,
            'filepath' => 'attachments/' . fake()->uuid() . '.' . $extension,
        ];
    }
}
