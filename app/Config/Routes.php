<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true); // se puede acceder a todas las funciones public en Controllers
 

$routes->get('/', 'Home::index');
$routes->get('/files/download', 'Files::download', ['filter' => 'authGuard']);

$routes->get('/admin/dashboard', 'Admin::dashboard', ['filter' => 'authGuard']);
$routes->get('/admin/usuarios', 'Admin::usuarios', ['filter' => 'authGuard']);
$routes->get('/admin/user_create', 'Admin::user_create', ['filter' => 'authGuard']);
$routes->get('/admin/user_edit/(:num)', 'Admin::user_edit/$1', ['filter' => 'authGuard']);
$routes->get('/admin/user_delete/(:num)', 'Admin::user_delete/$1', ['filter' => 'authGuard']);


$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'authGuard']);
$routes->get('/apps', 'Apps::index', ['filter' => 'authGuard']);

$routes->match(['get', 'post'], 'cotizar', 'Cotizar::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'busqueda', 'Busqueda::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'mantenimiento', 'Mantenimiento::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'aprobaciones', 'Aprobaciones::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], 'proveedores', 'Proveedores::index', ['filter' => 'authGuard']);

$routes->match(['get', 'post'], 'mtickets', 'Mtickets::index', ['filter' => 'authGuard']);

// $routes->get('/user/maquinaria', 'User::maquinaria', ['filter' => 'authGuard']);
// $routes->post('/user/maquinaria', 'User::maquinaria_post', ['filter' => 'authGuard']);



$routes->get('/user/mantenimiento', 'User::mantenimiento', ['filter' => 'authGuard']);

$routes->get('/user/mtickets', 'User::mtickets', ['filter' => 'authGuard']);


// Use (:segment) in routes
// (:segment) is a wildcard for one URL parameter. You can use (:any) if you want to allow any type of string.

// API ResourceController automatically provides index(), show(), create(), update(), and delete().
// $routes->resource('api');

$routes->get('/get_proveedor', 'Api::get_proveedor', ['filter' => 'authGuard']);
$routes->get('/get_proveedor_contacto', 'Api::get_proveedor_contacto', ['filter' => 'authGuard']);
$routes->get('/get_contactos', 'Api::get_contactos', ['filter' => 'authGuard']);
$routes->get('/get_articulo', 'Api::get_articulo', ['filter' => 'authGuard']);
$routes->get('/get_maquina', 'Api::get_maquina', ['filter' => 'authGuard']);
$routes->get('/get_adjuntos', 'Api::get_adjuntos', ['filter' => 'authGuard']);
$routes->get('/get_comentarios', 'Api::get_comentarios', ['filter' => 'authGuard']);
$routes->get('/get_articulo_coti', 'Api::get_articulo_coti', ['filter' => 'authGuard']);


$routes->get('get_aprobacion', 'Api::get_aprobacion', ['filter' => 'authGuard']);


$routes->get('all_proveedores', 'Api::all_proveedores', ['filter' => 'authGuard']);

$routes->get('all_articles', 'Api::all_articles', ['filter' => 'authGuard']);
$routes->delete('delete_article/(:num)', 'Api::delete_article/$1', ['filter' => 'authGuard']);


$routes->post('/search', 'Api::search', ['filter' => 'authGuard']);
$routes->post('/maq_search', 'Api::maq_search', ['filter' => 'authGuard']);
$routes->post('/add_comment', 'Api::add_comment', ['filter' => 'authGuard']);
$routes->post('/add_aprobacion', 'Api::add_aprobacion', ['filter' => 'authGuard']);


$routes->post('/edit_art', 'Api::edit_art', ['filter' => 'authGuard']);

// Mantenimiento


$routes->get('/get_lineas/(:segment)', 'Api::get_lineas/$1', ['filter' => 'authGuard']);

$routes->get('/get_empleados/(:segment)', 'Api::get_empleados/$1', ['filter' => 'authGuard']);

$routes->get('/get_maq_linea/(:segment)/(:segment)', 'Api::get_maq_linea/$1/$2');

$routes->post('/search_ticket', 'Api::search_ticket', ['filter' => 'authGuard']);
$routes->get('/all_tickets', 'Api::all_tickets', ['filter' => 'authGuard']);
$routes->get('/all_tickets_pendientes', 'Api::all_tickets_pendientes', ['filter' => 'authGuard']);
$routes->get('/get_user_tickets', 'Api::get_user_tickets', ['filter' => 'authGuard']);

$routes->get('/get_ticket/(:num)', 'Api::get_ticket/$1', ['filter' => 'authGuard']);


$routes->get('/get_notif', 'Api::get_notif', ['filter' => 'authGuard']);
$routes->post('/add_comment_mt', 'Api::add_comment_mt', ['filter' => 'authGuard']);
$routes->post('/add_firma_mt', 'Api::add_firma_mt', ['filter' => 'authGuard']);
$routes->get('/get_comentarios_mt/(:num)', 'Api::get_comentarios_mt/$1', ['filter' => 'authGuard']);
$routes->get('/get_adjuntos_mt/(:num)', 'Api::get_adjuntos_mt/$1', ['filter' => 'authGuard']);
$routes->get('/get_firmas_mt/(:num)', 'Api::get_firmas_mt/$1', ['filter' => 'authGuard']);

$routes->post('/edit_mant', 'Api::edit_mant', ['filter' => 'authGuard']);

// Profile
$routes->get('/profile', 'Profile::index', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], '/profile/change_password', 'Profile::change_password', ['filter' => 'authGuard']);
$routes->match(['get', 'post'], '/profile/signature', 'Profile::signature', ['filter' => 'authGuard']);

// Desarrollo
$routes->get('/desarrollo', 'Desarrollo::index', ['filter' => 'authGuard']);


// Test
$routes->match(['get', 'post'], '/test/openia', 'Test::openia');

// OpenIA
$routes->match(['get', 'post'], '/openia', 'OpenIA::index', ['filter' => 'authGuard']);

// Vacantes - autenticado
$routes->get('/vacantes', 'Vacantes::index', ['filter' => 'authGuard']);
// Vacantes - portal público (sin auth)
$routes->get('/vacantes/portal', 'Vacantes::portal');
$routes->get('/vacantes/mipostulacion', 'Vacantes::mipostulacion');








$routes->post('/', 'Auth::signin');

$routes->post('/admin/user_create', 'Admin::user_create_post', ['filter' => 'authGuard']);
$routes->post('/admin/user_update/(:num)', 'Admin::user_update/$1', ['filter' => 'authGuard']);



$routes->post('/user/mantenimiento', 'User::mantenimiento_post', ['filter' => 'authGuard']);



// RESTful Routes: If you're building a REST API, CodeIgniter's resource routing is preferred for CRUD operations. For example:

// $routes->resource('users'); 


// Example route with role-specific filter
// $routes->get('/admin', 'AdminController::index', ['filter' => 'authGuard:desarrollo,calidad']);




// $routes->get('/pdf', 'PdfGenerator::index');

// $routes->get('/', 'PdfController::index');
// $routes->match(['get', 'post'], 'PdfController/htmlToPDF', 'PdfController::htmlToPDF');


// $routes->get('/', 'SignupController::index');
// $routes->get('/signup', 'SignupController::index');
// $routes->match(['get', 'post'], 'SignupController/store', 'SignupController::store');
// $routes->match(['get', 'post'], 'SigninController/loginAuth', 'SigninController::loginAuth');

