<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassModel;

class Classes extends BaseController
{
    protected $classModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
    }

    public function index()
    {
        $data = $this->loadDefaultData();
        $data['title'] = 'Manage Classes';
        $data['classes'] = $this->classModel->getAllClassesWithStudentCount();

        return view('admin/classes/index', $data);
    }

    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|is_unique[classes.name]'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name')
                ];

                if ($this->classModel->insert($data)) {
                    return redirect()->to('/admin-system/classes')->with('success', 'Class added successfully');
                }

                return redirect()->back()->with('error', 'Failed to add class');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Add New Class';

        return view('admin/classes/create', $data);
    }

    public function edit($id)
    {
        $class = $this->classModel->find($id);
        
        if (!$class) {
            return redirect()->to('/admin-system/classes')->with('error', 'Class not found');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|is_unique[classes.name,id,' . $id . ']'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name')
                ];

                if ($this->classModel->update($id, $data)) {
                    return redirect()->to('/admin-system/classes')->with('success', 'Class updated successfully');
                }

                return redirect()->back()->with('error', 'Failed to update class');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = $this->loadDefaultData();
        $data['title'] = 'Edit Class';
        $data['class'] = $class;

        return view('admin/classes/edit', $data);
    }

    public function delete($id)
    {
        // Check if class has students
        $classWithStudents = $this->classModel->getClassWithStudentCount($id);
        
        if ($classWithStudents && $classWithStudents['student_count'] > 0) {
            return $this->error('Cannot delete class with students');
        }

        if ($this->classModel->delete($id)) {
            return $this->success('Class deleted successfully');
        }

        return $this->error('Failed to delete class');
    }
} 