<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/sales-report', [DashboardController::class, 'salesReport'])->middleware('auth')->name('sales.report');

Route::resource('users', UserController::class)->middleware('auth');
Route::get('users-api', [UserController::class, 'api'])->name('users.api')->middleware('auth');

Route::resource('menus', MenuController::class)->middleware('auth');
Route::get('menus-api', [MenuController::class, 'api'])->name('menus.api')->middleware('auth');

Route::resource('clusters', ClusterController::class)->middleware('auth');
Route::get('clusters-api', [ClusterController::class, 'api'])->name('clusters.api')->middleware('auth');

Route::resource('units', UnitController::class)->middleware('auth');
Route::get('units-api', [UnitController::class, 'api'])->name('units.api')->middleware('auth');

Route::resource('reservations', App\Http\Controllers\ReservationController::class)->middleware('auth');
Route::get('reservations-api', [App\Http\Controllers\ReservationController::class, 'api'])->name('reservations.api')->middleware('auth');

Route::resource('customers', App\Http\Controllers\CustomerController::class)->middleware('auth');
Route::get('customers-api', [App\Http\Controllers\CustomerController::class, 'api'])->name('customers.api')->middleware('auth');

// KPR Simulation routes
Route::get('/kpr-simulation', [App\Http\Controllers\KPRSimulationController::class, 'index'])->name('kpr-simulation.index');
Route::post('/kpr-simulation/calculate', [App\Http\Controllers\KPRSimulationController::class, 'calculate'])->name('kpr-simulation.calculate');

// Profile routes
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::post('/profile/photo', [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.photo.update')->middleware('auth');
Route::delete('/profile/photo', [App\Http\Controllers\ProfileController::class, 'removePhoto'])->name('profile.photo.remove')->middleware('auth');
Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update')->middleware('auth');
