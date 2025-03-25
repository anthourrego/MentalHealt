<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Appointment extends Migration
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
				'comment' => 'Id del paciente que solicito la cita'
			],
			'therapist_id' => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'comment' => 'Id del terapista que va a tomar la cita'
			],
			'status' => [
				'type'       => 'CHAR',
				'constraint'    => 2,
				'default'    => 'PE',
				'null'       => false,
				'comment'    => 'Estado de la cita (PE=Pendiente, CO=Confirmado, CP=Cancelado por el paciente, CT=Cancelado por el terapita, CC=Completado, NS=No presentados)',
			],
			'modality' => [
				'type'       => 'CHAR',
				'constraint'    => 2,
				'default'    => 'IP',
				'null'       => false,
				'comment'    => 'Modalidad de la cita (IP=Presencial, VC=Videollamada, PC=Llamada telefónica)',
			],
			'appointment_date' => [
				'type'       => 'DATE',
				'null'       => false,
				'comment'    => 'Fecha de la cita',
			],
			'appointment_time' => [
				'type'       => 'TIME',
				'null'       => false,
				'comment'    => 'Hora de la cita',
			],
			'video_url' => [
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'null'       => true,
				'comment'    => 'URL de la videollamada',
			],
			'notes' => [
				'type'       => 'TEXT',
				'null'       => true,
				'comment'    => 'Notas o comentarios adicionales',
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
		$this->forge->addForeignKey('therapist_id', 'User', 'id', 'CASCADE', 'CASCADE');
		$this->forge->addKey(['therapist_id', 'appointment_date'], false, true);
		$this->forge->createTable('Appointment', false, ATRIBUTOSDB);
	}

	public function down()
	{
		$this->forge->dropTable('Appointment');
	}
}
