<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Electrónica',    'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Computación',    'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Electrodomésticos', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}