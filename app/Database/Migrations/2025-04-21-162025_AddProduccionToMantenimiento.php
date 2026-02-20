<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProduccionToMantenimiento extends Migration
{
    public function up()
    {
			$this->forge->addColumn('mantenimiento', [
				'calidadId' => [
					'type'       => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'null'       => true,
					'after'      => 'responsableId'
				],
				'produccionId' => [
					'type'       => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'null'       => true,
					'after'      => 'calidadId'
				],
				'limpiezaId' => [
					'type'       => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'null'       => true,
					'after'      => 'produccionId'
				],
				'firma_calidad' => [
					'type'       => 'VARCHAR',
					'constraint' => 255,
					'null'       => true,
					'after'      => 'firma_responsable'
				],
				'firma_produccion' => [
					'type'       => 'VARCHAR',
					'constraint' => 255,
					'null'       => true,
					'after'      => 'firma_calidad'
				],
				'firma_limpieza' => [
					'type'       => 'VARCHAR',
					'constraint' => 255,
					'null'       => true,
					'after'      => 'firma_produccion'
				],
			]);

			$this->db->query('ALTER TABLE mantenimiento ADD CONSTRAINT fk_calidad FOREIGN KEY (calidadId) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
			$this->db->query('ALTER TABLE mantenimiento ADD CONSTRAINT fk_produccion FOREIGN KEY (produccionId) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
			$this->db->query('ALTER TABLE mantenimiento ADD CONSTRAINT fk_limpieza FOREIGN KEY (limpiezaId) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
			$this->forge->dropColumn('mantenimiento', ['calidadId', 'produccionId', 'limpiezaId',  'firma_calidad', 'firma_produccion', 'firma_limpieza']);
    }
}
