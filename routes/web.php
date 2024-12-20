<?php

use App\Enums\Permissions;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\ProceedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RaceFeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;

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
    
    Route::resource("roles", RolesController::class)->except('show');

    Route::get("users/changePassword/{user}", [UserController::class, 'changePassword'])->name('users.changePassword');
    Route::patch("users/changePassword/{user}", [UserController::class, 'changePasswordUpdate'])->name('users.changePasswordUpdate');
    Route::resource("users", UserController::class);
    
    Route::patch("users/{user}/block", [UserController::class, 'block'])->name('users.block');
    Route::patch("users/{user}/unblock", [UserController::class, 'unblock'])->name('users.unblock');
    
    Route::resource('athletes', AthleteController::class);
    Route::get('athletes/{athlete}/fees', [AthleteController::class, 'races'])->name('athletes.fees.index');
    Route::get('athletes/{athlete}/fees/{fee}/athletefee/{athleteFee}/edit', [AthleteController::class, 'editFee'])->name('athletes.fees.athletefee.edit');
    Route::patch('athletes/{athlete}/fees/{fee}/athletefee/{athleteFee}', [AthleteController::class, 'updateFee'])->name('athletes.fees.athletefee.update');
    Route::delete('athletes/{athlete}/fees/{fee}/athletefee/{athleteFee}', [AthleteController::class, 'destroySubscription'])->name('athletes.fees.athletefee.destroySubscription');
    Route::resource('athletes.certificates', CertificateController::class)->except('show');
    Route::resource('athletes.vouchers', VoucherController::class)->except('show');
    
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{year}/races', [ReportController::class, 'races'])->name('reports.races');
    Route::post('reports/download', [ReportController::class, 'download'])->name('reports.download');

    Route::get('races/subscriptions', [RaceController::class, 'subscriptionCreate'])->name('races.subscription.create');
    Route::post('races/subscriptions', [RaceController::class, 'subscriptionStore'])->name('races.subscription.store');
    Route::get('races/{race}/athletes', [RaceController::class, 'athletes'])->name('races.athletes');
    Route::get('races/{race}/subscriptions-list', [RaceController::class, 'subscriptionsList'])->name('races.subscriptions-list');
    
    Route::resource('races', RaceController::class)->except('show');

    Route::resource('proceeds', ProceedController::class);
    
    Route::get('races/{race}/fees/{fee}/athletesSubscribeable', [RaceFeeController::class, 'athletesSubscribeable'])->name('races.fees.athletes-subscribeable');
    
    Route::resource('races.fees', RaceFeeController::class)->except('show');
    
    Route::resource('payments', PaymentController::class)->only(['create', 'store']);

    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/{task}', [TaskController::class, 'exec'])->name('tasks.exec');

    /*
    * Impersonate routes
    */
    Route::impersonate();
});
