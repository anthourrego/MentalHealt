<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class User extends Migration
{
    public function up()
    {
        $this->forge->addField([
			'id'   => [
				'type'           => 'INT',
				'constraint'     => 11,
				'unsigned'       => true,
				'auto_increment' => true,
				'comment'        => 'Identificador único para cada usuario',
			],
			'email' => [
				'type'           => 'VARCHAR',
				'constraint'     => 255,
				'unique'         => true,
				'null'           => false,
				'comment'        => 'Correo electrónico del usuario (único en el sistema)',
			],
			'password' => [
				'type'           => 'TEXT',
				'comment'        => 'Contraseña encriptada del usuario',
			],
			'first_name' => [
				'type'           => 'VARCHAR',
				'constraint'     => 255,
				'comment'        => 'Nombres del usuario',
			],
			'last_name' => [
				'type'           => 'VARCHAR',
				'constraint'     => 255,
				'comment'        => 'Apellidos del usuario',
			],
			'profile' => [
				'type'           => 'INT',
				'constraint'     => 1,
				'null'           => false,
				'default'        => 3,
				'comment'        => 'Perfil de usuario (1=Admin, 2=Terapeuta, 3=Paciente)',
			],
			'status' => [
				'type'           => 'TINYINT',
				'constraint'     => 1,
				'default'        => 1,
				'comment'        => 'Estado del usuario (1=Activo, 0=Inactivo)',
			],
			'last_login' => [
				'type'           => 'datetime',
				'null'           => true,
				'comment'        => 'Fecha y hora del último acceso al sistema',
			],
			'email_confirm'=> [
				'type'=> 'TINYINT',
				'constraint'=> 1,
				'default'=> 0,
				'comment'=> 'Indica si el correo del usuario ha sido confirmado'
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
		$this->forge->createTable('User', false, ATRIBUTOSDB);
    }

    public function down()
    {
        $this->forge->dropTable('User');
    }
}
