<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get class with student count
    public function getClassWithStudentCount($classId)
    {
        $db = \Config\Database::connect();
        return $db->table('classes c')
            ->select('c.*, COUNT(s.id) as student_count')
            ->join('students s', 's.class_id = c.id', 'left')
            ->where('c.id', $classId)
            ->groupBy('c.id')
            ->get()
            ->getRowArray();
    }

    // Get all classes with student counts
    public function getAllClassesWithStudentCount()
    {
        $db = \Config\Database::connect();
        return $db->table('classes c')
            ->select('c.*, COUNT(s.id) as student_count')
            ->join('students s', 's.class_id = c.id', 'left')
            ->groupBy('c.id')
            ->get()
            ->getResultArray();
    }
} 