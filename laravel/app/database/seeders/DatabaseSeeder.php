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
        // Seed estados
        $this->call(EstadosSeeder::class);

        // Seed cidades
        $this->call(CidadesSeeder::class);

        // Seed teste cores perguntas e opcoes
        $this->call(TesteCoresPerguntasSeeder::class);

        // Create admin user
        User::create([
            'name' => 'Admin',
            'nome' => 'Administrador',
            'email' => 'admin@nutridiario.com.br',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'nome' => 'Usuário Teste',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);
    }
}
