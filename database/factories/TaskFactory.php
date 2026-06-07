<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected static ?int $index = null;
    private static array $taskNames = [
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
        'Интегрировать Redis для кэширования',
        'Настроить Xdebug в PHP-FPM',
        'Реализовать middleware авторизации',
        'Создать FormRequest для задач',
        'Написать Feature-тесты фильтрации',
        'Настроить Nginx reverse proxy',
        'Добавить пагинацию в список задач',
        'Реализовать soft deletes для задач',
        'Настроить GitHub Actions для CI',
        'Добавить Swagger/OpenAPI документацию',
        'Оптимизировать Eloquent N+1 запросы',
        'Настроить логирование ошибок в Laravel',
        'Реализовать очередь задач (Queues)',
        'Добавить подтверждение email верификации',
        'Настроить rate limiting для API',
        'Создать Policy для управления правами',
        'Интегрировать Sentry для мониторинга',
        'Настроить автоматические бэкапы PostgreSQL',
        'Добавить dark mode в интерфейсе',
        'Реализовать drag-and-drop на канбан-доске',
    ];
    public function definition(): array
    {
        static::$index ??= fake()->numberBetween(0, count(static::$taskNames) - 1);
        return [
            'name' => static::$taskNames[static::$index++ % count(static::$taskNames)],
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'is_done' => fake()->boolean(30),
            'due_date' => fake()->dateTimeBetween('-1 week', '+10 weeks'),
            'created_at' => fake()->dateTimeBetween('-5 weeks'),
            'description' => fake()->sentence(15),
        ];
    }
}
