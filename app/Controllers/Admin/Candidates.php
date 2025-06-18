<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CandidateModel;
use App\Models\PeriodModel;

class Candidates extends BaseController
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
        $data = $this->loadDefaultData();
        $data['title'] = 'Manage Candidates';
        $data['periods'] = $this->periodModel->findAll();
        $data['activePeriod'] = $this->periodModel->getActivePeriod();
        
        if ($data['activePeriod']) {
            $data['candidates'] = $this->candidateModel->getCandidatesWithVotesByPeriod($data['activePeriod']['id']);
        }

        return view('admin/candidates/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required',
                'period_id' => 'required|numeric',
                'vision' => 'required',
                'mission' => 'required',
                'photo' => 'uploaded[photo]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]'
            ];

            if ($this->validate($rules)) {
                $photo = $this->request->getFile('photo');
                $newName = $photo->getRandomName();
                
                if ($photo->move(FCPATH . 'uploads/candidates', $newName)) {
                    $data = [
                        'name' => $this->request->getPost('name'),
                        'period_id' => $this->request->getPost('period_id'),
                        'vision' => $this->request->getPost('vision'),
                        'mission' => $this->request->getPost('mission'),
                        'photo' => $newName
                    ];

                    if ($this->candidateModel->insert($data)) {
                        return redirect()->to('/admin-system/candidates')->with('success', 'Candidate added successfully');
                    }
                }

                return redirect()->back()->with('error', 'Failed to upload photo');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Add New Candidate';
        $data['periods'] = $this->periodModel->findAll();

        return view('admin/candidates/create', $data);
    }

    public function edit($id)
    {
        $candidate = $this->candidateModel->find($id);
        
        if (!$candidate) {
            return redirect()->to('/admin-system/candidates')->with('error', 'Candidate not found');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required',
                'period_id' => 'required|numeric',
                'vision' => 'required',
                'mission' => 'required'
            ];

            // Only validate photo if a new one is uploaded
            if ($this->request->getFile('photo')->isValid()) {
                $rules['photo'] = 'is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]|max_size[photo,2048]';
            }

            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'period_id' => $this->request->getPost('period_id'),
                    'vision' => $this->request->getPost('vision'),
                    'mission' => $this->request->getPost('mission')
                ];

                // Handle photo upload if a new one is provided
                $photo = $this->request->getFile('photo');
                if ($photo->isValid()) {
                    $newName = $photo->getRandomName();
                    if ($photo->move(FCPATH . 'uploads/candidates', $newName)) {
                        // Delete old photo
                        if (file_exists(FCPATH . 'uploads/candidates/' . $candidate['photo'])) {
                            unlink(FCPATH . 'uploads/candidates/' . $candidate['photo']);
                        }
                        $data['photo'] = $newName;
                    }
                }

                if ($this->candidateModel->update($id, $data)) {
                    return redirect()->to('/admin-system/candidates')->with('success', 'Candidate updated successfully');
                }

                return redirect()->back()->with('error', 'Failed to update candidate');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Edit Candidate';
        $data['candidate'] = $candidate;
        $data['periods'] = $this->periodModel->findAll();

        return view('admin/candidates/edit', $data);
    }

    public function delete($id)
    {
        $candidate = $this->candidateModel->find($id);
        
        if (!$candidate) {
            return $this->error('Candidate not found');
        }

        // Delete photo
        if (file_exists(FCPATH . 'uploads/candidates/' . $candidate['photo'])) {
            unlink(FCPATH . 'uploads/candidates/' . $candidate['photo']);
        }

        if ($this->candidateModel->delete($id)) {
            return $this->success('Candidate deleted successfully');
        }

        return $this->error('Failed to delete candidate');
    }
} 