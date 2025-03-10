<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class User extends Seeder
{
    public function run() {
		$datos = array(
			"email" => "anthourrego@gmail.com",
			"password" => password_hash("12345678", PASSWORD_DEFAULT, array("cost" => 15)),
			"first_name" => "Anthony Smidh",
			"last_name" => "Urrego Pineda",
			"profile" => 1,
		);
		$this->db->table('user')->insert($datos);
	}
}
