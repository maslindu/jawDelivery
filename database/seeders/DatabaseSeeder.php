<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::firstOrCreate(
                ['name' => $roleEnum->value],
                [
                    'display_name' => $roleEnum->displayName(),
                    'description' => $roleEnum->description(),
                ]
            );
        }

        $username = env('ADMIN_USERNAME', 'Admin');
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'password');

        $user = \App\Models\User::factory()->create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $role = Role::where('name', RoleEnum::ADMIN->value)->first();
        $user->roles()->attach($role->id);
    }
}

