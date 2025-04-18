<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AirportsTableSeeder extends Seeder
{
    public function run()
    {
        // Desactivar comprobación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('airports')->truncate(); // Elimina datos existentes
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $path = database_path('seeders/sql/airports.sql');
        $sql = File::get($path);
        
        DB::unprepared($sql);
        
        $this->command->info('¡Datos de aeropuertos importados exitosamente!');
    }
}