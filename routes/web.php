<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::resource('users', UserController::class)->middleware('auth');
Route::get('users-api', [UserController::class, 'api'])->name('users.api')->middleware('auth');

Route::resource('companies', CompanyController::class)->middleware('auth');
Route::get('companies-api', [CompanyController::class, 'api'])->name('companies.api')->middleware('auth');

Route::resource('menus', MenuController::class)->middleware('auth');
Route::get('menus-api', [MenuController::class, 'api'])->name('menus.api')->middleware('auth');

Route::resource('clusters', ClusterController::class)->middleware('auth');
Route::get('clusters-api', [ClusterController::class, 'api'])->name('clusters.api')->middleware('auth');

Route::resource('units', UnitController::class)->middleware('auth');
Route::get('units-api', [UnitController::class, 'api'])->name('units.api')->middleware('auth');
