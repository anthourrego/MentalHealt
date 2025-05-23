<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\Libraries;
use App\Controllers\Administrator\Users;
use App\Controllers\Appointment;
use App\Controllers\Patient;
use App\Controllers\Therapist;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [Home::class, 'index']);
$routes->get('register', [Home::class, 'register']);
$routes->get('confirmEmail', [Home::class, 'confirmEmail']);

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
  $routes->get('/', [Therapist::class, 'index']);

  $routes->group('appointments', function($routes) {
    $routes->get('getEvents', [Therapist::class, 'getEvents']);
    $routes->put('cancel/(:num)', [[Appointment::class, 'changeStatus'], "$1"]);
    $routes->put('noPresented/(:num)', [[Appointment::class, 'changeStatus'], "$1"]);
    $routes->put('Update/(:num)', [[Appointment::class, 'updateAppointment'], "$1"]);
    $routes->get('getAppointments', [Therapist::class, 'getEvents']);
  });

  $routes->get('diary/(:num)', [[Patient::class, 'index'], "1/$1"]);
  $routes->get('diary/getEntries', [Patient::class, 'getEntries']);
});

$routes->group('patient', ['filter' => 'profile:patient'], function($routes) {
  $routes->get('/', [Appointment::class, 'index']);

  $routes->group('diary', function($routes) {
    $routes->get('/', [Patient::class, 'index']);

    $routes->group('', ['filter' => 'ajax'], function($routes) {
      $routes->post('create', [Patient::class,'saveDiary']);
      $routes->get('getEntries', [Patient::class,'getEntries']);
      $routes->delete('delete/(:num)', [[Patient::class, 'deleteEntry'], "$1"]);
      $routes->put('update/(:num)', [[Patient::class, 'updateEntry'], "$1"]);
    });
  });

  $routes->group('appointments', function($routes) {
    $routes->get('/', [Appointment::class, 'index']);
    $routes->get('getEvents', [Appointment::class, 'getEvents']);
    $routes->get('getAvailableTherapists', [Appointment::class, 'getAvailableTherapists']);
    $routes->get('getAppointments', [Appointment::class, 'getAppointments']);
    $routes->post('Create', [Appointment::class, 'createAppointment']);
    $routes->put('cancel/(:num)', [[Appointment::class, 'changeStatus'], "$1"]);
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