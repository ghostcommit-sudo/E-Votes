<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckAdmin extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name       = 'admin:check';
    protected $description = 'Checks the admin table and its contents';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // Check if admin table exists
        $tables = $db->listTables();
        if (!in_array('admin', $tables)) {
            CLI::error('Admin table does not exist!');
            return;
        }

        CLI::write('Admin table exists', 'green');

        // Check admin table contents
        $query = $db->query('SELECT id, username, name FROM admin');
        $results = $query->getResultArray();

        if (empty($results)) {
            CLI::error('No admin users found in the table!');
            return;
        }

        CLI::write('Found ' . count($results) . ' admin users:', 'green');
        foreach ($results as $row) {
            CLI::write("ID: {$row['id']}, Username: {$row['username']}, Name: {$row['name']}");
        }
    }
} 