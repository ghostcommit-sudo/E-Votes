<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    protected $adminModel;
    
    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }
    
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required|min_length[3]',
                'password' => 'required|min_length[6]'
            ];
            
            if ($this->validate($rules)) {
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                
                $admin = $this->adminModel->where('username', $username)->first();
                
                if ($admin && password_verify($password, $admin['password'])) {
                    // Set session data
                    $sessionData = [
                        'adminId' => $admin['id'],
                        'adminName' => $admin['name'],
                        'username' => $admin['username'],
                        'isAdminLoggedIn' => true
                    ];
                    
                    session()->set($sessionData);
                    
                    // Check if there's a redirect URL stored in session
                    $redirectUrl = session()->getFlashdata('redirect_url');
                    if ($redirectUrl) {
                        return redirect()->to($redirectUrl);
                    }
                    
                    return redirect()->to(base_url('admin-system/dashboard'))
                                   ->with('success', 'Welcome back, ' . $admin['name']);
                }
                
                return redirect()->back()
                               ->with('error', 'Invalid username or password')
                               ->withInput();
            }
            
            return redirect()->back()
                           ->with('errors', $this->validator->getErrors())
                           ->withInput();
        }
        
        // GET request - show login form
        return view('auth/admin_login', [
            'title' => 'Admin Login'
        ]);
    }
    
    public function logout()
    {
        // Clear admin session data
        session()->remove([
            'adminId',
            'adminName',
            'username',
            'isAdminLoggedIn'
        ]);
        
        return redirect()->to(base_url('admin-system/login'))
                       ->with('success', 'You have been logged out successfully');
    }
} 