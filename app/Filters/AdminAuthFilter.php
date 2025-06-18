<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $currentUrl = current_url();
        
        log_message('debug', '=== ADMIN AUTH FILTER START ===');
        log_message('debug', 'AdminAuthFilter: Request Method: ' . $request->getMethod());
        log_message('debug', 'AdminAuthFilter: Current URL: ' . $currentUrl);
        log_message('debug', 'AdminAuthFilter: Session ID: ' . session_id());
        log_message('debug', 'AdminAuthFilter: Session data: ' . json_encode($session->get()));
        
        // If not logged in as admin
        if (!$session->get('isAdminLoggedIn')) {
            log_message('debug', 'AdminAuthFilter: User not logged in, redirecting to login');
            log_message('debug', 'AdminAuthFilter: Session missing isAdminLoggedIn flag');
            
            // Don't store login page URL as redirect_url
            if (!str_contains($currentUrl, 'admin-system/login')) {
                $session->setFlashdata('redirect_url', $currentUrl);
                log_message('debug', 'AdminAuthFilter: Setting redirect_url: ' . $currentUrl);
            }
            
            return redirect()->to(base_url('admin-system/login'))
                           ->with('error', 'Please login to access the admin area.');
        }
        
        log_message('debug', 'AdminAuthFilter: User is logged in, proceeding');
        log_message('debug', 'AdminAuthFilter: Admin ID: ' . $session->get('adminId'));
        log_message('debug', '=== ADMIN AUTH FILTER END ===');
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
} 