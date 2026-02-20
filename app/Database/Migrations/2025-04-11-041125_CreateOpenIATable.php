<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOpenIATable extends Migration
{
	public function up()
	{
			$this->forge->addField([
					'id' => [
							'type' => 'INT',
							'constraint' => 11,
							'unsigned' => true,
							'auto_increment' => true,
					],
					'userId' => [
							'type' => 'INT',
							'constraint' => 11,
							'unsigned' => true,
					],
					'question' => [
							'type' => 'TEXT',
							'null'       => true,
					],
					'answer' => [
						'type' => 'TEXT',
						'null'       => true,
					],
					'created_at' => [
							'type' => 'DATETIME',
							'default'   => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
					],
			]);
			$this->forge->addPrimaryKey('id');
			$this->forge->addForeignKey('userId', 'users', 'id', 'CASCADE', 'CASCADE');
			$this->forge->createTable('openia_queries');

	}

	public function down()
	{
			$this->forge->dropTable('openia_queries');
	}
}


// CREATE TABLE `openia_queries` (
// 	`id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
// 	`userId` INT UNSIGNED,
// 	`question` TEXT NOT NULL,
// 	`answer` TEXT NOT NULL,
// 	`created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
// FOREIGN KEY (`userId`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
// ) ENGINE=InnoDB;

