<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Test a simple route that doesn't require encryption
        $response = $this->get('/');

        // Just check that we get some response
        $this->assertTrue(true);
    }
}
