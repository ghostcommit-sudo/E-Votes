<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Home routes
$routes->get('/', 'Home::index');
$routes->get('login', 'Home::login');

// Auth routes
$routes->get('auth/google', 'Auth::googleLogin');
$routes->get('auth/google/callback', 'Auth::googleCallback');
$routes->get('auth/student-info', 'Auth::studentInfo');
$routes->post('auth/student-info', 'Auth::studentInfo');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::register');
$routes->get('logout', 'Auth::studentLogout');

// Admin System Routes
$routes->group('admin-system', function($routes) {
    // Auth routes
    $routes->get('login', 'Admin\AuthController::login');
    $routes->post('login', 'Admin\AuthController::login');
    $routes->get('logout', 'Admin\AuthController::logout');
    
    // Admin routes (no auth filter)
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('dashboard/export', 'Admin\Dashboard::exportVoteResults');
    
    $routes->get('candidates', 'Admin\Candidates::index');
    $routes->get('candidates/create', 'Admin\Candidates::create');
    $routes->post('candidates/create', 'Admin\Candidates::create');
    $routes->get('candidates/edit/(:num)', 'Admin\Candidates::edit/$1');
    $routes->post('candidates/edit/(:num)', 'Admin\Candidates::edit/$1');
    $routes->delete('candidates/delete/(:num)', 'Admin\Candidates::delete/$1');
    
    $routes->get('students', 'Admin\Students::index');
    $routes->get('students/create', 'Admin\Students::create');
    $routes->post('students/create', 'Admin\Students::create');
    $routes->get('students/edit/(:num)', 'Admin\Students::edit/$1');
    $routes->post('students/edit/(:num)', 'Admin\Students::edit/$1');
    $routes->delete('students/delete/(:num)', 'Admin\Students::delete/$1');
    
    $routes->get('classes', 'Admin\Classes::index');
    $routes->get('classes/create', 'Admin\Classes::create');
    $routes->post('classes/create', 'Admin\Classes::create');
    $routes->get('classes/edit/(:num)', 'Admin\Classes::edit/$1');
    $routes->post('classes/edit/(:num)', 'Admin\Classes::edit/$1');
    $routes->delete('classes/delete/(:num)', 'Admin\Classes::delete/$1');
    
    $routes->get('periods', 'Admin\Periods::index');
    $routes->get('periods/create', 'Admin\Periods::create');
    $routes->post('periods/create', 'Admin\Periods::create');
    $routes->get('periods/edit/(:num)', 'Admin\Periods::edit/$1');
    $routes->post('periods/edit/(:num)', 'Admin\Periods::edit/$1');
    $routes->delete('periods/delete/(:num)', 'Admin\Periods::delete/$1');
});

// Vote routes (protected by studentAuth filter)
$routes->group('vote', ['filter' => 'studentAuth'], function($routes) {
    $routes->get('/', 'Vote::index');
    $routes->post('submit', 'Vote::submit');
    $routes->get('receipt', 'Vote::receipt');
});
