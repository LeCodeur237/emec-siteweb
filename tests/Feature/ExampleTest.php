<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_configuration_loads(): void
    {
        $this->assertSame('EMEC Backend', config('app.name'));
        $this->assertSame('mysql', config('database.default'));
    }
}
