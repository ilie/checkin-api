<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CheckinsController;
use App\Http\Controllers\CheckinsUserController;
use App\Http\Controllers\Auth\AuthController;

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

// Login and reset password
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.reset');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

// Users
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [UsersController::class, 'index'])->name('users.index');
    Route::post('/register', [UsersController::class, 'store'])->name('users.store');
    Route::get('/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::put('/{id}/update-password', [UsersController::class, 'updatePassword'])->name('users.update.password');
    Route::put('/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
});
// Checkins
Route::prefix('checkins')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CheckinsController::class, 'index'])->name('checkins.index');
    Route::post('/', [CheckinsController::class, 'store'])->name('checkins.store');
    Route::post('/add', [CheckinsController::class, 'storeManually'])->name('checkins.store.manually');
    Route::get('/status', [CheckinsController::class, 'status'])->name('checkins.status');
    Route::get('/{checkin}', [CheckinsController::class, 'show'])->name('checkins.show');
    Route::put('/{checkin}', [CheckinsController::class, 'update'])->name('checkins.update');
    Route::delete('/{checkin}', [CheckinsController::class, 'destroy'])->name('checkins.destroy');
});
