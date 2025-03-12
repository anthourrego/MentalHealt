<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('auth', ['filter' => 'ajax'], function($routes) {
  $routes->post('login', 'Home::attemptLogin');
  $routes->post('register', 'Home::attemptRegister');
  $routes->post('logout', 'Home::logout');
  /* $routes->post('password/forgot', 'Home::processForgotPassword');
  $routes->post('password/reset', 'Home::processResetPassword'); */
});

$routes->group('admin', ['filter' => 'profile:admin'], function($routes) {
  $routes->get('/', 'Home::administrator');
  $routes->get('Users', 'Administrator\Users::index');
});

$routes->group('therapist', ['filter' => 'profile:therapist'], function($routes) {
  $routes->get('/', 'Home::patient');
});

$routes->group('patient', ['filter' => 'profile:patient'], function($routes) {
  $routes->get('/', 'Home::therapist');
});

$routes->get('Library/(:segment)/(:segment)/(:any)', function($package, $lib, $file) {
  $path = ROOTPATH . "vendor/{$package}/{$lib}/{$file}";
  if (file_exists($path)) {
      $type = pathinfo($path, PATHINFO_EXTENSION) === 'js' ? 'application/javascript' : 'text/css';
      header("Content-Type: {$type}");
      readfile($path);
      exit;
  }
});