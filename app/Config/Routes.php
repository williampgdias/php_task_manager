<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'TaskController::view');
$routes->group('api', function ($routes) {
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');

    $routes->group('', ['filter' => 'auth'], function ($routes) {

        $routes->get('tasks', 'TaskController::index');
        $routes->get('tasks/(:num)', 'TaskController::show/$1');
        $routes->post('tasks', 'TaskController::create');
        $routes->put('tasks/(:num)', 'TaskController::update/$1');
        $routes->delete('tasks/(:num)', 'TaskController::delete/$1');
    });
});