<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingAdminController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::post('/register', [AuthController::class, 'register']);


Route::middleware(['auth:sanctum', RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('//users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    Route::get('/bookings', [BookingAdminController::class, 'index']);
    Route::get('/bookings/{id}', [BookingAdminController::class, 'show']);
    Route::put('/bookings/{id}/status', [BookingAdminController::class, 'updateStatus']);
    Route::get('/stats', [AdminDashboardController::class, 'stats']);
    Route::get('/recent-bookings', [AdminDashboardController::class, 'recentBookings']);

    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', RoleMiddleware::class . ':kasir'])->group(function () {
    Route::post('/kasir/transactions',[TransactionsController::class, 'store']);
    Route::get('/kasir/transactions', [TransactionsController::class, 'index']);
    Route::get('/kasir/transactions/report', [TransactionsController::class, 'report']);
});

Route::middleware(['auth:sanctum', RoleMiddleware::class . ':barber'])->group(function () {
    Route::get('/barber/dashboard', fn () => response()->json(['message' => 'Barber Only']));
});

Route::middleware(['auth:sanctum', RoleMiddleware::class . ':customer'])->group(function () {
    Route::post('/customer/booking', [BookingController::class, 'store']);
    Route::get('/my-bookings', [BookingController::class, 'index']);
});
