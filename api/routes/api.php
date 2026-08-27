<?php

use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketUpdateController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', RegisterUserController::class);
Route::post('/login', LoginUserController::class);

// System Settings
Route::get('/system-settings', [SystemSettingsController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/');

    // Enums
    Route::get('/enums', [EnumController::class, 'index']);

    // Users
    Route::apiResource('/users', UserController::class);

    // Companies
    Route::get('/companies', [CompanyController::class, 'index']);

    // Sites
    Route::get('/companies/{company}/sites', [SiteController::class, 'index']);

    // Tickets
    Route::apiResource('/tickets', TicketController::class);
    Route::apiResource('/tickets.updates', TicketUpdateController::class)->shallow();
});
