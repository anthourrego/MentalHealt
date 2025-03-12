<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\Administrator\Users;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);

$routes->group('auth', ['filter' => 'ajax'], function($routes) {
  $routes->post('login', [Home::class, 'attemptLogin']);
  $routes->post('register', [Home::class, 'attemptRegister']);
  $routes->post('logout', [Home::class, 'logout']);
  /* $routes->post('password/forgot', 'Home::processForgotPassword');
  $routes->post('password/reset', 'Home::processResetPassword'); */
});

$routes->group('admin', ['filter' => 'profile:admin'], function($routes) {
  $routes->get('/', 'Home::administrator');

  //Module Users
  $routes->group('Users', static function ($routes) {
    $routes->get('/', [Users::class, 'index']);
    $routes->get('Photo', [Users::class, 'foto']);
	  $routes->get('Photo/(:any)', [[Users::class, 'foto'], "$1"]);
    
    //Ajax Request
    $routes->group('', ['filter' => 'ajax'], function($routes) {
      $routes->post('validEmail', [Users::class, 'validEmail']);
      $routes->post('DT', [Users::class, 'listaDT']);
    });
  });
});

$routes->group('therapist', ['filter' => 'profile:therapist'], function($routes) {
  $routes->get('/', [Home::class, 'patient']);
});

$routes->group('patient', ['filter' => 'profile:patient'], function($routes) {
  $routes->get('/', [Home::class, 'therapist']);
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