<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFechasMantenimiento extends Migration
{
    public function up()
		{
			$this->forge->addColumn('mantenimiento', [
					'fecha_cierre_calidad' => [
							'type'       => 'DATETIME',
							'null'       => true,
							'after'      => 'firma_limpieza'
					],
					'fecha_cierre_produccion' => [
							'type'       => 'DATETIME',
							'null'       => true,
							'after'      => 'fecha_cierre_calidad'
					],
					'fecha_inicio_limpieza' => [
							'type'       => 'DATETIME',
							'null'       => true,
							'after'      => 'fecha_cierre_produccion'
					],
					'fecha_cierre_limpieza' => [
							'type'       => 'DATETIME',
							'null'       => true,
							'after'      => 'fecha_inicio_limpieza'
					],
					'fecha_inicio_liberacion' => [
							'type'       => 'DATETIME',
							'null'       => true,
							'after'      => 'fecha_cierre_limpieza'
					],
					'fecha_cierre_liberacion' => [
							'type'       => 'DATETIME',
							'null'       => true,
					]
					
			]);
    }

    public function down()
    {
			$this->forge->dropColumn('mantenimiento', ['fecha_cierre_calidad', 'fecha_cierre_produccion', 'fecha_inicio_limpieza', 'fecha_cierre_limpieza', 'fecha_inicio_liberacion', 'fecha_cierre_liberacion']);
    }
}
