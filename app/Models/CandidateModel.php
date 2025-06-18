<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateModel extends Model
{
    protected $table = 'candidates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['period_id', 'name', 'photo', 'vision', 'mission'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Get candidates by period
    public function getCandidatesByPeriod($periodId)
    {
        return $this->where('period_id', $periodId)->findAll();
    }

    // Get candidate with vote count
    public function getCandidateWithVotes($candidateId)
    {
        $db = \Config\Database::connect();
        return $db->table('candidates c')
            ->select('c.*, COUNT(v.id) as vote_count')
            ->join('votes v', 'v.candidate_id = c.id', 'left')
            ->where('c.id', $candidateId)
            ->groupBy('c.id')
            ->get()
            ->getRowArray();
    }

    // Get all candidates with vote counts for a specific period
    public function getCandidatesWithVotesByPeriod($periodId)
    {
        $db = \Config\Database::connect();
        return $db->table('candidates c')
            ->select('c.*, COUNT(v.id) as vote_count')
            ->join('votes v', 'v.candidate_id = c.id', 'left')
            ->where('c.period_id', $periodId)
            ->groupBy('c.id')
            ->get()
            ->getResultArray();
    }
} 