<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Create authenticated user for testing
     */
    protected function createAuthenticatedUser(array $attributes = [])
    {
        $user = \App\Models\User::factory()->create(array_merge([
            'status' => 'aktif',
        ], $attributes));

        $this->actingAs($user);

        return $user;
    }

    /**
     * Create admin user
     */
    protected function createAdmin()
    {
        return $this->createAuthenticatedUser([
            'role' => 'admin',
        ]);
    }
}
