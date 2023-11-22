<?php

use App\Http\Controllers\AcceptanceTermsController;
use App\Http\Controllers\Admin\AwardsController as AdminAwardsController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\PresentationsController as AdminPresentationsController;
use App\Http\Controllers\Admin\SchedulesController as AdminSchedulesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AwardsController;
use App\Http\Controllers\PresentationsController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\SchedulesController;
use App\Http\Middleware\AdminAccess;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\PageAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Authenticated routes
Route::middleware([Authenticate::class, PageAccess::class])->group(function () {
    Route::get('/', function () {
        return redirect('home');
    });

    Route::controller(AppController::class)->group(function () {
        Route::get('home', 'index');
        Route::post('log_access', 'logAccess');
        Route::get('detailed_score', 'detailedScore');
    });

    Route::controller(AcceptanceTermsController::class)->group(function () {
        Route::get('acceptance_terms', function () {
            return view('acceptance_terms');
        });
        Route::post('accept_terms', 'acceptTerms');
    });

    Route::controller(AwardsController::class)->group(function () {
        Route::get('awards', 'index');
    });

    Route::controller(QRCodeController::class)->group(function () {
        Route::get('qrcode', 'index');
        Route::post('scan_qrcode', 'scanQRCode');
    });

    Route::controller(QuestionsController::class)->group(function () {
        Route::get('questions', 'index');
        Route::post('answerQuestion', 'answerQuestion');
    });

    Route::controller(PresentationsController::class)->group(function () {
        Route::get('presentations', 'index');
    });

    Route::controller(SchedulesController::class)->group(function () {
        Route::get('schedules', 'index');
    });

    // -------------------- Admin Routes -------------------- //
    Route::prefix('admin')->middleware(AdminAccess::class)->group(function () {
        Route::controller(EventsController::class)->group(function () {
            Route::get('events', 'events');
            Route::get('events/{id}', 'getEvent')->whereNumber('id');
            Route::post('events', 'createEvent');
            Route::put('events/{id}', 'editEvent')->whereNumber('id');
            Route::delete('events/{id}', 'deleteEvent')->whereNumber('id');
        });
        Route::controller(UsersController::class)->group(function () {
            Route::get('users', 'users');
            Route::get('users/{id}', 'getUser')->whereNumber('id');
            Route::post('users', 'createUser');
            Route::put('users/{id}', 'editUser')->whereNumber('id');
            Route::delete('users/{id}', 'deleteUser')->whereNumber('id');
        });
        Route::controller(AdminPresentationsController::class)->group(function () {
            Route::get('presentations', 'presentations');
            Route::get('presentations/{id}', 'getPresentation')->whereNumber('id');
            Route::post('presentations', 'createPresentation');
            Route::put('presentations/{id}', 'editPresentation')->whereNumber('id');
            Route::delete('presentations/{id}', 'deletePresentation')->whereNumber('id');
        });
        Route::controller(AdminSchedulesController::class)->group(function () {
            Route::get('schedules', 'schedules');
            Route::get('schedules/{id}', 'getSchedule')->whereNumber('id');
            Route::post('schedules', 'createSchedule');
            Route::put('schedules/{id}', 'editSchedule')->whereNumber('id');
            Route::delete('schedules/{id}', 'deleteSchedule')->whereNumber('id');
        });
        Route::controller(AdminAwardsController::class)->group(function () {
            Route::get('awards', 'awards');
            Route::get('awards/{id}', 'getAward')->whereNumber('id');
            Route::post('awards', 'createAward');
            Route::put('awards/{id}', 'editAward')->whereNumber('id');
            Route::delete('awards/{id}', 'deleteAward')->whereNumber('id');
        });
        Route::controller(PagesController::class)->group(function () {
            Route::get('pages', 'pages');
            Route::get('pages/{id}', 'getPage')->whereNumber('id');
            Route::put('pages/{id}', 'editPage')->whereNumber('id');
        });
    });

    Route::get('403', function () {
        return view('403');
    })->withoutMiddleware(PageAccess::class)->name('403');
});

Auth::routes();

