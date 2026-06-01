<?php

namespace Config;

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->setAutoRoute(false);
$routes->setDefaultNamespace('App\Controllers'); 

$routes->get('/', 'Home::index');

$routes->get('traceability/login',       'TraceabilityController::login');
$routes->post('traceability/login',      'TraceabilityController::loginPost');
$routes->get('traceability/logout',      'TraceabilityController::logout');

$routes->get('traceability/supplier',              'TraceabilityController::supplier');
$routes->post('traceability/supplier/store',       'TraceabilityController::supplierStore');
$routes->post('traceability/supplier/delete/(:num)', 'TraceabilityController::supplierDelete/$1');
$routes->delete('traceability/supplier/delete/(:num)', 'TraceabilityController::supplierDelete/$1');
$routes->get('traceability/supplier/edit/(:num)',  'TraceabilityController::supplierEdit/$1');
$routes->post('traceability/supplier/update/(:num)', 'TraceabilityController::supplierUpdate/$1');

$routes->get('traceability/manufacturer',                'TraceabilityController::manufacturer');
$routes->post('traceability/manufacturer/store',         'TraceabilityController::manufacturerStore');
$routes->post('traceability/manufacturer/delete/(:num)', 'TraceabilityController::manufacturerDelete/$1');
$routes->delete('traceability/manufacturer/delete/(:num)', 'TraceabilityController::manufacturerDelete/$1');
$routes->get('traceability/manufacturer/edit/(:num)',    'TraceabilityController::manufacturerEdit/$1');
$routes->post('traceability/manufacturer/update/(:num)', 'TraceabilityController::manufacturerUpdate/$1');

$routes->get('traceability/distributor',                'TraceabilityController::distributor');
$routes->post('traceability/distributor/store',         'TraceabilityController::distributorStore');
$routes->post('traceability/distributor/delete/(:num)', 'TraceabilityController::distributorDelete/$1');
$routes->get('traceability/distributor/qr/(:any)',      'TraceabilityController::generateQR/$1');
$routes->delete('traceability/distributor/delete/(:num)', 'TraceabilityController::distributorDelete/$1');
$routes->get('traceability/distributor/edit/(:num)',    'TraceabilityController::distributorEdit/$1');
$routes->post('traceability/distributor/update/(:num)', 'TraceabilityController::distributorUpdate/$1');

$routes->get('traceability/track/(:any)',  'TraceabilityController::track/$1');
$routes->get('traceability/track-search', 'TraceabilityController::trackByResi');

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}