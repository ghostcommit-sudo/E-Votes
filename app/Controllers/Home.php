<?php

namespace App\Controllers;

use App\Models\CandidateModel;
use App\Models\PeriodModel;

class Home extends BaseController
{
    protected $candidateModel;
    protected $periodModel;

    public function __construct()
    {
        $this->candidateModel = new CandidateModel();
        $this->periodModel = new PeriodModel();
    }

    public function index()
    {
        $activePeriod = $this->periodModel->getActivePeriod();
        
        $data = $this->loadDefaultData();
        $data['title'] = 'E-Votes - Student Council Election';
        $data['activePeriod'] = $activePeriod;
        $data['candidates'] = []; // Initialize as empty array by default
        $data['period'] = $activePeriod; // Add period data for the view
        
        if ($activePeriod) {
            $data['candidates'] = $this->candidateModel->getCandidatesWithVotesByPeriod($activePeriod['id']);
        }

        return view('home', $data);
    }

    public function login()
    {
        // If already logged in as student
        if (session()->get('isStudentLoggedIn')) {
            return redirect()->to('/vote');
        }

        // Clear any existing session data except CSRF token
        $csrf_token = session()->get('csrf_token');
        session()->destroy();
        session()->start();
        session()->set('csrf_token', $csrf_token);

        $data = $this->loadDefaultData();
        $data['title'] = 'Login';

        return view('auth/login', $data);
    }
}
