<?php

namespace App\Controllers;

use App\Models\CandidateModel;
use App\Models\VoteModel;
use App\Models\PeriodModel;
use App\Models\StudentModel;
use TCPDF;

class Vote extends BaseController
{
    protected $candidateModel;
    protected $voteModel;
    protected $periodModel;
    protected $studentModel;

    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
        $this->voteModel = new VoteModel();
        $this->periodModel = new PeriodModel();
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        $activePeriod = $this->periodModel->getActivePeriod();
        
        if (!$activePeriod) {
            return redirect()->to('/')->with('error', 'No active voting period');
        }

        $studentId = session()->get('studentId');
        
        // Check if student has already voted
        if ($this->voteModel->hasStudentVoted($studentId, $activePeriod['id'])) {
            return redirect()->to('/vote/receipt');
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Vote';
        $data['activePeriod'] = $activePeriod;
        $data['candidates'] = $this->candidateModel->getCandidatesByPeriod($activePeriod['id']);

        return view('vote/index', $data);
    }

    public function submit()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/vote');
        }

        $activePeriod = $this->periodModel->getActivePeriod();
        
        if (!$activePeriod) {
            return $this->error('No active voting period');
        }

        $studentId = session()->get('studentId');
        
        // Check if student has already voted
        if ($this->voteModel->hasStudentVoted($studentId, $activePeriod['id'])) {
            return $this->error('You have already voted');
        }

        $candidateId = $this->request->getPost('candidate_id');
        
        if (!$candidateId) {
            return $this->error('Please select a candidate');
        }

        // Generate proof PDF
        $proofPdf = $this->generateProofPdf($studentId, $activePeriod['id']);
        
        if (!$proofPdf) {
            return $this->error('Failed to generate proof PDF');
        }

        // Record vote
        if ($this->voteModel->recordVote($studentId, $candidateId, $activePeriod['id'], $proofPdf)) {
            // Mark student as voted
            $this->studentModel->markAsVoted($studentId);
            
            return $this->success('Vote recorded successfully', [
                'redirect' => '/vote/receipt'
            ]);
        }

        return $this->error('Failed to record vote');
    }

    public function receipt()
    {
        $studentId = session()->get('studentId');
        $activePeriod = $this->periodModel->getActivePeriod();
        
        if (!$activePeriod) {
            return redirect()->to('/')->with('error', 'No active voting period');
        }

        // Get vote details
        $vote = $this->voteModel->where('student_id', $studentId)
                               ->where('period_id', $activePeriod['id'])
                               ->first();
        
        if (!$vote) {
            return redirect()->to('/vote');
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Vote Receipt';
        $data['vote'] = $vote;
        $data['student'] = $this->studentModel->getStudentWithClass($studentId);

        return view('vote/receipt', $data);
    }

    protected function generateProofPdf($studentId, $periodId)
    {
        $student = $this->studentModel->getStudentWithClass($studentId);
        $period = $this->periodModel->find($periodId);

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('E-Votes System');
        $pdf->SetTitle('Voting Proof');

        // Remove header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // Add content
        $html = '
        <h1 style="text-align:center;">Voting Proof</h1>
        <p style="text-align:center;">This document certifies that:</p>
        <p style="text-align:center;font-size:14px;font-weight:bold;">' . $student['name'] . '</p>
        <p style="text-align:center;">NIS: ' . $student['nis'] . '<br>Class: ' . $student['class_name'] . '</p>
        <p style="text-align:center;">has participated in the Student Council Election<br>for the period ' . $period['year_start'] . '/' . $period['year_end'] . '</p>
        <p style="text-align:center;font-style:italic;">Vote cast on: ' . date('Y-m-d H:i:s') . '</p>
        ';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Generate file name
        $fileName = 'vote_proof_' . $student['nis'] . '_' . time() . '.pdf';
        $filePath = FCPATH . 'uploads/proofs/' . $fileName;

        // Save PDF
        if ($pdf->Output($filePath, 'F')) {
            return $fileName;
        }

        return false;
    }
} 