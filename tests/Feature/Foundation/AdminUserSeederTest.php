<?php

namespace Tests\Feature\Foundation;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_default_super_admin_user(): void
    {
        putenv('EMEC_ADMIN_PASSWORD=secure-test-password');
        $_ENV['EMEC_ADMIN_PASSWORD'] = 'secure-test-password';

        $this->seed(DatabaseSeeder::class);

        $user = User::where('email', 'egliseemec.cm@gmail.com')->firstOrFail();

        $this->assertTrue($user->isActive());
        $this->assertTrue($user->hasRole('super_admin'));
    }
}
