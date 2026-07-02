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

//administradores
$routes->group('admin', ['filter' => 'auth:Administrador'], function($routes) {

    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/create', 'Admin\Users::create');
    $routes->post('users/update', 'Admin\Users::update');
    $routes->post('users/delete', 'Admin\Users::delete');
    $routes->post('users/changePassword', 'Admin\Users::changePassword');
    $routes->get('medical-entities', 'Admin\MedicalEntities::index');
    $routes->get('medical-entities/create', 'Admin\MedicalEntities::create');
    $routes->post('medical-entities/create', 'Admin\MedicalEntities::create');
    $routes->post('medical-entities/delete', 'Admin\MedicalEntities::delete');
    $routes->post('medical-entities/update', 'Admin\MedicalEntities::update');
    //tipo de diasnostico
    $routes->get('medical-diagnosis', 'Admin\MedicalDiagnosis::index');
    $routes->post('medical-diagnosis/create', 'Admin\MedicalDiagnosis::create');
    $routes->post('medical-diagnosis/delete', 'Admin\MedicalDiagnosis::delete');
    $routes->post('medical-diagnosis/update', 'Admin\MedicalDiagnosis::update');
    //tipo de tarea
    $routes->get('taskTypes', 'Admin\TaskType::index');
    $routes->get('taskTypes/create', 'Admin\TaskType::create');
    $routes->post('taskTypes/create', 'Admin\TaskType::create');
    $routes->post('taskTypes/delete', 'Admin\TaskType::delete');
    $routes->post('taskTypes/update', 'Admin\TaskType::update');

});
//medicos
$routes->group('medical_staff', ['filter' => 'auth:Personal de salud'], function($routes) {
    //plan de cuidado
    $routes->get('care-plan', 'Doctor\CarePlan::index');
    $routes->get('care-plan/create/(:num)', 'Doctor\CarePlan::create/$1');
    $routes->post('care-plan/create/(:num)', 'Doctor\CarePlan::create/$1');
    $routes->get('care-plan/edit/(:num)', 'Doctor\CarePlan::edit/$1');
    $routes->post('care-plan/update', 'Doctor\CarePlan::update');
    $routes->post('care-plan/delete', 'Doctor\CarePlan::delete');
    //diagnostico
    $routes->get('diagnosis/', 'Doctor\Diagnosis::index');
    $routes->get('diagnosis/create', 'Doctor\Diagnosis::create');
    $routes->post('diagnosis/create', 'Doctor\Diagnosis::create');
    $routes->post('diagnosis/delete', 'Doctor\Diagnosis::delete');
    $routes->post('diagnosis/update', 'Doctor\Diagnosis::update');
    //tareas de plan de cuidado
    $routes->get('care-plan-task', 'Doctor\CarePlanTask::index');
    $routes->get('care-plan-task/create', 'Doctor\CarePlanTask::create');
    $routes->post('care-plan-task/create', 'Doctor\CarePlanTask::create');
    $routes->post('care-plan-task/delete', 'Doctor\CarePlanTask::delete');
    $routes->post('care-plan-task/update', 'Doctor\CarePlanTask::update');
    //validacion de cumplimientos
    $routes->get('validacion', 'Doctor\Validacion::index');
    $routes->post('validacion/validar', 'Doctor\Validacion::validar');
    //estadisticas
    $routes->get('care-plan-task', 'Doctor\CarePlanTask::index');
    //medical_staff/statistics
    $routes->get('statistics', 'Doctor\Statistics::index');

});
//compartido administradores y medicos
$routes->group('staff', ['filter' => 'auth:Personal de salud,Administrador'], function($routes) {

    //PLACEHOLDER

});

$routes->group('paciente', ['filter' => 'auth:Paciente'], function($routes) {
    $routes->get('cumplimiento', 'Paciente\Cumplimiento::index');
    $routes->post('cumplimiento/store', 'Paciente\Cumplimiento::store');
});

$routes->group('profile', ['filter' => 'auth:Paciente,Personal de salud,Administrador'], function($routes) {
    $routes->post('update', 'Profile::update');
});
