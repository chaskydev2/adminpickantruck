<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar ofertas_carga
        Schema::table('ofertas_carga', function (Blueprint $table) {
            $foreignKeys = $this->getForeignKeys('ofertas_carga');
            
            if (in_array('ofertas_carga_user_id_foreign', $foreignKeys)) {
                $table->dropForeign('ofertas_carga_user_id_foreign');
            }
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Actualizar ofertas_ruta
        Schema::table('ofertas_ruta', function (Blueprint $table) {
            $foreignKeys = $this->getForeignKeys('ofertas_ruta');
            
            if (in_array('ofertas_ruta_user_id_foreign', $foreignKeys)) {
                $table->dropForeign('ofertas_ruta_user_id_foreign');
            }
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Bids ya tiene onDelete('cascade') desde su creación
        // Mantenerlo por si acaso hay una versión antigua sin cascade
        if (Schema::hasTable('bids')) {
            Schema::table('bids', function (Blueprint $table) {
                $foreignKeys = $this->getForeignKeys('bids');
                
                if (in_array('bids_user_id_foreign', $foreignKeys)) {
                    $table->dropForeign('bids_user_id_foreign');
                }
                
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            });
        }

        // Actualizar messages (si existe)
        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table) {
                if (Schema::hasColumn('messages', 'user_id')) {
                    $foreignKeys = $this->getForeignKeys('messages');
                    
                    if (in_array('messages_user_id_foreign', $foreignKeys)) {
                        $table->dropForeign('messages_user_id_foreign');
                    }
                    
                    $table->foreign('user_id')
                          ->references('id')
                          ->on('users')
                          ->onDelete('cascade');
                }
            });
        }

        // Actualizar notifications (si existe)
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                if (Schema::hasColumn('notifications', 'notifiable_id')) {
                    // Las notificaciones no suelen tener restricciones de clave foránea,
                    // pero añadimos un índice para mejorar el rendimiento
                    $indexes = $this->getIndexes('notifications');
                    
                    if (!in_array('notifications_notifiable_id_notifiable_type_index', $indexes)) {
                        $table->index(['notifiable_id', 'notifiable_type']);
                    }
                }
            });
        }
    }

    /**
     * Obtiene las claves foráneas de una tabla
     */
    private function getForeignKeys($tableName)
    {
        $database = config('database.connections.mysql.database');
        $foreignKeys = [];
        
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = ? 
            AND TABLE_NAME = ? 
            AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ", [$database, $tableName]);
        
        foreach ($constraints as $constraint) {
            $foreignKeys[] = $constraint->CONSTRAINT_NAME;
        }
        
        return $foreignKeys;
    }

    /**
     * Obtiene los índices de una tabla
     */
    private function getIndexes($tableName)
    {
        $indexes = [];
        
        $indexList = DB::select("SHOW INDEXES FROM {$tableName}");
        
        foreach ($indexList as $index) {
            $indexes[] = $index->Key_name;
        }
        
        return array_unique($indexes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar las claves foráneas a su estado original
        Schema::table('ofertas_carga', function (Blueprint $table) {
            $foreignKeys = $this->getForeignKeys('ofertas_carga');
            
            if (in_array('ofertas_carga_user_id_foreign', $foreignKeys)) {
                $table->dropForeign('ofertas_carga_user_id_foreign');
            }
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });

        Schema::table('ofertas_ruta', function (Blueprint $table) {
            $foreignKeys = $this->getForeignKeys('ofertas_ruta');
            
            if (in_array('ofertas_ruta_user_id_foreign', $foreignKeys)) {
                $table->dropForeign('ofertas_ruta_user_id_foreign');
            }
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });

        if (Schema::hasTable('bids')) {
            Schema::table('bids', function (Blueprint $table) {
                $foreignKeys = $this->getForeignKeys('bids');
                
                if (in_array('bids_user_id_foreign', $foreignKeys)) {
                    $table->dropForeign('bids_user_id_foreign');
                }
                
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users');
            });
        }

        // No es necesario restaurar las claves foráneas para messages y notifications
    }
};
