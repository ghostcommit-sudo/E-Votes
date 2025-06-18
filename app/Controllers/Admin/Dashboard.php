<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CandidateModel;
use App\Models\StudentModel;
use App\Models\VoteModel;
use App\Models\PeriodModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected $candidateModel;
    protected $studentModel;
    protected $voteModel;
    protected $periodModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->candidateModel = new CandidateModel();
        $this->studentModel = new StudentModel();
        $this->voteModel = new VoteModel();
        $this->periodModel = new PeriodModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'totalCandidates' => $this->candidateModel->countAll(),
            'totalStudents' => $this->studentModel->countAll(),
            'totalVotes' => $this->voteModel->countAll(),
            'activePeriod' => $this->periodModel->where('status', 'active')->first(),
            'voteResults' => $this->getVoteResults()
        ];
        
        return view('admin/dashboard', $data);
    }

    private function getVoteResults()
    {
        $activePeriod = $this->periodModel->where('status', 'active')->first();
        
        if (!$activePeriod) {
            return [];
        }
        
        $candidates = $this->candidateModel->where('period_id', $activePeriod['id'])->findAll();
        $results = [];
        
        foreach ($candidates as $candidate) {
            $voteCount = $this->voteModel
                ->where('candidate_id', $candidate['id'])
                ->where('period_id', $activePeriod['id'])
                ->countAllResults();
                
            $results[] = [
                'name' => $candidate['name'],
                'votes' => $voteCount
            ];
        }
        
        return $results;
    }

    public function exportVoteResults()
    {
        $results = $this->getVoteResults();
        
        // Create CSV content
        $csv = "Candidate Name,Vote Count\n";
        foreach ($results as $result) {
            $csv .= "\"{$result['name']}\",{$result['votes']}\n";
        }
        
        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="vote_results.csv"');
        
        echo $csv;
        exit;
    }
} 