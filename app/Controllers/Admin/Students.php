<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\ClassModel;

class Students extends BaseController
{
    protected $studentModel;
    protected $classModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->classModel = new ClassModel();
    }

    public function index()
    {
        $data = $this->loadDefaultData();
        $data['title'] = 'Manage Students';
        $data['students'] = $this->studentModel->select('students.*, classes.name as class_name')
                                             ->join('classes', 'classes.id = students.class_id')
                                             ->findAll();

        return view('admin/students/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nis' => 'required|is_unique[students.nis]',
                'name' => 'required',
                'class_id' => 'required|numeric',
                'email' => 'required|valid_email|is_unique[students.email]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'nis' => $this->request->getPost('nis'),
                    'name' => $this->request->getPost('name'),
                    'class_id' => $this->request->getPost('class_id'),
                    'email' => $this->request->getPost('email')
                ];

                if ($this->studentModel->insert($data)) {
                    return redirect()->to('/admin-system/students')->with('success', 'Student added successfully');
                }

                return redirect()->back()->with('error', 'Failed to add student');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Add New Student';
        $data['classes'] = $this->classModel->findAll();

        return view('admin/students/create', $data);
    }

    public function edit($id)
    {
        $student = $this->studentModel->find($id);
        
        if (!$student) {
            return redirect()->to('/admin-system/students')->with('error', 'Student not found');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nis' => 'required|is_unique[students.nis,id,' . $id . ']',
                'name' => 'required',
                'class_id' => 'required|numeric',
                'email' => 'required|valid_email|is_unique[students.email,id,' . $id . ']'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'nis' => $this->request->getPost('nis'),
                    'name' => $this->request->getPost('name'),
                    'class_id' => $this->request->getPost('class_id'),
                    'email' => $this->request->getPost('email')
                ];

                if ($this->studentModel->update($id, $data)) {
                    return redirect()->to('/admin-system/students')->with('success', 'Student updated successfully');
                }

                return redirect()->back()->with('error', 'Failed to update student');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Edit Student';
        $data['student'] = $student;
        $data['classes'] = $this->classModel->findAll();

        return view('admin/students/edit', $data);
    }

    public function delete($id)
    {
        if ($this->studentModel->delete($id)) {
            return $this->success('Student deleted successfully');
        }

        return $this->error('Failed to delete student');
    }

    public function import()
    {
        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('file');
            
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(WRITEPATH . 'uploads', $newName);
                
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(WRITEPATH . 'uploads/' . $newName);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Remove header row
                array_shift($rows);
                
                $successCount = 0;
                $errorCount = 0;
                
                foreach ($rows as $row) {
                    $data = [
                        'nis' => $row[0],
                        'name' => $row[1],
                        'class_id' => $row[2],
                        'email' => $row[3]
                    ];
                    
                    // Skip if NIS or email already exists
                    if ($this->studentModel->where('nis', $data['nis'])->orWhere('email', $data['email'])->first()) {
                        $errorCount++;
                        continue;
                    }
                    
                    if ($this->studentModel->insert($data)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                }
                
                unlink(WRITEPATH . 'uploads/' . $newName);
                
                return redirect()->to('/admin-system/students')->with('success', $successCount . ' students imported successfully. ' . $errorCount . ' failed.');
            }
            
            return redirect()->back()->with('error', 'Please upload a valid Excel file');
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Import Students';
        
        return view('admin/students/import', $data);
    }
} 