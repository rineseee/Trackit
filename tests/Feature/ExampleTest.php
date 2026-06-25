<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_redirects_home_to_projects(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/projects');
    }

    public function test_the_projects_index_returns_a_successful_response(): void
    {
        $response = $this->get('/projects');

        $response->assertOk();
    }
}
