<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\Libraries;
use App\Controllers\Administrator\Users;
use App\Controllers\Appointment;
use App\Controllers\Patient;

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
      $routes->delete('Delete/(:num)', [[Users::class, 'delete'], "$1"]);
      $routes->put('ChangeStatus/(:num)', [[Users::class, 'changeStatus'], "$1"]);
      $routes->post('Create', [Users::class, 'create']);
      $routes->put('Update/(:num)', [[Users::class, 'update'], "$1"]);
      $routes->put('ChangePass/(:num)', [[Users::class, 'changePassword'], "$1"]);
    });
  });
});

$routes->group('therapist', ['filter' => 'profile:therapist'], function($routes) {
  $routes->get('/', [Home::class, 'therapist']);
});

$routes->group('patient', ['filter' => 'profile:patient'], function($routes) {
  $routes->get('/', [Patient::class, 'index']);

  $routes->group('diary', ['filter' => 'ajax'], function($routes) {
    $routes->post('create', [Patient::class,'saveDiary']);
    $routes->get('getEntries', [Patient::class,'getEntries']);
    $routes->delete('delete/(:num)', [[Patient::class, 'deleteEntry'], "$1"]);
    $routes->put('update/(:num)', [[Patient::class, 'updateEntry'], "$1"]);
  });

  $routes->group('appointments', function($routes) {
    $routes->get('/', [Appointment::class, 'index']);
    $routes->get('getEvents', [Appointment::class, 'getEvents']);
    $routes->get('getAvailableTherapists', [Appointment::class, 'getAvailableTherapists']);
    $routes->get('getAvailableTimeSlots', [Appointment::class, 'getAvailableTimeSlots']);
    $routes->post('Create', [Appointment::class, 'createAppointment']);
    $routes->delete('cancel/(:num)', [[Appointment::class, 'delete'], "$1"]);
  });
});

$routes->get('Library/(:segment)/(:any)', [[Libraries::class, 'getLibrary'], "$1/$2"]);

/* $routes->get('Library/(:segment)/(:segment)/(:any)', function($package, $lib, $file) {
  $path = ROOTPATH . "vendor/{$package}/{$lib}/{$file}";
  if (file_exists($path)) {
      $type = pathinfo($path, PATHINFO_EXTENSION) === 'js' ? 'application/javascript' : 'text/css';
      header("Content-Type: {$type}");
      readfile($path);
      exit;
  }
}); */