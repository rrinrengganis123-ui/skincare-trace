<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('MedisController');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Auth
$routes->get('/',                        'MedisController::login');
$routes->get('login',                    'MedisController::login');
$routes->post('login',                   'MedisController::doLogin');
$routes->get('logout',                   'MedisController::logout');

// Dashboard
$routes->get('dashboard','MedisController::dashboard');

// Rekam Medis CRUD
$routes->get('rekam-medis','MedisController::rekamMedis');
$routes->get('rekam-medis/create','MedisController::rekamMedisCreate');
$routes->post('rekam-medis/store','MedisController::rekamMedisStore');
$routes->get('rekam-medis/edit/(:num)',  'MedisController::rekamMedisEdit/$1');
$routes->post('rekam-medis/update/(:num)','MedisController::rekamMedisUpdate/$1');
$routes->get('rekam-medis/delete/(:num)','MedisController::rekamMedisDelete/$1');


// Alias pasien → rekam medis
$routes->get('pasien', 'MedisController::rekamMedis');
$routes->get('pasien/create', 'MedisController::rekamMedisCreate');
$routes->post('pasien/store', 'MedisController::rekamMedisStore');
$routes->get('pasien/edit/(:num)', 'MedisController::rekamMedisEdit/$1');
$routes->post('pasien/update/(:num)', 'MedisController::rekamMedisUpdate/$1');
$routes->get('pasien/delete/(:num)', 'MedisController::rekamMedisDelete/$1');
$routes->get('pasien/detail/(:num)', 'MedisController::detail/$1');

$routes->get('kunjungan', 'MedisController::kunjungan');
$routes->get('kunjungan/create', 'MedisController::rekamMedisCreate');
$routes->post('kunjungan/store', 'MedisController::rekamMedisStore');
$routes->get('kunjungan/detail/(:num)', 'MedisController::detail/$1');

// Verify Data
$routes->get('verify','MedisController::verify');