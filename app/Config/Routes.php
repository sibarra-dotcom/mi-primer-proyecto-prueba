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
 
// Home controller
$routes->GET('/', 'Home::index');
$routes->GET('/session_check', 'Home::session_check');

// Admin controller
$routes->GET('/admin/dashboard', 'Admin::dashboard', ['filter' => 'authGuard']);
$routes->GET('/admin/usuarios', 'Admin::usuarios', ['filter' => 'authGuard']);
$routes->GET('/admin/user_create', 'Admin::user_create', ['filter' => 'authGuard']);
$routes->GET('/admin/user_edit/(:num)', 'Admin::user_edit/$1', ['filter' => 'authGuard']);
$routes->GET('/admin/user_delete/(:num)', 'Admin::user_delete/$1', ['filter' => 'authGuard']);
$routes->POST('/admin/user_create', 'Admin::user_create_post', ['filter' => 'authGuard']);
$routes->POST('/admin/user_update/(:num)', 'Admin::user_update/$1', ['filter' => 'authGuard']);

// Api controller
$routes->GET('productos/(:segment)/(:num)', 'Api::productos/$1/$2', ['filter' => 'authGuard']);
$routes->GET('search_productos/(:segment)', 'Api::search_productos/$1', ['filter' => 'authGuard']);

$routes->delete('delete_proveedor/(:num)', 'Api::delete_proveedor/$1', ['filter' => 'authGuard']);
$routes->GET('/get_firmas_insp/(:num)', 'Api::get_firmas_insp/$1', ['filter' => 'authGuard']);
$routes->GET('/get_files_insp/(:num)', 'Api::get_files_insp/$1', ['filter' => 'authGuard']);


$routes->GET('/get_proveedor', 'Api::get_proveedor', ['filter' => 'authGuard']);
$routes->GET('/get_proveedor_contacto', 'Api::get_proveedor_contacto', ['filter' => 'authGuard']);
$routes->GET('/get_contactos', 'Api::get_contactos', ['filter' => 'authGuard']);
$routes->GET('/get_articulo', 'Api::get_articulo', ['filter' => 'authGuard']);
$routes->GET('/get_maquina', 'Api::get_maquina', ['filter' => 'authGuard']);
$routes->GET('/get_adjuntos', 'Api::get_adjuntos', ['filter' => 'authGuard']);
$routes->GET('/get_art_ficha/(:num)', 'Api::get_art_ficha/$1', ['filter' => 'authGuard']);
$routes->GET('/get_comentarios', 'Api::get_comentarios', ['filter' => 'authGuard']);
$routes->GET('/get_articulo_coti', 'Api::get_articulo_coti', ['filter' => 'authGuard']);

$routes->GET('/get_condiciones/(:num)', 'Api::get_condiciones/$1', ['filter' => 'authGuard']);


$routes->GET('get_aprobacion/(:segment)/(:segment)', 'Api::get_aprobacion/$1/$2', ['filter' => 'authGuard']);


$routes->GET('all_proveedores', 'Api::all_proveedores', ['filter' => 'authGuard']);

$routes->GET('all_articles', 'Api::all_articles', ['filter' => 'authGuard']);
$routes->delete('delete_article/(:num)', 'Api::delete_article/$1', ['filter' => 'authGuard']);

$routes->POST('/search_articulos', 'Api::search_articulos', ['filter' => 'authGuard']);

$routes->POST('/search_proveedor', 'Api::search_proveedor', ['filter' => 'authGuard']);


$routes->POST('/maq_search', 'Api::maq_search', ['filter' => 'authGuard']);
$routes->POST('/add_comment', 'Api::add_comment', ['filter' => 'authGuard']);
$routes->POST('/add_aprobacion', 'Api::add_aprobacion', ['filter' => 'authGuard']);


$routes->POST('/edit_art', 'Api::edit_art', ['filter' => 'authGuard']);


$routes->GET('/get_lineas/(:segment)', 'Api::get_lineas/$1', ['filter' => 'authGuard']);
$routes->GET('/get_lineas_alt', 'Api::get_lineas_alt', ['filter' => 'authGuard']);

$routes->GET('/get_empleados/(:segment)', 'Api::get_empleados/$1', ['filter' => 'authGuard']);

$routes->GET('/get_maq_linea/(:segment)/(:segment)', 'Api::get_maq_linea/$1/$2', ['filter' => 'authGuard']);



$routes->POST('/search_ticket', 'Api::search_ticket', ['filter' => 'authGuard']);
$routes->GET('/all_tickets', 'Api::all_tickets', ['filter' => 'authGuard']);
$routes->GET('/all_tickets_pendientes', 'Api::all_tickets_pendientes', ['filter' => 'authGuard']);
$routes->GET('/get_user_tickets', 'Api::get_user_tickets', ['filter' => 'authGuard']);

$routes->GET('/get_ticket/(:num)', 'Api::get_ticket/$1', ['filter' => 'authGuard']);


$routes->GET('/get_notif', 'Api::get_notif', ['filter' => 'authGuard']);
$routes->POST('/add_comment_mt', 'Api::add_comment_mt', ['filter' => 'authGuard']);
$routes->POST('/add_limpieza_mt', 'Api::add_limpieza_mt', ['filter' => 'authGuard']);

$routes->POST('/add_firma_mt', 'Api::add_firma_mt', ['filter' => 'authGuard']);
$routes->GET('/get_comentarios_mt/(:num)', 'Api::get_comentarios_mt/$1', ['filter' => 'authGuard']);
$routes->GET('/get_adjuntos_mt/(:num)', 'Api::get_adjuntos_mt/$1', ['filter' => 'authGuard']);
$routes->GET('/get_firmas_mt/(:num)', 'Api::get_firmas_mt/$1', ['filter' => 'authGuard']);

$routes->POST('/edit_mant', 'Api::edit_mant', ['filter' => 'authGuard']);


          // inspecciones
$routes->GET('all_inspecciones/(:num)', 'Api::all_inspecciones/$1', ['filter' => 'authGuard']);

$routes->POST('/add_firma_insp', 'Api::add_firma_insp', ['filter' => 'authGuard']);
$routes->GET('/delete_files_insp/(:num)', 'Api::delete_files_insp/$1', ['filter' => 'authGuard']);



$routes->POST('/add_firma_reporte', 'Api::add_firma_reporte', ['filter' => 'authGuard']);
$routes->POST('/add_firma_reporte_diario', 'Api::add_firma_reporte_diario', ['filter' => 'authGuard']);


$routes->GET('all_reportes/(:segment)', 'Api::all_reportes/$1', ['filter' => 'authGuard']);
$routes->GET('all_reportes_old', 'Api::all_reportes_old', ['filter' => 'authGuard']);

$routes->GET('all_reporte/(:segment)', 'Api::all_reporte/$1', ['filter' => 'authGuard']);



$routes->GET('all_maquinas', 'Api::all_maquinas', ['filter' => 'authGuard']);
$routes->GET('/get_maq_files/(:num)', 'Api::get_maq_files/$1', ['filter' => 'authGuard']);
$routes->GET('/get_maq/(:num)', 'Api::get_maq/$1', ['filter' => 'authGuard']);
$routes->POST('/search_maquina', 'Api::search_maquina', ['filter' => 'authGuard']);





// Apps controller
$routes->GET('/apps', 'Apps::index', ['filter' => 'authGuard']);

// Aprobaciones controller
$routes->match(['GET', 'POST'], 'aprobaciones', 'Aprobaciones::index', ['filter' => 'authGuard']);

// Auth controller - public route not use filter AuthGuard
$routes->group('auth', function($routes) {
	$routes->POST('signin', 'Auth::signin');
	$routes->GET('signout', 'Auth::signout');
	$routes->GET('forgot', 'Auth::forgot');
});

// Busqueda controller
$routes->group('busqueda', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Busqueda::index');

	$routes->POST('upload_ficha', 'Busqueda::upload_ficha');
});

// Comedor controller
// $routes->group('comedor', ['filter' => 'authGuard:rrhh'], function($routes) {
$routes->group('comedor', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Comedor::index');
	$routes->match(['GET', 'POST'], 'menu', 'Comedor::menu');

	$routes->GET('lista', 'Comedor::lista');
});

// ComedorOnline controller
$routes->group('comedor_online', function($routes) {
	$routes->match(['GET', 'POST'], '/', 'ComedorOnline::index');
	$routes->match(['GET', 'POST'], 'pedido', 'ComedorOnline::pedido');
});

// Cotizar controller
$routes->match(['GET', 'POST'], 'cotizar', 'Cotizar::index', ['filter' => 'authGuard']);



// Dashboard controller
$routes->GET('/dashboard', 'Dashboard::index', ['filter' => 'authGuard']);

// Desarrollo controller
$routes->GET('/desarrollo', 'Desarrollo::index', ['filter' => 'authGuard']);

// Export controller
$routes->group('export', ['filter' => 'authGuard'], function($routes) {
	$routes->GET('comedor_entregas', 'Export::comedor_entregas');
	$routes->GET('sorteo_entregas/(:segment)', 'Export::sorteo_entregas/$1');
});


// Files controller
$routes->group('files', ['filter' => 'authGuard'], function($routes) {
	$routes->GET('download', 'Files::download');
});

// Inspeccion Controller
$routes->group('inspeccion', ['filter' => 'authGuard'], function($routes) {
	$routes->GET('/', 'Inspeccion::index');
	$routes->GET('init/(:segment)', 'Inspeccion::init/$1');
	$routes->GET('lista/(:segment)', 'Inspeccion::lista/$1');
	$routes->GET('print/(:segment)/(:num)', 'Inspeccion::print/$1/$2');
	$routes->GET('update/(:segment)/(:num)', 'Inspeccion::update/$1/$2');
	$routes->GET('create/(:segment)', 'Inspeccion::create/$1');

	$routes->GET('mt_delete/(:num)/(:segment)', 'Inspeccion::mt_delete/$1/$2');

	$routes->match(['GET', 'POST'], 'materias-primas', 'Inspeccion::materias_primas');
	$routes->match(['GET', 'POST'], 'materias-primasu', 'Inspeccion::materias_primasu');

	$routes->match(['GET', 'POST'], 'materiales', 'Inspeccion::materiales');
});

// Logs controller
$routes->group('logs', function($routes) {
	$routes->GET('last/(:num)/(:segment)', 'Logs::last/$1/$2');
});

// Mantenimiento controller
$routes->match(['GET', 'POST'], 'mantenimiento', 'Mantenimiento::index', ['filter' => 'authGuard']);

// Maquinaria controller
$routes->GET('maquinaria/delete/(:num)/(:segment)', 'Maquinaria::delete/$1/$2', ['filter' => 'authGuard']);
$routes->GET('maquinaria', 'Maquinaria::index', ['filter' => 'authGuard']);


// Mtickets controller
$routes->group('mtickets', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Mtickets::index');
	$routes->GET('get_by_daterange/(:segment)/(:segment)', 'Mtickets::get_by_daterange/$1/$2');
});

// OpenIA controller
$routes->group('openia', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'OpenIA::index');
	$routes->match(['GET', 'POST'], 'read_pdf', 'OpenIA::read_pdf');
});

// OrdenFab Controller
$routes->group('ordenfab', ['filter' => 'authGuard'], function($routes) {
	$routes->GET('/', 'OrdenFab::index');

	$routes->GET('get_resumen/(:num)', 'OrdenFab::get_resumen/$1');
	$routes->GET('get_last', 'OrdenFab::get_last');
	$routes->match(['GET', 'POST'], 'dashboard', 'OrdenFab::dashboard');
});

// Liberaciones Controller
$routes->group('liberaciones', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], 'create/(:segment)', 'Liberaciones::create/$1');

	$routes->GET('/all_personal', 'Liberaciones::all_personal');
	$routes->GET('/all_procesos', 'Liberaciones::all_procesos');
	$routes->GET('/all_ordenes', 'Liberaciones::all_ordenes');

	$routes->GET('/ordenes_liberacion', 'Liberaciones::ordenes_liberacion');

});

// Informe Resultados Controller
$routes->group('informeres', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], 'create/(:segment)', 'Informeres::create/$1');

	$routes->GET('/all_personal', 'Informeres::all_personal');
	$routes->GET('/all_procesos', 'Informeres::all_procesos');
	$routes->GET('/all_ordenes', 'Informeres::all_ordenes');

	$routes->GET('/ordenes_informe', 'Informeres::ordenes_informe');

});

// Produccion Controller
$routes->group('produccion', ['filter' => 'authGuard'], function($routes) {
	$routes->GET('/', 'Produccion::index');
	$routes->GET('/all_personal', 'Produccion::all_personal');
	$routes->GET('/all_procesos', 'Produccion::all_procesos');
	$routes->GET('/all_ordenes', 'Produccion::all_ordenes');

	$routes->GET('/ordenes_lista', 'Produccion::ordenes_lista');

	
	$routes->match(['GET', 'POST'], 'registro_diario', 'Produccion::registro_diario');
	$routes->match(['GET', 'POST'], 'registro_diariop', 'Produccion::registro_diariop');
	$routes->match(['GET', 'POST'], 'registro_update', 'Produccion::registro_update');

	$routes->match(['GET', 'POST'], 'grafico', 'Produccion::grafico');
	$routes->match(['GET', 'POST'], 'personal', 'Produccion::personal');
	$routes->match(['GET', 'POST'], 'procesos', 'Produccion::procesos');
	$routes->match(['GET', 'POST'], 'productos', 'Produccion::productos');


	$routes->match(['GET', 'POST'], 'lista_paros', 'Produccion::lista_paros');
	$routes->match(['GET', 'POST'], 'reporte_merma', 'Produccion::reporte_merma');
});

// Profile controller
$routes->group('profile', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Profile::index');
	$routes->match(['GET', 'POST'], 'change_password', 'Profile::change_password');
	$routes->match(['GET', 'POST'], 'signature', 'Profile::signature');
});

// Proveedores controller
$routes->group('proveedores', ['filter' => 'authGuard'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Proveedores::index');
});

// Registros controller
$routes->group('registros', ['filter' => 'authGuard'], function($routes) {
	$routes->GET('/', 'Registros::index');

	$routes->match(['GET', 'POST'], 'incidencias', 'Registros::incidencias');
	$routes->match(['GET', 'POST'], 'grafico', 'Registros::grafico');
});

// Sorteo controller - access only role marketing
$routes->group('sorteo', ['filter' => 'authGuard:marketing'], function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Sorteo::index');
	$routes->match(['GET', 'POST'], '/ruleta', 'Sorteo::ruleta');
	$routes->match(['GET', 'POST'], '/inventario', 'Sorteo::inventario');

	$routes->GET('lista', 'Sorteo::lista');
	$routes->GET('lista_entregado', 'Sorteo::lista_entregado');
	$routes->GET('all_inventario', 'Sorteo::all_inventario');
	$routes->GET('lista_inventario', 'Sorteo::lista_inventario');
	$routes->GET('premio/(:segment)', 'Sorteo::premio/$1');

	$routes->POST('activar_producto', 'Sorteo::activar_producto');
});

// Test Controller
// $routes->group('test', ['filter' => 'authGuard:admin'], function($routes) {
$routes->group('test', function($routes) {
	$routes->match(['GET', 'POST'], '/', 'Test::index');
	$routes->match(['GET', 'POST'], 'config_php', 'Test::config_php');

});

// Upload Controller
$routes->POST('upload/inspeccion', 'Upload::inspeccion', ['filter' => 'authGuard']);

// Salud Ocupacional - Expediente Médico
$routes->GET('/salud', 'Salud::index', ['filter' => 'authGuard:salud_ocupacional,rrhh']);

// Vacantes controller - autenticado
$routes->GET('/vacantes', 'Vacantes::index', ['filter' => 'authGuard']);
// Vacantes - portal público (sin auth)
$routes->GET('/vacantes/portal', 'Vacantes::portal');
$routes->GET('/vacantes/mipostulacion', 'Vacantes::mipostulacion');


// (:segment) is a wildcard for one URL parameter. You can use (:any) if you want to allow any type of string.

// API ResourceController automatically provides index(), show(), create(), update(), and delete().
// RESTful Routes: If you're building a REST API
// $routes->resource('api'); 
