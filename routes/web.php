<?php

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
// MENU
Route::get('maestros', ['as'=>'maestros.index', 'uses'=>'HomeController@maestros']);
Route::get('documentos', ['as'=>'documentos.index', 'uses'=>'HomeController@documentos']);
Route::get('operaciones', ['as'=>'operaciones.index', 'uses'=>'HomeController@operaciones']);
Route::get('reportes', ['as'=>'reportes.index_menu', 'uses'=>'HomeController@reportes']);

//Route::get('productos', ['as' => 'productos.index', 'uses' => 'ProductosController@index']);
Route::resource('productos','ProductosController');
Route::post('eliminar_productos', ['as'=>'productos.eliminarVarios','uses'=>'ProductosController@eliminarVarios']);

Route::resource('categorias','CategoriasController');
Route::post('eliminar_categorias', ['as'=>'categorias.eliminarVarios','uses'=>'CategoriasController@eliminarVarios']);
// ALMACEN
Route::get('almacen', ['as'=>'almacen.index', 'uses'=>'AlmacenController@index']);
Route::get('almacen/create', ['as'=>'almacen.create', 'uses'=>'AlmacenController@create']);
Route::post('almacen', ['as'=>'almacen.store', 'uses'=>'AlmacenController@store']);
Route::get('almacen/{salida}', ['as'=>'almacen.show', 'uses'=>'AlmacenController@show']);
Route::get('almacen/{id}/edit', ['as'=>'almacen.edit','uses'=>'AlmacenController@edit']);
Route::put('almacen/{id}', ['as'=>'almacen.update','uses'=>'AlmacenController@update']);
Route::put('almacen/{salida}', ['as'=>'almacen.update', 'uses'=>'AlmacenController@update']);
Route::delete('almacen/{id}', ['as'=>'almacen.destroy','uses'=>'AlmacenController@destroy']);
Route::post('eliminar_almacen', ['as'=>'almacen.eliminarVarios','uses'=>'AlmacenController@eliminarVarios']);

// MENU ALMACEN
Route::get('menu_almacen', ['as'=>'menu_almacen.index', 'uses'=>'AlmacenController@menu_almacen']);

// CLIENTES y PROVEEDORES
Route::resource('ctas_corrientes','CtaCorrientesController');
Route::post('eliminar_ctas_corrientes', ['as'=>'ctas_corrientes.eliminarVarios','uses'=>'CtaCorrientesController@eliminarVarios']);

// Proyectos
Route::resource('proyectos','ProyectosController');
Route::post('eliminar_proyectos', ['as'=>'proyectos.eliminarVarios','uses'=>'ProyectosController@eliminarVarios']);

//Route::resource('salidas','SalidasController');
Route::get('salidas', ['as'=>'salidas.index', 'uses'=>'SalidasController@index']);
Route::post('salidas', ['as'=>'salidas.store', 'uses'=>'SalidasController@store']);
Route::get('salidas/create', ['as'=>'salidas.create', 'uses'=>'SalidasController@create']);
Route::get('salidas/{salida}', ['as'=>'salidas.show', 'uses'=>'SalidasController@show']);
Route::put('salidas/{salida}', ['as'=>'salidas.update', 'uses'=>'SalidasController@update']);
Route::get('salidas/{salida}/edit', ['as'=>'salidas.edit', 'uses'=>'SalidasController@edit']);
Route::post('eliminar_salidas', ['as'=>'salidas.eliminarVarios','uses'=>'SalidasController@eliminarVarios']);
Route::get('salidas/reporte/{id}', ['as'=>'salidas.comprobante', 'uses'=>'SalidasController@comprobante']);
Route::get('salidas/stock/{id}/{cant}', ['as'=>'salidas.stock', 'uses'=>'SalidasController@stock']);
Route::get('check_nrodoc_s', ['as'=>'salidas.check_nrodoc_s', 'uses'=>'SalidasController@check_nrodoc_s']);

// Tipo de cambio
Route::get('tc_add/{dia}/{tc_id}', ['as'=>'tc.tc_add','uses'=>'TC_Controller@tc_add']);
Route::post('tc_store', ['as'=>'tc.store','uses'=>'TC_Controller@store']);
Route::get('prov_add/{dia}/{tc_id}', ['as'=>'tc.prov_add','uses'=>'IngresosController@prov_add']);

// Add cliente
Route::get('clienteAdd/{tc_id}', ['as'=>'cli.clienteAdd','uses'=>'ContratoController@clienteAdd']);
Route::post('clienteStore', ['as'=>'cli.clienteStore','uses'=>'ContratoController@clienteStore']);

// Add laboratorio
Route::get('laboratorioAdd/{tc_id}', ['as'=>'cli.laboratorioAdd','uses'=>'ContratoController@laboratorioAdd']);
Route::post('laboratorioStore', ['as'=>'cli.laboratorioStore','uses'=>'ContratoController@laboratorioStore']);

Route::post('prov_store', ['as'=>'prov.store','uses'=>'IngresosController@storeProv']);
Route::get('proy_add/{dia}/{tc_id}', ['as'=>'tc.proy_add','uses'=>'SalidasController@proy_add']);
Route::post('proy_store', ['as'=>'proy.store','uses'=>'SalidasController@storeProy']);

Route::get('form_add/{tc_id}', ['as'=>'form_add.index','uses'=>'FormController@add_categoria']);
Route::post('form_store', ['as'=>'form.store','uses'=>'FormController@store_categoria']);

Route::get('tc', ['as'=>'tc.index', 'uses'=>'TC_Controller@index']);
Route::post('tc', ['as'=>'tc.store', 'uses'=>'TC_Controller@store']);
Route::get('tc/create', ['as'=>'tc.create', 'uses'=>'TC_Controller@create']);
Route::get('tc/{salida}', ['as'=>'tc.show', 'uses'=>'TC_Controller@show']);
Route::get('tc/{salida}/edit', ['as'=>'tc.edit', 'uses'=>'TC_Controller@edit']);
Route::put('tc/{salida}', ['as'=>'tc.update', 'uses'=>'TC_Controller@update']);
Route::post('eliminar_tc', ['as'=>'tc.eliminarVarios','uses'=>'TC_Controller@eliminarVarios']);

// Autocomplete
Route::get('autocomplete/findProduct', 'SalidasController@findProduct');
Route::get('autocomplete/findProvee', 'IngresosController@buscarProveedor');
Route::get('autocomplete/findClient', 'IngresosController@buscarCliente');
Route::get('autocomplete/findProyecto', 'IngresosController@buscarProyecto');
Route::get('autocomplete/findLaboratorio', 'IngresosController@findLaboratorio');

// INGRESOS
//Route::resource('ingresos','IngresosController');
Route::get('ingresos', ['as'=>'ingresos.index', 'uses'=>'IngresosController@index']);
Route::post('ingresos', ['as'=>'ingresos.store', 'uses'=>'IngresosController@store']);
Route::get('ingresos/create', ['as'=>'ingresos.create', 'uses'=>'IngresosController@create']);
Route::get('ingresos/{ingreso}', ['as'=>'ingresos.show', 'uses'=>'IngresosController@show']);
Route::put('ingresos/{ingreso}', ['as'=>'ingresos.update', 'uses'=>'IngresosController@update']);
Route::get('ingresos/{ingreso}/edit', ['as'=>'ingresos.edit', 'uses'=>'IngresosController@edit']);
Route::post('eliminar_ingresos', ['as'=>'ingresos.eliminarVarios','uses'=>'IngresosController@eliminarVarios']);

Route::post('ingresos/autocomplete', ['as'=>'ingresos.productos', 'uses'=>'IngresosController@autocomplete']);
Route::get('ingresos/reporte/{id}', ['as'=>'ingresos.comprobante', 'uses'=>'IngresosController@comprobante']);
Route::get('ingresos/stock/{id}/{cant}', ['as'=>'ingresos.stock', 'uses'=>'IngresosController@stock']);
Route::get('check_nrodoc', ['as'=>'ingresos.check_nrodoc', 'uses'=>'IngresosController@check_nrodoc']);

// GUIA DEVOLUCION
//Route::resource('ingresos','IngresosController');
Route::get('guia_devolucion', ['as'=>'guia_devolucion.index', 'uses'=>'GuiaDController@index']);
Route::post('guia_devolucion', ['as'=>'guia_devolucion.store', 'uses'=>'GuiaDController@store']);
Route::get('guia_devolucion/create', ['as'=>'guia_devolucion.create', 'uses'=>'GuiaDController@create']);
Route::get('guia_devolucion/{guia}', ['as'=>'guia_devolucion.show', 'uses'=>'GuiaDController@show']);
Route::put('guia_devolucion/{guia}', ['as'=>'guia_devolucion.update', 'uses'=>'GuiaDController@update']);
// CUANDO SE PASA POR AJAX
//Route::post('guia_devolucion/{guia}', ['as'=>'guia_devolucion.update', 'uses'=>'GuiaDController@update']);
Route::get('guia_devolucion/{guia}/edit', ['as'=>'guia_devolucion.edit', 'uses'=>'GuiaDController@edit']);
Route::post('eliminar_guia_devolucion', ['as'=>'guia_devolucion.eliminarVarios','uses'=>'GuiaDController@eliminarVarios']);

// verificar stock del prod devuelto
Route::get('guia_devolucion/stock/{id}/{cant}/{proy}', ['as'=>'guia_devolucion.stock', 'uses'=>'GuiaDController@stock']);

// TIPO CAMBIO
Route::resource('tcambios','TCambiosController');
Route::post('eliminar_ctas_corrientes', ['as'=>'ctas_corrientes.eliminarVarios','uses'=>'CtaCorrientesController@eliminarVarios']);

// GUIA DEVOLUCION
Route::get('gt', ['as'=>'gt.index', 'uses'=>'GuiaTransfController@index']);
Route::post('gt', ['as'=>'gt.store', 'uses'=>'GuiaTransfController@store']);
Route::get('gt/create', ['as'=>'gt.create', 'uses'=>'GuiaTransfController@create']);
Route::get('gt/{gt}', ['as'=>'gt.show', 'uses'=>'GuiaTransfController@show']);
Route::put('gt/{gt}', ['as'=>'gt.update', 'uses'=>'GuiaTransfController@update']);
Route::get('gt/{gt}/edit', ['as'=>'gt.edit', 'uses'=>'GuiaTransfController@edit']);
Route::post('eliminar_gt', ['as'=>'gt.eliminarVarios','uses'=>'GuiaTransfController@eliminarVarios']);

// REPORTES
Route::get('reportes/stock_sin_valorizar', ['as'=>'stock.stock_sin_valorizar', 'uses' => 'ReportesController@index']);
Route::get('reportes/stock_valorizado', ['as'=>'stock.stock_valorizado', 'uses' => 'ReportesController@index']);
Route::get('reportes/herr_pendientes', ['as'=>'pendientes.herramientas', 'uses' => 'ReportesController@herr_pendientes']);

Route::get('reportes/stock', ['as'=>'reportes.index', 'uses' => 'ReportesController@index']);
Route::get('reportes/prueba', ['as'=>'reportes.prueba', 'uses' => 'ReportesController@prueba']);


// ROLES
Route::resource('roles','RolesController');

Route::get('roles/create', ['as'=>'roles.create','uses'=>'RolesController@create'])->middleware('VerificarToken');
Route::post('roles', ['as'=>'roles.store','uses'=>'RolesController@store'])->middleware('VerificarToken');

Route::post('roles/storepermisos', ['as'=>'roles.storepermisos','uses'=>'RolesController@storepermisos'])->middleware('VerificarToken');
Route::post('roles/storepermisosall', ['as'=>'roles.storepermisosall','uses'=>'RolesController@storepermisosall'])->middleware('VerificarToken');

Route::get('roles/{id}', ['as'=>'roles.show','uses'=>'RolesController@show'])->middleware('VerificarToken');// ruta ver
Route::get('roles/{id}/edit', ['as'=>'roles.edit','uses'=>'RolesController@edit'])->middleware('VerificarToken');
Route::get('roles/{id}/permisos', ['as'=>'roles.permisos','uses'=>'RolesController@permisos'])->middleware('VerificarToken');
Route::put('roles/{id}', ['as'=>'roles.update','uses'=>'RolesController@update'])->middleware('VerificarToken');
Route::delete('roles/{id}', ['as'=>'roles.destroy','uses'=>'RolesController@destroy']);
Route::post('eliminar_roles', ['as'=>'roles.eliminarVarios','uses'=>'RolesController@eliminarVarios']);

// usuario
Route::resource('usuarios','UsuariosController');
Route::post('eliminar_usuarios', ['as'=>'usuarios.eliminarVarios','uses'=>'UsuariosController@eliminarVarios']);
Route::get('usuarios/{id}/roles', ['as'=>'usuarios.roles','uses'=>'UsuariosController@roles'])->middleware('VerificarToken');
Route::post('usuarios_storeroles', ['as'=>'usuarios.storeRoles','uses'=>'UsuariosController@storeRoles']);


// Procedimiento Almacenado
Route::get('sp_stock', ['as'=>'sp.stock', 'uses'=>'SPController@sp_stock']);
//Route::get('kardex_basic', ['as'=>'kardex.index', 'uses'=>'SPController@kardex_basic']);
Route::get('kardex_basic', ['as'=>'kardex.create', 'uses'=>'SPController@create']);
Route::post('kardex_basic', ['as'=>'kardex.store', 'uses'=>'SPController@store']);

/*Route::get('kardex_valorizado', ['as'=>'kardex_va.index', 'uses'=>'SPController@kardex_va']);*/
Route::get('kardex_valorizado', ['as'=>'kardex_va.create', 'uses'=>'SPController@create_va']);
Route::post('kardex_valorizado', ['as'=>'kardex_va.store', 'uses'=>'SPController@store_va']);

// SP calculo_costos
Route::get('calculo_costos', ['as'=>'calculo_costos.index', 'uses'=>'CcostoController@index']);
Route::get('calculo_costos/create', ['as'=>'calculo_costos.create', 'uses'=>'CcostoController@create']);
Route::post('calculo_costos', ['as'=>'calculo_costos.store', 'uses'=>'CcostoController@store']);

Route::get('kardex_pdf', ['as'=>'kardex_pdf.index', 'uses'=>'SPController@kardex_pdf']);
Route::get('kardex_excel', ['as'=>'kardex_excel.index', 'uses'=>'SPController@kardex_excel']);

// Stock
//Route::get('stock', ['as'=>'alm.kardex','uses'=>'KardexController@kardex']);


Route::get('whatsapp', "WhatsappController@index");
Route::post('whatsapp', ['as'=>'whatsapp.send','uses'=>'WhatsappController@send']);
//Route::post('whatsapp', "WhatsappController@send");

// Mod Contrato
Route::resource('contratos','ContratoController');
Route::post('eliminar_contratos', ['as'=>'contratos.eliminarVarios','uses'=>'ContratoController@eliminarVarios']);
Route::get('contratos/edit/{id}', ['as'=>'contratos.ed', 'uses'=>'ContratoController@editContrato']);

// Documentos Electronicos
Route::get('ventas', ['as'=>'ventas.index', 'uses'=>'VentasController@index']);
Route::post('ventas', ['as'=>'ventas.store', 'uses'=>'VentasController@store']);
Route::get('ventas/create', ['as'=>'ventas.create', 'uses'=>'VentasController@create']);
Route::get('ventas/{salida}', ['as'=>'ventas.show', 'uses'=>'VentasController@show']);
Route::put('ventas/{salida}', ['as'=>'ventas.update', 'uses'=>'VentasController@update']);
//Route::get('ventas/{salida}/edit', ['as'=>'ventas.edit', 'uses'=>'VentasController@edit']);
Route::get('ventas/{comprobante}/edit', ['as'=>'ventas.edit', 'uses'=>'VentasController@edit']);
Route::post('eliminar_ventas', ['as'=>'ventas.eliminarVarios','uses'=>'VentasController@eliminarVarios']);
Route::get('ventas/reporte/{id}', ['as'=>'ventas.comprobante', 'uses'=>'VentasController@comprobante']);
Route::get('ventas/stock/{id}/{cant}', ['as'=>'ventas.stock', 'uses'=>'VentasController@stock']);
Route::get('check_nrodoc_s', ['as'=>'ventas.check_nrodoc_s', 'uses'=>'VentasController@check_nrodoc_s']);
Route::get('ventas/{comprobante}/ticket', ['as'=>'ventas.ticket', 'uses'=>'VentasController@ticket']);

Route::get('ventas/{comprobante}/ticket', ['as'=>'ventas.ticket', 'uses'=>'VentasController@ticket']);
Route::get('ventas/{comprobante}/a4', ['as'=>'ventas.a4', 'uses'=>'VentasController@a4']);

// clear cache
/*Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});*/

Route::get('factura/impresion', ['as'=>'comprobante.pdf', 'uses'=>'FacturaController@impresion']);