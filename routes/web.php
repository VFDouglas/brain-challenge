<?php

use App\Http\Controllers\AcceptanceTermsController;
use App\Http\Controllers\AdminController;
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

    Route::prefix('admin')->controller(AdminController::class)->group(function () {
        Route::get('events', 'events');
    })->middleware(AdminAccess::class);

    Route::get('403', function () {
        return view('403');
    })->withoutMiddleware(PageAccess::class)->name('403');
});

Auth::routes();

