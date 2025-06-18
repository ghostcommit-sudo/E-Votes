<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_BCRYPT),
            'name'     => 'Administrator',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // First, check if admin already exists
        $existingAdmin = $this->db->table('admin')
                                ->where('username', 'admin')
                                ->get()
                                ->getRow();

        if (!$existingAdmin) {
            // Insert only if admin doesn't exist
            $this->db->table('admin')->insert($data);
            echo "Admin user created successfully\n";
        } else {
            echo "Admin user already exists\n";
        }
    }
} 