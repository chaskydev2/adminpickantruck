<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@pickutruck.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'estado' => 'Activo',
            'verified' => true,
        ]);

        // Crear detalles del administrador
        UserDetail::create([
            'user_id' => $admin->id,
            'phone' => '1234567890',
            'role' => 'admin',
        ]);

        // Verificar si ya existen tipos de camión para no duplicar
        if (DB::table('truck_types')->count() === 0) {
            DB::table('truck_types')->insert([
                ['nombre' => 'Camión Cerrado', 'descripcion' => 'Caja cerrada estándar', 'capacidad_kg' => 10000, 'largo' => 13.6, 'ancho' => 2.4, 'alto' => 2.7],
                ['nombre' => 'Torton', 'descripcion' => 'Caja cerrada grande', 'capacidad_kg' => 15000, 'largo' => 15.0, 'ancho' => 2.5, 'alto' => 2.8],
                ['nombre' => 'Rabón', 'descripcion' => 'Caja cerrada corta', 'capacidad_kg' => 7000, 'largo' => 8.5, 'ancho' => 2.4, 'alto' => 2.7],
            ]);
        }

        // Verificar si ya existen tipos de carga para no duplicar
        if (DB::table('cargo_types')->count() === 0) {
            DB::table('cargo_types')->insert([
                [
                    'name' => 'Mercancía General', 
                    'description' => 'Carga seca y no perecedera',
                    'requires_refrigeration' => false,
                    'hazardous' => false,
                    'active' => true,
                    'icon' => 'box',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Refrigerados', 
                    'description' => 'Productos que requieren cadena de frío',
                    'requires_refrigeration' => true,
                    'hazardous' => false,
                    'active' => true,
                    'icon' => 'snowflake',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Materiales Peligrosos', 
                    'description' => 'Sustancias peligrosas',
                    'requires_refrigeration' => false,
                    'hazardous' => true,
                    'active' => true,
                    'icon' => 'exclamation-triangle',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Maquinaria Pesada', 
                    'description' => 'Equipos y maquinaria industrial',
                    'requires_refrigeration' => false,
                    'hazardous' => false,
                    'active' => true,
                    'icon' => 'cog',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
