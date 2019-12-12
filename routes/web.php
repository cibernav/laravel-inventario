<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

\Debugbar::disable();

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'Admin\HomeController@index')->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::resource('user', 'UserController')->except('show');
    Route::post('user/active/{id}', 'UserController@active')->name('user.active');
});

Route::middleware(['auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::resource('category', 'CategoryController')->except('show');
});

Route::middleware(['auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::resource('product', 'ProductController')->except('show');
    Route::get('product/list', 'ProductController@listar')->name('product.list');
    Route::get('product/codigo/{id}', 'ProductController@getCodigo')->name('product.codigo');
});

Route::middleware(['auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::resource('client', 'ClientController')->except('show');
    Route::get('client/list', 'ClientController@listar')->name('client.list');
});


Route::middleware(['auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::resource('venta', 'VentaController')->except('show');
    Route::get('venta/numero/{id}', 'VentaController@numerodocumento')->name('venta.numero');
    Route::get('venta/listproduct', 'VentaController@listarProduct')->name('venta.listproduct');
    Route::get('venta/searchproduct', 'VentaController@searchProduct')->name('venta.searchproduct');
    Route::get('venta/items/{id}', 'VentaController@itemDetail')->name('venta.items');
    Route::get('venta/printer/{id}', 'VentaController@printer')->name('venta.printer');
    Route::get('venta/listarbandeja', 'VentaController@listarBandeja')->name('venta.listarbandeja');
});

Route::middleware(['auth'])->prefix('admin')->namespace('Admin')->name('admin.')->group(function(){
    Route::get('reporte/', 'ReporteController@index')->name('reporte.ventas');
    Route::get('reporte/chartventa', 'ReporteController@getChartVenta')->name('reporte.chartventa');
    Route::get('reporte/chartproducto', 'ReporteController@getChartProducto')->name('reporte.chartproducto');
    Route::get('reporte/downloadexcel', 'ReporteController@getDownloadExcel')->name('reporte.downloadexcel');
    Route::get('reporte/downloadcsv', 'ReporteController@getDownloadCsv')->name('reporte.downloadcsv');
});
