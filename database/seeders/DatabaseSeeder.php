<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario administrador inicial (cambiar la contraseña al entrar)
        User::updateOrCreate(
            ['email' => 'admin@granja.com'],
            [
                'name'     => 'Administrador',
                'role'     => 'admin',
                'password' => 'admin123',
            ]
        );
    }
}