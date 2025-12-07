<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/invitations/company', [App\Http\Controllers\InvitationController::class, 'storeCompany']);
    Route::post('/invitations/user', [App\Http\Controllers\InvitationController::class, 'storeUser']);
    Route::post('/urls', [App\Http\Controllers\UrlController::class, 'store']);
    Route::get('/management/clients', [App\Http\Controllers\ManagementController::class, 'clients']);
    Route::delete('/management/clients/{company}', [App\Http\Controllers\ManagementController::class, 'destroyClient']);
    Route::get('/management/urls', [App\Http\Controllers\ManagementController::class, 'urls']);

    Route::get('/management/team', [App\Http\Controllers\ManagementController::class, 'team']);
    Route::delete('/management/team/{member}', [App\Http\Controllers\ManagementController::class, 'destroyMember']);

    Route::get('/invitations/create', [App\Http\Controllers\InvitationController::class, 'createCompany']);
    Route::get('/invitations/user/create', [App\Http\Controllers\InvitationController::class, 'createUser']);

    Route::get('/urls/create', [App\Http\Controllers\UrlController::class, 'create']);
    Route::get('/urls/export', [App\Http\Controllers\ExportController::class, 'export']);
});

Route::get('/register', [App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register']);

Route::get('/{shortCode}', [App\Http\Controllers\UrlController::class, 'redirect']);

