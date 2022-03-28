<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/login');
});


Auth::routes(['register' => false]);

Route::get('/show-cartogram/{id}/{value}', [App\Http\Controllers\HomeController::class, 'cartogram']);
Route::get('/show-legend/{id}/{value}', [App\Http\Controllers\HomeController::class, 'legend']);

Route::get('/clients/{id}/cabinet', [App\Http\Controllers\ClientController::class, 'cabinet']);
    
Route::group(['middleware' => 'auth'], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    
    Route::get('/test', [App\Http\Controllers\TestController::class, 'index']);


    Route::get('/cartograms/{id}/generate', [App\Http\Controllers\CartogramController::class, 'generate'])->name('cartograms.generate');


    Route::resource('regions', App\Http\Controllers\RegionController::class);

    Route::get('/clients/{id}/self-selection', [App\Http\Controllers\ClientController::class, 'selfSelection']);
    Route::post('/clients/{id}/self-selection', [App\Http\Controllers\ClientController::class, 'storeSelfSelection']);
    Route::get('/clients/{id}/cartograms', [App\Http\Controllers\ClientController::class, 'cartograms']);
    Route::get('/clients/{id}/protocol', [App\Http\Controllers\ClientController::class, 'protocol']);
    Route::post('/clients/{id}/generate-protocol', [App\Http\Controllers\ProtocolController::class, 'generateForClient']);
    Route::resource('clients', App\Http\Controllers\ClientController::class);

    Route::post('/update_polygon', [App\Http\Controllers\FieldController::class, 'updatePolygon']);
    Route::get('/fields/editor', [App\Http\Controllers\FieldController::class, 'editor']);

    Route::get('/import_form', [App\Http\Controllers\FieldController::class, 'formImport'])->name('fields.import_form');
    Route::post('/fields/import', [App\Http\Controllers\FieldController::class, 'prepareImport']);
    Route::post('/fields/generate-import', [App\Http\Controllers\FieldController::class, 'import']);

    Route::get('/fields/{id}/kml', [App\Http\Controllers\FieldController::class, 'kml']);
    Route::get('/fields/{id}/map', [App\Http\Controllers\FieldController::class, 'map'])->name('fields.map');
    Route::resource('fields', App\Http\Controllers\FieldController::class);


    Route::resource('trips', App\Http\Controllers\TripController::class);


    Route::resource('kmls', App\Http\Controllers\KmlController::class);


    Route::post('/clear_polygon', [App\Http\Controllers\PolygonController::class, 'clearPolygon']);
    Route::resource('polygons', App\Http\Controllers\PolygonController::class);


    Route::resource('subpolygons', App\Http\Controllers\SubpolygonController::class);

    Route::post('/update_point', [App\Http\Controllers\PointController::class, 'updatePoint']);
    Route::post('/delete_point', [App\Http\Controllers\PointController::class, 'deletePoint']);
    Route::resource('points', App\Http\Controllers\PointController::class);


    Route::get('qrcodes/{id}/scan', [App\Http\Controllers\QrcodeController::class, 'scan'])->name('qrcodes.scan');
    Route::resource('qrcodes', App\Http\Controllers\QrcodeController::class);


    Route::resource('samples', App\Http\Controllers\SampleController::class);


    Route::resource('results', App\Http\Controllers\ResultController::class);

    Route::post('/cartograms/{id}/download', [App\Http\Controllers\CartogramController::class, 'download']);
    Route::post('/cartograms/{id}/prepare', [App\Http\Controllers\CartogramController::class, 'prepare']);
    Route::resource('cartograms', App\Http\Controllers\CartogramController::class);


    Route::post('/protocols/redirect', [App\Http\Controllers\ProtocolController::class, 'redirect']);

    Route::get('/protocols/{id}/{fieldId}/prepare', [App\Http\Controllers\ProtocolController::class, 'prepare']);
    Route::post('/protocols/{id}/prepare', [App\Http\Controllers\ProtocolController::class, 'preparePost']);
    Route::post('/protocols/{id}/generate', [App\Http\Controllers\ProtocolController::class, 'generate']);
    Route::resource('protocols', App\Http\Controllers\ProtocolController::class);
    

    Route::post('/save_route', [App\Http\Controllers\PathController::class, 'save']);
    Route::resource('paths', App\Http\Controllers\PathController::class);

});

