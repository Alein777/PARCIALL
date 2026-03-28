<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('marcas')->insert([
            ['nombre' => 'Samsung', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Apple',   'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Sony',    'estado' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}