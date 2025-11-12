<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::doLogin');
$routes->get('/logout', 'Auth::logout');
$routes->get('/register', 'Register::index');
$routes->post('/register/registrar', 'Register::registrar');
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('/dashboard', 'Dashboard::index');
$routes->get('/admin/users', 'Admin\Users::index');
$routes->post('/admin/users/create', 'Admin\Users::create');
$routes->get('/admin/users/create', 'Admin\Users::create');


