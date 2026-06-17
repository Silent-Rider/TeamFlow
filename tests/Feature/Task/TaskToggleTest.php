<?php

namespace Task;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
