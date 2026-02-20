<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;


class AddLimpiezaToMantenimiento extends Migration
{
	public function up()
	{
			$this->forge->addColumn('mantenimiento', [
					'requiere_limpieza' => [
							'type'       => 'VARCHAR',
							'constraint' => 255,
							'null'       => true,
							'after'      => 'cambio_pieza'
					],
			]);

	}

	public function down()
	{
			// Drop the added columns
			$this->forge->dropColumn('mantenimiento', ['requiere_limpieza']);
	}
}

