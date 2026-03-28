<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedoresSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('proveedores')->insert([
            ['nombre' => 'Distribuidora Tech S.A.',  'telefono' => '2222-1111', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'ImportSA de C.V.',         'telefono' => '2233-4455', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Global Supply SV',         'telefono' => '7890-1234', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}