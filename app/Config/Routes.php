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

$routes->group('admin', ['filter' => 'auth:Administrador'], function($routes) {

    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/create', 'Admin\Users::create');
    $routes->post('users/update', 'Admin\Users::update');
    $routes->post('users/delete', 'Admin\Users::delete');
    $routes->get('medical-entities', 'Admin\MedicalEntities::index');
});
