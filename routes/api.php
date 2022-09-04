<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// PUBLIC ROUTES
Route::post('/login', [AuthController::class, 'login'])->name('login-api');
Route::post('/register', [AuthController::class, 'register'])->name('register-api');

// PROTECTED ROUTES
Route::group(['middleware' => ['auth:sanctum']], function() {
    // GET ALL THE FUNCTION INSIDE THE TASK CONTROLLER (EXCEPT THE SHOW FUNCTION IN RESOURCE)
    Route::resource('/tasks', TaskController::class);
    // Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('show-api');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout-api');
});
