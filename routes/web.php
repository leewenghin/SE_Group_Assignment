<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\HelpDesk\DashboardController as HelpDeskDashboard;
use App\Http\Controllers\HelpDesk\ComplaintController as HelpDeskComplaint;
use App\Http\Controllers\HelpDesk\VerifiedComplaintController as HelpDeskVerifiedComplaint;
use App\Http\Controllers\Executive\VerifiedComplaintController as ExecutiveVerifiedComplaint;

use Illuminate\Pagination\Paginator;
use App\Http\Controllers\PaginationController;
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

Route::get('/login', [LoginController::class, 'form'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    Route::resource('complaints', ComplaintController::class);

    Route::middleware('role:Administrator')->prefix('administrator')->group(function () {

        Route::resource('users', UserController::class);
        Route::resource('departments', DepartmentController::class)->except(['show']);

    });

    Route::middleware('role:Help Desk')->prefix('help-desk')->group(function () {

        Route::get('/dashboard', [HelpDeskDashboard::class, 'index'])->name('helpdesk.dashboard');

        Route::get('/complaints', [HelpDeskComplaint::class, 'index'])->name('helpdesk.complaints.index');
        Route::get('/complaints/{complaint}', [HelpDeskComplaint::class, 'show'])->name('helpdesk.complaints.show');

        Route::get('/verified-complaints', [HelpDeskVerifiedComplaint::class, 'index'])->name('helpdesk.verified_complaints.index');

        Route::post('/verified-complaints/create', [HelpDeskVerifiedComplaint::class, 'create'])->name('helpdesk.verified_complaints.create');
        Route::post('/verified-complaints', [HelpDeskVerifiedComplaint::class, 'store'])->name('helpdesk.verified_complaints.store');

        Route::post('/verified-complaints/add-complaint', [HelpDeskVerifiedComplaint::class, 'add_complaint'])->name('helpdesk.verified_complaints.add_complaint');

        Route::get('/verified-complaints/{verified_complaint}', [HelpDeskVerifiedComplaint::class, 'show'])->whereNumber('verified_complaint')->name('helpdesk.verified_complaints.show');

        Route::post('/verified-complaints/{verified_complaint}', [HelpDeskVerifiedComplaint::class, 'action'])->whereNumber('verified_complaint')->name('helpdesk.verified_complaints.action');

    });

    Route::middleware('role:Executive')->prefix('executive')->group(function () {

        Route::get('/dashboard', [ExecutiveVerifiedComplaint::class, 'dashboard'])->name('executive.dashboard');

        Route::get('/verified-complaints', [ExecutiveVerifiedComplaint::class, 'index'])->name('executive.verified_complaints.index');

        Route::get('/verified-complaints/{verified_complaint}', [ExecutiveVerifiedComplaint::class, 'show'])->whereNumber('verified_complaint')->name('executive.verified_complaints.show');

        Route::post('/verified-complaints/{verified_complaint}', [ExecutiveVerifiedComplaint::class, 'action'])->whereNumber('verified_complaint')->name('executive.verified_complaints.action');

    });

});
