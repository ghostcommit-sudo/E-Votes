<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\StudentModel;

class Auth extends BaseController
{
    protected $adminModel;
    protected $studentModel;
    protected $classModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->studentModel = new StudentModel();
        $this->classModel = new \App\Models\ClassModel();
        helper('google');
    }

    public function adminLogin()
    {
        helper(['form']);
        
        // Debug: Check current session state
        log_message('debug', '=== START ADMIN LOGIN PROCESS ===');
        log_message('debug', 'Current session state: ' . json_encode(session()->get()));
        log_message('debug', 'Request Method: ' . $this->request->getMethod());
        log_message('debug', 'POST Data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Current URL: ' . current_url());
        log_message('debug', 'Session ID: ' . session_id());
        
        // If already logged in, redirect to dashboard
        if (session()->get('isAdminLoggedIn')) {
            log_message('debug', 'Already logged in, redirecting to dashboard');
            return redirect()->to(base_url('admin-system/dashboard'));
        }

        if ($this->request->getMethod() === 'post') {
            log_message('debug', 'Processing POST request...');
            
            $rules = [
                'username' => 'required|min_length[3]',
                'password' => 'required|min_length[3]'
            ];
            
            log_message('debug', 'Starting validation...');
            
            if ($this->validate($rules)) {
                log_message('debug', 'Validation passed');
                
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');

                log_message('debug', 'Attempting database lookup for username: ' . $username);
                
                // Debug: Check if we can find the admin
                $admin = $this->adminModel->where('username', $username)->first();
                log_message('debug', 'Database lookup complete. Admin found: ' . ($admin ? 'Yes' : 'No'));
                
                if ($admin) {
                    log_message('debug', 'Admin found, verifying password...');
                    
                    // Debug: Check password verification
                    $passwordVerified = password_verify($password, $admin['password']);
                    log_message('debug', 'Password verification result: ' . ($passwordVerified ? 'Success' : 'Failed'));
                    
                    if ($passwordVerified) {
                        log_message('debug', 'Password verified successfully');
                        
                        // Set session data
                        $sessionData = [
                            'isAdminLoggedIn' => true,
                            'adminId' => $admin['id'],
                            'adminName' => $admin['name']
                        ];
                        
                        log_message('debug', 'Attempting to set session data...');
                        
                        try {
                            // Set session data
                            foreach ($sessionData as $key => $value) {
                                session()->set($key, $value);
                            }
                            
                            log_message('debug', 'Session data set successfully');
                            log_message('debug', 'New session state: ' . json_encode(session()->get()));
                            log_message('debug', 'New Session ID: ' . session_id());
                            
                            log_message('debug', 'Redirecting to dashboard...');
                            return redirect()->to(base_url('admin-system/dashboard'));
                        } catch (\Exception $e) {
                            log_message('error', 'Session error: ' . $e->getMessage());
                            return redirect()->back()
                                           ->withInput()
                                           ->with('error', 'Session error occurred');
                        }
                    }
                    
                    log_message('debug', 'Password verification failed');
                } else {
                    log_message('debug', 'Admin not found in database');
                }

                log_message('debug', 'Authentication failed, redirecting back');
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Invalid username or password');
            } else {
                log_message('debug', 'Validation failed');
                log_message('debug', 'Validation errors: ' . json_encode($this->validator->getErrors()));
                return redirect()->back()
                               ->withInput()
                               ->with('errors', $this->validator->getErrors());
            }
        }

        return view('auth/admin_login', [
            'title' => 'Admin Login',
            'validation' => \Config\Services::validation()
        ]);
    }

    public function adminLogout()
    {
        $this->session->remove(['isAdminLoggedIn', 'adminId', 'adminName']);
        return redirect()->to('/admin-system/login');
    }

    public function googleLogin()
    {
        // Redirect to Google OAuth page
        return redirect()->to(getGoogleAuthUrl());
    }

    public function googleCallback()
    {
        try {
            log_message('debug', '=== START GOOGLE CALLBACK ===');
            $client = initGoogleClient();
            
            if (empty($this->request->getVar('code'))) {
                log_message('debug', 'No authorization code received');
                return redirect()->to('login')
                               ->with('error', 'Authorization code not received from Google.');
            }
            
            // Exchange authorization code for access token
            $token = $client->fetchAccessTokenWithAuthCode($this->request->getVar('code'));
            
            if (isset($token['error'])) {
                log_message('debug', 'Token error: ' . $token['error']);
                return redirect()->to('login')
                               ->with('error', 'Error fetching access token: ' . $token['error']);
            }
            
            // Get user information from Google
            $userInfo = getGoogleUserInfo($token);
            
            // Check if email is from allowed domain (e.g., school domain)
            $email = $userInfo->getEmail();
            log_message('debug', 'Google user email: ' . $email);
            
            // Find student record
            $student = $this->studentModel->where('email', $email)->first();
            log_message('debug', 'Found student: ' . json_encode($student));
            
            if (!$student) {
                // Create new student record
                $studentData = [
                    'email' => $email,
                    'name' => $userInfo->getName(),
                    'google_id' => $userInfo->getId(),
                    'picture' => $userInfo->getPicture()
                ];
                
                // Store Google data in session before redirect
                session()->set('google_data', $studentData);
                log_message('debug', 'New user - redirecting to student-info');
                
                // Redirect to complete registration if needed
                return redirect()->to('auth/student-info');
            }
            
            // Existing student - set session data
            $sessionData = [
                'studentId' => $student['id'],
                'studentName' => $student['name'],
                'studentEmail' => $student['email'],
                'isStudentLoggedIn' => true
            ];
            
            log_message('debug', 'Setting session data for existing student: ' . json_encode($sessionData));
            session()->set($sessionData);
            
            // Redirect to vote page or dashboard
            log_message('debug', 'Redirecting existing student to vote page');
            return redirect()->to('vote')
                           ->with('success', 'Welcome back, ' . $student['name']);
            
        } catch (\Exception $e) {
            log_message('error', '[Google OAuth] ' . $e->getMessage());
            return redirect()->to('login')
                           ->with('error', 'An error occurred during Google login. Please try again.');
        }
    }

    public function studentInfo()
    {
        // Debug logging
        log_message('debug', '=== START STUDENT INFO PROCESS ===');
        log_message('debug', 'Request Method: ' . $this->request->getMethod());
        log_message('debug', 'Current session state: ' . json_encode(session()->get()));
        
        // Check if we have Google data in the session
        $googleData = session()->get('google_data');
        log_message('debug', 'Google data from session: ' . json_encode($googleData));
        
        if (!$googleData) {
            log_message('debug', 'No Google data found in session, redirecting to login');
            return redirect()->to('login')
                           ->with('error', 'Please login with Google first.');
        }

        // Check if student already exists with this email
        $existingStudent = $this->studentModel->where('email', $googleData['email'])->first();
        if ($existingStudent) {
            // Set session data for existing student
            $sessionData = [
                'studentId' => $existingStudent['id'],
                'studentName' => $existingStudent['name'],
                'studentEmail' => $existingStudent['email'],
                'isStudentLoggedIn' => true
            ];
            
            session()->set($sessionData);
            return redirect()->to('vote')
                           ->with('success', 'Welcome back, ' . $existingStudent['name']);
        }

        if ($this->request->getMethod() === 'post') {
            log_message('debug', 'Processing POST request...');
            log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
            
            $rules = [
                'nis' => 'required|is_unique[students.nis]',
                'class_id' => 'required|numeric'
            ];

            if ($this->validate($rules)) {
                log_message('debug', 'Validation passed');
                
                $data = [
                    'nis' => $this->request->getPost('nis'),
                    'class_id' => $this->request->getPost('class_id'),
                    'name' => $googleData['name'],
                    'email' => $googleData['email'],
                    'google_id' => $googleData['google_id'],
                    'picture' => $googleData['picture']
                ];

                try {
                    $this->studentModel->insert($data);
                    
                    // Set session data
                    $sessionData = [
                        'studentId' => $this->studentModel->getInsertID(),
                        'studentName' => $data['name'],
                        'studentEmail' => $data['email'],
                        'isStudentLoggedIn' => true
                    ];
                    
                    session()->set($sessionData);
                    session()->remove('google_data'); // Clean up temporary data
                    
                    return redirect()->to('vote')
                                   ->with('success', 'Welcome, ' . $data['name']);
                } catch (\Exception $e) {
                    log_message('error', 'Error saving student: ' . $e->getMessage());
                    return redirect()->back()
                                   ->withInput()
                                   ->with('error', 'An error occurred while saving your information. Please try again.');
                }
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('errors', $this->validator->getErrors());
            }
        }

        // Load the student info form
        return view('auth/student_info', [
            'title' => 'Complete Registration',
            'validation' => \Config\Services::validation(),
            'classes' => $this->classModel->findAll(),
            'googleData' => $googleData
        ]);
    }

    public function register()
    {
        if (!$this->session->get('email')) {
            return redirect()->to('/login');
        }

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nis' => 'required|is_unique[students.nis]',
                'class_id' => 'required|numeric'
            ];

            if ($this->validate($rules)) {
                $data = [
                    'nis' => $this->request->getPost('nis'),
                    'class_id' => $this->request->getPost('class_id'),
                    'name' => $this->session->get('name'),
                    'email' => $this->session->get('email'),
                    'google_id' => $this->session->get('google_id')
                ];

                if ($this->studentModel->insert($data)) {
                    $student = $this->studentModel->where('email', $data['email'])->first();
                    
                    $this->session->set([
                        'isStudentLoggedIn' => true,
                        'studentId' => $student['id'],
                        'studentName' => $student['name'],
                        'studentClass' => $student['class_id']
                    ]);

                    // Remove temporary Google data
                    $this->session->remove(['google_id', 'email', 'name']);

                    return redirect()->to('/vote');
                }

                return redirect()->back()->with('error', 'Failed to register student');
            }

            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        return view('auth/register', $this->loadDefaultData());
    }

    public function studentLogout()
    {
        // Clear all session data except CSRF token
        $csrf_token = session()->get('csrf_token');
        session()->destroy();
        session()->start();
        session()->set('csrf_token', $csrf_token);
        
        return redirect()->to('login')
                        ->with('success', 'You have been logged out successfully.');
    }
} 