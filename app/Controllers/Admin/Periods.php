<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeriodModel;

class Periods extends BaseController
{
    protected $periodModel;

    public function __construct()
    {
        $this->periodModel = new PeriodModel();
    }

    public function index()
    {
        $data = $this->loadDefaultData();
        $data['title'] = 'Manage Periods';
        $data['periods'] = $this->periodModel->findAll();

        return view('admin/periods/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'year_start' => 'required|numeric|exact_length[4]',
                'year_end' => 'required|numeric|exact_length[4]|greater_than[year_start]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'year_start' => $this->request->getPost('year_start'),
                    'year_end' => $this->request->getPost('year_end'),
                    'status' => 'inactive'
                ];

                if ($this->periodModel->insert($data)) {
                    return redirect()->to('/admin-system/periods')->with('success', 'Period added successfully');
                }

                return redirect()->back()->with('error', 'Failed to add period');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Add New Period';

        return view('admin/periods/create', $data);
    }

    public function edit($id)
    {
        $period = $this->periodModel->find($id);
        
        if (!$period) {
            return redirect()->to('/admin-system/periods')->with('error', 'Period not found');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'year_start' => 'required|numeric|exact_length[4]',
                'year_end' => 'required|numeric|exact_length[4]|greater_than[year_start]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'year_start' => $this->request->getPost('year_start'),
                    'year_end' => $this->request->getPost('year_end')
                ];

                if ($this->periodModel->update($id, $data)) {
                    return redirect()->to('/admin-system/periods')->with('success', 'Period updated successfully');
                }

                return redirect()->back()->with('error', 'Failed to update period');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Edit Period';
        $data['period'] = $period;

        return view('admin/periods/edit', $data);
    }

    public function delete($id)
    {
        $period = $this->periodModel->find($id);
        
        if (!$period) {
            return $this->error('Period not found');
        }

        // Don't allow deleting active period
        if ($period['status'] === 'active') {
            return $this->error('Cannot delete active period');
        }

        if ($this->periodModel->delete($id)) {
            return $this->success('Period deleted successfully');
        }

        return $this->error('Failed to delete period');
    }

    public function setActive($id)
    {
        $period = $this->periodModel->find($id);
        
        if (!$period) {
            return $this->error('Period not found');
        }

        if ($this->periodModel->setActivePeriod($id)) {
            return $this->success('Period set as active successfully');
        }

        return $this->error('Failed to set period as active');
    }
} 