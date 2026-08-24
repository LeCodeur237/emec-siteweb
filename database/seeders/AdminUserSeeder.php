<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('EMEC_ADMIN_PASSWORD');

        if (! is_string($password) || strlen($password) < 8) {
            throw new RuntimeException('EMEC_ADMIN_PASSWORD must be defined and contain at least 8 characters.');
        }

        $user = User::updateOrCreate(
            ['email' => env('EMEC_ADMIN_EMAIL', 'egliseemec.cm@gmail.com')],
            [
                'name' => env('EMEC_ADMIN_NAME', 'Administrateur EMEC'),
                'password' => $password,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $role = Role::where('slug', 'super_admin')->firstOrFail();

        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
