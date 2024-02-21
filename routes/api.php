 <?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * OSM CONTROLLERS
 */
use App\Http\Controllers\OSMController;

/**
 * STOP CONTROLLER
 */
use App\Http\Controllers\StopController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * OSM ROUTES
 */
Route::post('osm_data_import', [OSMController::class, 'osmImportData']);


/**
 * STOP ROUTES
 */
Route::prefix('stop')->group(function () {
    Route::get('', [StopController::class, 'read']);
    Route::get('/{stop_id}', [StopController::class, 'readById']);
});

