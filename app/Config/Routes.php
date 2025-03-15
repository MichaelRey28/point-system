<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page (Login & Register Modals inside)
$routes->get('/', 'Home::index');
$routes->post('register', 'AuthController::register');
$routes->post('/login', 'AuthController::login');
$routes->get('/logout', 'AuthController::logout');

// Admin & User Dashboard (Role-Based Access)
$routes->get('/admin', 'AdminController::index', ['filter' => 'auth:admin']);
$routes->get('/user', 'UserController::index', ['filter' => 'auth:user']);

// Cluster Management
$routes->post('admin/create-cluster', 'AdminController::createCluster', ['filter' => 'auth:admin']);
$routes->post('admin/update-cluster/(:num)', 'AdminController::updateCluster/$1', ['filter' => 'auth:admin']);
$routes->post('admin/delete-cluster/(:num)', 'AdminController::deleteCluster/$1', ['filter' => 'auth:admin']); // Changed to POST

// Event Management
$routes->post('admin/create-event', 'AdminController::createEvent', ['filter' => 'auth:admin']);
$routes->post('admin/update-event/(:num)', 'AdminController::updateEvent/$1', ['filter' => 'auth:admin']);
$routes->post('admin/delete-event/(:num)', 'AdminController::deleteEvent/$1', ['filter' => 'auth:admin']); // Changed to POST

// Participant Management
$routes->post('admin/create-participant', 'AdminController::createParticipant', ['filter' => 'auth:admin']);
$routes->post('admin/update-participant/(:num)', 'AdminController::updateParticipant/$1', ['filter' => 'auth:admin']);
$routes->post('admin/delete-participant/(:num)', 'AdminController::deleteParticipant/$1', ['filter' => 'auth:admin']); // Changed to POST
$routes->get('admin/get-participants/(:num)', 'AdminController::getParticipantsByCluster/$1');

// Event Results
$routes->post('admin/create-event-result', 'AdminController::createEventResult', ['filter' => 'auth:admin']);
$routes->post('admin/update-event-result/(:num)', 'AdminController::updateEventResult/$1', ['filter' => 'auth:admin']);
$routes->post('admin/delete-event-result/(:num)', 'AdminController::deleteEventResult/$1', ['filter' => 'auth:admin']); // Changed to POST
$routes->get('rankings', 'EventResultController::rankings');

// Audit Trails
$routes->get('admin/audit-trails', 'AdminController::auditTrails', ['filter' => 'auth:admin']);

// Archive & Soft Delete System
$routes->get('admin/archive', 'AdminController::archive', ['filter' => 'auth:admin']);
$routes->post('admin/restore-participant/(:num)', 'AdminController::restoreParticipant/$1', ['filter' => 'auth:admin']); // Changed to POST
$routes->post('admin/restore-event/(:num)', 'AdminController::restoreEvent/$1', ['filter' => 'auth:admin']); // Changed to POST
$routes->post('admin/permanent-delete-participant/(:num)', 'AdminController::permanentDeleteParticipant/$1', ['filter' => 'auth:admin']); // Changed to POST
$routes->post('admin/permanent-delete-event/(:num)', 'AdminController::permanentDeleteEvent/$1', ['filter' => 'auth:admin']); // Changed to POST