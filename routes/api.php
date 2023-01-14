<?php

use App\Http\Controllers\API\DeviceApiController;
use App\Http\Controllers\API\DeviceCodeApiController;
use App\Http\Controllers\API\ReviewApiController;
use App\Http\Controllers\API\v02\DeviceCodeApiV2Controller;
use App\Http\Controllers\API\v02\RegistrationApiController;
use App\Models\DeviceLanguages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::put('/device/{deviceCodeId}', [AuthorizationController::class, 'update']);
Route::post('/v01/device-code', [DeviceCodeApiController::class, 'store']);
Route::post('/v02/device-code', [DeviceCodeApiV2Controller::class, 'store']);
Route::post('/v01/device', [DeviceApiController::class, 'store']);
Route::get('/v01/device/logout/{deviceId}', [DeviceApiController::class, 'destroy']);

Route::get('/test', function () {
    $modelDeviceLanguages = new DeviceLanguages();
    $deviceLanguages = DB::table($modelDeviceLanguages->getTable())
        ->where('device_id', '=', 2)
        ->get(['device_local']);
    $deviceLang = [];
    foreach ($deviceLanguages as $lang) $deviceLang[] = $lang->device_local;
    return response($deviceLang);
});

Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('/v01/authentication', function () {
            return response([], 204);
        });
        Route::get('/v01/device/{data}', [DeviceApiController::class, 'index']);
        Route::post('/v02/device/out-of-service', [DeviceApiController::class, 'storeStatus']);
        Route::post('/v02/review', [ReviewApiController::class, 'store']);
        Route::post('/v02/registration', [RegistrationApiController::class, 'store']);
    }
);
