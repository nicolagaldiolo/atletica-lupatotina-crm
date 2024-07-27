<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\AthleteFeeController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\RaceFeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth Routes
require __DIR__.'/auth.php';

/*
*
* Backend Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'can:view_backend']], function () {
    
    Route::get('/', [BackendController::class, 'index'])->name('index');
    Route::get('dashboard', [BackendController::class, 'index'])->name('dashboard');
    Route::get('dashboard/certificates', [BackendController::class, 'certificates'])->name('dashboard.certificates');
    
    Route::resource("roles", RolesController::class);

    Route::get("users/changePassword/{id}", [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::patch("users/changePassword/{id}", ['as' => "users.changePasswordUpdate", 'uses' => "UserController@changePasswordUpdate"]);
    Route::get("users/trashed", ['as' => "users.trashed", 'uses' => "UserController@trashed"]);
    Route::patch("users/trashed/{id}", ['as' => "users.restore", 'uses' => "UserController@restore"]);
    Route::get("users/index_data", ['as' => "users.index_data", 'uses' => "UserController@index_data"]);
    Route::get("users/index_list", ['as' => "users.index_list", 'uses' => "UserController@index_list"]);
    Route::resource("users", UserController::class);
    Route::patch("users/{id}/block", ['as' => "users.block", 'uses' => "UserController@block", 'middleware' => ['permission:block_users']]);
    Route::patch("users/{id}/unblock", ['as' => "users.unblock", 'uses' => "UserController@unblock", 'middleware' => ['permission:block_users']]);

    Route::get('athletes/trashed', [AthleteController::class, 'trashed'])->name('athletes.trashed');
    Route::get('athletes/trashed/{id}', [AthleteController::class, 'showTrashed'])->name('athletes.trashed.show');
    Route::patch('athletes/trashed/{id}', [AthleteController::class, 'restore'])->name('athletes.restore');
    Route::resource('athletes', AthleteController::class)->except('show');
    Route::get('athletes/{athlete}/races', [AthleteController::class, 'races'])->name('athletes.races.index');
    Route::resource('athletes.certificates', CertificateController::class)->except('show');
    Route::get('races/trashed', [RaceController::class, 'trashed'])->name('races.trashed');
    Route::get('races/trashed/{id}', [RaceController::class, 'showTrashed'])->name('races.trashed.show');
    Route::patch('races/trashed/{id}', [RaceController::class, 'restore'])->name('races.restore');
    Route::get('races/subscriptions', [RaceController::class, 'subscriptionCreate'])->name('races.subscription.create');
    Route::post('races/subscriptions', [RaceController::class, 'subscriptionStore'])->name('races.subscription.store');
    Route::get('races/{race}/athletes', [RaceController::class, 'athletes'])->name('races.athletes');
    Route::get('races/{race}/subscriptions-list', [RaceController::class, 'subscriptionsList'])->name('races.subscriptions-list');
    Route::resource('athleteFees', AthleteFeeController::class)->except('show');
    Route::resource('races', RaceController::class)->except('show');
    Route::resource('races.fees', RaceFeeController::class)->except('show');
    Route::get('reports/athletes', [ReportController::class, 'athletes'])->name('reports.athletes');
});
