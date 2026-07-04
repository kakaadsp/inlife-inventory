<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_returns_a_successful_response(): void
    {
        // '/' redirects to /login in this app
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }
}
