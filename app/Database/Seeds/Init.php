<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Init extends Seeder
{
    public function run()
    {
        $this->call('User');
    }
}
