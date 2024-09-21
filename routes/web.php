<?php

use App\Enums\Permissions;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\AthleteFeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RaceFeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
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

Route::group(['middleware' => ['auth', 'can:' . Permissions::ViewDashboard]], function () {
    
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/certificates', [DashboardController::class, 'certificates'])->name('dashboard.certificates');
    Route::get('dashboard/fees', [DashboardController::class, 'fees'])->name('dashboard.fees');
    
    Route::resource("roles", RolesController::class);

    Route::get("users/changePassword/{id}", [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::patch("users/changePassword/{id}", [UserController::class, 'changePasswordUpdate'])->name('users.changePasswordUpdate');
    Route::get("users/index_data", [UserController::class, 'index_data'])->name('users.index_data');
    Route::get("users/index_list", [UserController::class, 'index_list'])->name('users.index_list');
    Route::resource("users", UserController::class);
    
    Route::group(['middleware' => ['permission:block_users']], function () {
        Route::patch("users/{id}/block", [UserController::class, 'block'])->name('users.block');
        Route::patch("users/{id}/unblock", [UserController::class, 'unblock'])->name('users.unblock');
    });

    Route::patch('athletes/athleteFees/{athleteFee}', [AthleteController::class, 'payFee'])->name('athletes.payFee');
    Route::delete('athletes/athleteFees/{athleteFee}', [AthleteController::class, 'destroyPayFee'])->name('athletes.destroyPayFee');

    Route::resource('athletes', AthleteController::class)->except('show');
    Route::get('athletes/{athlete}/races', [AthleteController::class, 'races'])->name('athletes.races.index');
    Route::resource('athletes.certificates', CertificateController::class)->except('show');
    Route::resource('athletes.vouchers', VoucherController::class)->except('show');
    
    Route::get('races/reports', [RaceController::class, 'report'])->name('races.reports');
    Route::get('races/subscriptions', [RaceController::class, 'subscriptionCreate'])->name('races.subscription.create');
    Route::post('races/subscriptions', [RaceController::class, 'subscriptionStore'])->name('races.subscription.store');
    Route::get('races/{race}/athletes', [RaceController::class, 'athletes'])->name('races.athletes');
    Route::get('races/{race}/subscriptions-list', [RaceController::class, 'subscriptionsList'])->name('races.subscriptions-list');
    Route::resource('athleteFees', AthleteFeeController::class)->except('show');
    Route::resource('races', RaceController::class)->except('show');
    
    Route::get('races/{race}/fees/{fee}/athletesSubscribeable', [RaceFeeController::class, 'athletesSubscribeable'])->name('races.fees.athletes-subscribeable');
    
    Route::resource('races.fees', RaceFeeController::class)->except('show');
    
    Route::resource('payments', PaymentController::class)->except('show');

    //Route::get('reports/athletes', [ReportController::class, 'athletes'])->name('reports.athletes');
});
