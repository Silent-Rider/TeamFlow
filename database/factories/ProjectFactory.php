<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected static ?int $index = null;

    private static array $projectNames = [
        'TeamFlow Platform',
        'Mobile App Redesign',
        'Customer Portal v2',
        'Internal Analytics Dashboard',
        'AI-Powered Search Module',
        'Real-time Notifications Service',
        'Documentation Hub',
    ];
    public function definition(): array
    {
        static::$index ??= fake()->numberBetween(0, count(static::$projectNames) - 1);
        return [
            'name' => static::$projectNames[static::$index++ % count(static::$projectNames)],
            'created_at' => fake()->dateTimeBetween('-5 weeks'),
            'description' => fake()->sentence(15),
        ];
    }
}
