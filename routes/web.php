<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\CheckUserLogin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use function Pest\Laravel\get;

Route::get('/', function () {
    return Inertia::render('client/Home');
});

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'loginPage']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'registerPage']);
    Route::post('register', [AuthController::class, 'register']);
});

// User Logged in
Route::middleware([CheckUserLogin::class])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });

    Route::prefix('reservations')->group(function() {
        Route::get('', [ReservationController::class, 'indexPage']);
        Route::post('', [ReservationController::class, 'create']);
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('', [DashboardController::class, 'clientIndex']);
        Route::get('{reservation}', [DashboardController::class, 'detailOrder']);
        Route::delete('{uniqueNumber}', [DashboardController::class, 'cancelOrder']);
    });
});


// Admin
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'adminLoginPage']);
    Route::post('login', [AuthController::class, 'adminLogin']);

    Route::middleware([AdminOnly::class])->group(function () {
        Route::get('', [DashboardController::class, 'index']);

        Route::prefix('users')->group(function () {
            Route::get('', [UserController::class, 'index']);
            Route::get('create', [UserController::class, 'createPage']);
            Route::post('create', [UserController::class, 'create']);
            Route::get('edit/{id}', [UserController::class, 'editPage']);
            Route::post('edit/{id}', [UserController::class, 'edit']);
            Route::delete('delete/{id}', [UserController::class, 'delete']);
        });

        Route::prefix('tasks')->group(function () {
            Route::get('', [TaskController::class, 'index']);
            Route::get('create', [TaskController::class, 'createPage']);
            Route::post('create', [TaskController::class, 'create']);
            Route::get('edit/{task}', [TaskController::class, 'editPage']);
            Route::post('edit/{task}', [TaskController::class, 'edit']);
            Route::delete('delete/{task}', [TaskController::class, 'delete']);
        });

        Route::prefix('packages')->group(function() {
            Route::get('', [PackageController::class, 'index']);
            Route::get('create', [PackageController::class, 'createPage']);
            Route::post('create', [PackageController::class, 'create']);
            Route::get('edit/{package}', [PackageController::class, 'editPage']);
            Route::post('edit/{package}', [PackageController::class, 'edit']);
            Route::delete('delete/{package}', [PackageController::class, 'delete']);
        });

        Route::prefix('reservations')->group(function() {
            Route::get('', [ReservationController::class, 'index']);
            Route::post('{reservation}/quick-update-status', [ReservationController::class, 'quickUpdateStatus']);
            Route::get('{reservation}/edit', [ReservationController::class, 'editPage']);
            Route::post('{reservation}/edit', [ReservationController::class, 'edit']);
            Route::delete('{reservation}', [ReservationController::class, 'delete']);
        });

        Route::prefix('assignments')->group(function() {
            Route::post('{reservation}/assign-job', [AssignmentController::class, 'assignJob']);
            Route::delete('{assignment}', [AssignmentController::class, 'delete']);
        });

        Route::prefix('schedules')->group(function() {
            Route::get('', [AssignmentController::class, 'index']);
        });
    });

});

// Worker
Route::prefix('worker')->group(function() {
    Route::get('login', [AuthController::class, 'workerLoginPage']);
    Route::post('login', [AuthController::class, 'workerLogin']);
    Route::post('logout', [AuthController::class, 'workerLogout']);
    Route::get('', [WorkerController::class, 'index']);
    Route::post('{assignment}/update-status', [WorkerController::class, 'updateStatus']);
});
