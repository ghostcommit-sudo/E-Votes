<?php

namespace App\Models;

use CodeIgniter\Model;

class VoteModel extends Model
{
    protected $table = 'votes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['student_id', 'candidate_id', 'period_id', 'proof_pdf'];
    protected $useTimestamps = false;
    protected $createdField  = 'voted_at';

    // Record a new vote
    public function recordVote($studentId, $candidateId, $periodId, $proofPdf = null)
    {
        return $this->insert([
            'student_id' => $studentId,
            'candidate_id' => $candidateId,
            'period_id' => $periodId,
            'proof_pdf' => $proofPdf
        ]);
    }

    // Get vote results for a period
    public function getVoteResults($periodId)
    {
        $db = \Config\Database::connect();
        return $db->table('candidates c')
            ->select('c.name, COUNT(v.id) as vote_count')
            ->join('votes v', 'v.candidate_id = c.id', 'left')
            ->where('c.period_id', $periodId)
            ->groupBy('c.id, c.name')
            ->orderBy('vote_count', 'DESC')
            ->get()
            ->getResultArray();
    }

    // Get total votes for a period
    public function getTotalVotes($periodId)
    {
        return $this->where('period_id', $periodId)->countAllResults();
    }

    // Check if student has already voted in a period
    public function hasStudentVoted($studentId, $periodId)
    {
        return $this->where('student_id', $studentId)
                    ->where('period_id', $periodId)
                    ->countAllResults() > 0;
    }

    // Get vote details with student and candidate information
    public function getVoteDetails($voteId)
    {
        $db = \Config\Database::connect();
        return $db->table('votes v')
            ->select('v.*, s.name as student_name, s.nis, c.name as candidate_name')
            ->join('students s', 's.id = v.student_id')
            ->join('candidates c', 'c.id = v.candidate_id')
            ->where('v.id', $voteId)
            ->get()
            ->getRowArray();
    }
} 