<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/products', 'ProductController::index');
$routes->get('/property', 'PropertyController::index');

$routes->get('/product/create', 'ProductController::createView');
$routes->get('/product/edit', 'ProductController::createEditView');

$routes->get('/property/edit', 'PropertyController::createEditView');

$routes->get('/digital-products', 'DigitalProductController::index');
$routes->get('/digital-product/create', 'DigitalProductController::createView');
$routes->get('/digital-product/edit', 'DigitalProductController::createEditView');

$routes->get('/physical-products', 'PhysicalProductController::index');
$routes->get('/physical-product/create', 'PhysicalProductController::createView');
$routes->get('/physical-product/edit', 'PhysicalProductController::createEditView');

$routes->post('/api/products/create', 'ProductController::create');
$routes->put('/api/products/update', 'ProductController::edit');
$routes->post('/api/products/delete', 'ProductController::delete');

$routes->post('/api/digital-products/create', 'DigitalProductController::create');
$routes->put('/api/digital-products/update', 'DigitalProductController::edit');
$routes->post('/api/digital-products/delete', 'DigitalProductController::delete');

$routes->post('/api/physical-products/create', 'PhysicalProductController::create');
$routes->put('/api/physical-products/update', 'PhysicalProductController::edit');
$routes->post('/api/physical-products/delete', 'PhysicalProductController::delete');

$routes->post('/api/property/create', 'PropertyController::create');
$routes->put('/api/property/update', 'PropertyController::edit');
$routes->post('/api/property/delete', 'PropertyController::delete');

$routes->get('/db-error', function () {
  return view('db_error');
});