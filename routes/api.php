<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\UserApiController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users/{id?}', [UserApiController::class, 'showUser']);
Route::post('/add-user', [UserApiController::class, 'addUser']);
Route::post('/add-user-multiple', [UserApiController::class, 'addUserMultiple']);


Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', function () {
        return response()->json([
            'message' => 'Unauthenticate',
        ]);
    })->name('login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/user/{id}', [AuthController::class, 'userShow']);
    });
});