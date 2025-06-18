<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nis', 'name', 'class_id', 'email', 'google_id', 'has_voted'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get student by Google ID
    public function getStudentByGoogleId($googleId)
    {
        return $this->where('google_id', $googleId)->first();
    }

    // Get student by NIS
    public function getStudentByNIS($nis)
    {
        return $this->where('nis', $nis)->first();
    }

    // Get student with class information
    public function getStudentWithClass($studentId)
    {
        $db = \Config\Database::connect();
        return $db->table('students s')
            ->select('s.*, c.name as class_name')
            ->join('classes c', 'c.id = s.class_id')
            ->where('s.id', $studentId)
            ->get()
            ->getRowArray();
    }

    // Check if student has voted
    public function hasVoted($studentId)
    {
        return $this->where('id', $studentId)->where('has_voted', true)->countAllResults() > 0;
    }

    // Mark student as voted
    public function markAsVoted($studentId)
    {
        return $this->update($studentId, ['has_voted' => true]);
    }
} 