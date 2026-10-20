<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $testPassword = 'test_password';

        $users = User::factory()->count(5)->create([
            'password' => bcrypt($testPassword), 
        ]);

        $users->each(function ($user) use ($testPassword) {
            $token = $user->createToken("{$user->name} token")->plainTextToken;
            $this->command->line("Email: <info>{$user->email}</info> | Pass: <info>{$testPassword}</info> | Token: <comment>{$token}</comment>");
        });
    }
}
