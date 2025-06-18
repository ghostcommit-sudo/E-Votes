<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'year_start' => date('Y'),
            'year_end' => date('Y') + 1,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Using Query Builder
        $this->db->table('periods')->insert($data);
    }
} 