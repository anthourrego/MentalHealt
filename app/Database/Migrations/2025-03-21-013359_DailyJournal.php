<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class DailyJournal extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id'   => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
				'comment'        => 'Identificador único',
			],
			'patient_id' => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'comment'				=> 'Id del paciente que es escribiendo el diario'
			],
			'mood' => [
				'type'        => 'INT',
				'unsigned'    => true,
				'constraint'  => 1,
				'default' 		=> 1,
				"comment"     => 'Estado de ánimo del paciente (1=Pésimo, 2=Malo, 3=Regular, 4=Bueno, 5=Excelente)'
			],
			'content' => [
				'type'        => 'TEXT',
				"null"        => false,
				"comment"     => 'Descripción detallada de cómo se siente'
			],
			'entry_date' => [
				'type'    => 'date',
				'null'    => false,
				'comment' => 'Fecha de la entrada del diario',
			],
			'entry_hour'=> [
				'type'=> 'time',
				'null'=> false,
				'comment'=> 'Hora de la entrada del diario'
			],
			'private_entry' => [
				'type'    => 'TINYINT',
				'constraint' => 1,
				'default' => 0,
				'comment' => 'Indica si la entrada es privada o no (0=No, 1=Sí)',
			],
			'created_at' => [
				'type'    => 'datetime',
				'default' => new RawSql('CURRENT_TIMESTAMP'),
				'comment' => 'Fecha y hora de creación del registro',
			],
			'updated_at' => [
				'type'    => 'datetime',
				'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
				'comment' => 'Fecha y hora de la última actualización del registro',
			]
		]);

		$this->forge->addKey('id', true);
		$this->forge->addForeignKey('patient_id', 'User', 'id', 'CASCADE', 'CASCADE');
		$this->forge->createTable('DailyJournal', false, ATRIBUTOSDB);
	}

	public function down()
	{
		$this->forge->dropTable('DailyJournal');
	}
}
