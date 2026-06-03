<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'    => fake()->randomElement([
                'Настроить окружение Docker',
                'Создать миграции БД',
                'Разработать API авторизации',
                'Написать unit-тесты',
                'Настроить CI/CD pipeline',
                'Добавить валидацию форм',
                'Написать документацию',
                'Сделать code review',
                'Исправить баги после тестирования',
                'Оптимизировать запросы к БД',
            ]),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'is_done'  => fake()->boolean(30),
            'due_date' => fake()->dateTimeBetween('-1 week', '+3 weeks'),
        ];
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_done' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_done' => false,
        ]);
    }
}
