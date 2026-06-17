<?php

namespace Project;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
