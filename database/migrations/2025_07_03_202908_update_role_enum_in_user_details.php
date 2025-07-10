<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateRoleEnumInUserDetails extends Migration
{
    /**
     * Ejecutar las migraciones.
     */
    public function up()
    {
        // Primero, actualizar los valores existentes de 'shipper' a 'forwarder'
        DB::statement("UPDATE user_details SET `role` = 'forwarder' WHERE `role` = 'shipper'");

        // Luego, modificar la columna para usar los nuevos valores ENUM
        DB::statement("ALTER TABLE user_details MODIFY COLUMN `role` ENUM('carrier', 'forwarder', 'admin') NOT NULL DEFAULT 'forwarder'");
    }

    /**
     * Revertir las migraciones.
     */
    public function down()
    {
        // Primero, actualizar los valores existentes de 'forwarder' a 'shipper'
        DB::statement("UPDATE user_details SET `role` = 'shipper' WHERE `role` = 'forwarder'");

        // Luego, modificar la columna para volver a los valores originales
        DB::statement("ALTER TABLE user_details MODIFY COLUMN `role` ENUM('carrier', 'shipper', 'admin') NOT NULL DEFAULT 'shipper'");
    }
}
