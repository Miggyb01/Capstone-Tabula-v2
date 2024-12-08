<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebase\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

//Admin Routes
use App\Http\Controllers\Firebase\Admin\AdminEventController;
use App\Http\Controllers\Firebase\Admin\AdminCriteriaController;
use App\Http\Controllers\Firebase\Admin\AdminContestantController;
use App\Http\Controllers\Firebase\Admin\AdminJudgeController;
use App\Http\Controllers\Firebase\Admin\AdminCalendarController;
use App\Http\Controllers\Firebase\Admin\AdminResultController;
use App\Http\Controllers\Firebase\Admin\AdminScoreController;
use App\Http\Controllers\Firebase\Admin\AdminReportController;
use App\Http\Controllers\Firebase\Admin\AdminOrganizerController;

// Organizer Routes
use App\Http\Controllers\Firebase\Organizer\OrganizerContestantController;
use App\Http\Controllers\Firebase\Organizer\OrganizerEventController;
use App\Http\Controllers\Firebase\Organizer\OrganizerCriteriaController;
use App\Http\Controllers\Firebase\Organizer\OrganizerJudgeController;
use App\Http\Controllers\Firebase\Organizer\OrganizerCalendarController;
use App\Http\Controllers\Firebase\Organizer\OrganizerResultController;
use App\Http\Controllers\Firebase\Organizer\OrganizerScoreController;
use App\Http\Controllers\Firebase\Organizer\OrganizerReportController;

// Judge Routes
use App\Http\Controllers\Firebase\Judge\JudgeEventController;
use App\Http\Controllers\Firebase\Judge\JudgeScoreController;
use App\Http\Controllers\Firebase\Judge\JudgeReportController;
use App\Http\Controllers\Firebase\Judge\JudgeTabulationController; 
use App\Http\Controllers\Firebase\Judge\JudgeCalendarController;

// Home Route
Route::get('/', [HomeController::class, 'index']);

// Authentication Routes

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/email/verify', [RegisterController::class, 'showVerificationNotice'])
    ->name('verify.email.notice');
Route::post('/email/verification-resend', [RegisterController::class, 'resendVerification'])
    ->name('verification.resend');


Route::get('/dashboard', function() {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminEventController::class, 'dashboard'])->name('dashboard');
    
    // Event Routes
    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/setup', [AdminEventController::class, 'create'])->name('setup');
        Route::get('/list', [AdminEventController::class, 'list'])->name('list');
        Route::post('/store', [AdminEventController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminEventController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminEventController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdminEventController::class, 'destroy'])->name('delete');
    });

    
    // Criteria Routes
    Route::prefix('criteria')->name('criteria.')->group(function () {
        Route::get('/list', [AdminCriteriaController::class, 'list'])->name('list');
        Route::get('/setup', [AdminCriteriaController::class, 'create'])->name('setup');
        Route::post('/store', [AdminCriteriaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminCriteriaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminCriteriaController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdminCriteriaController::class, 'destroy'])->name('delete');
    });

    // Contestant Routes
    Route::prefix('contestant')->name('contestant.')->group(function () {
        Route::get('/setup', [AdminContestantController::class, 'create'])->name('setup');
        Route::get('/list', [AdminContestantController::class, 'list'])->name('list');
        Route::post('/store', [AdminContestantController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminContestantController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminContestantController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdminContestantController::class, 'destroy'])->name('delete');
    });

    // Judge Routes
    Route::prefix('judge')->name('judge.')->group(function () {
        Route::get('/setup', [AdminJudgeController::class, 'create'])->name('setup');
        Route::get('/list', [AdminJudgeController::class, 'list'])->name('list');
        Route::post('/store', [AdminJudgeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [AdminJudgeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [AdminJudgeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [AdminJudgeController::class, 'destroy'])->name('delete');
        Route::get('/reset-password/{id}', [AdminJudgeController::class, 'resetPassword'])->name('reset-password');
    });

    Route::get('/calendar', [AdminCalendarController::class, 'index'])->name('calendar');

    Route::get('/score', [AdminScoreController::class, 'score'])->name('score');

    Route::get('/result', [AdminResultController::class, 'result'])->name('result');

    Route::get('/report', [AdminReportController::class, 'report'])->name('report');

    Route::get('/profile', [AdminOrganizerController::class, 'profile'])->name('profile');
});



// Organizer Routes
Route::prefix('organizer')->name('organizer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OrganizerEventController::class, 'dashboard'])->name('dashboard');
    
    // Event Routes
    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/setup', [OrganizerEventController::class, 'create'])->name('setup');
        Route::get('/list', [OrganizerEventController::class, 'list'])->name('list');
        Route::post('/store', [OrganizerEventController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [OrganizerEventController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [OrganizerEventController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [OrganizerEventController::class, 'destroy'])->name('delete');
    });

    // Criteria Routes
    Route::prefix('criteria')->name('criteria.')->group(function () {
        Route::get('/setup', [OrganizerCriteriaController::class, 'create'])->name('setup');
        Route::get('/list', [OrganizerCriteriaController::class, 'list'])->name('list');
        Route::post('/store', [OrganizerCriteriaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [OrganizerCriteriaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [OrganizerCriteriaController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [OrganizerCriteriaController::class, 'destroy'])->name('delete');
    });

    // Contestant Routes
    Route::prefix('contestant')->name('contestant.')->group(function () {
        Route::get('/setup', [OrganizerContestantController::class, 'create'])->name('setup');
        Route::get('/list', [OrganizerContestantController::class, 'list'])->name('list');
        Route::post('/store', [OrganizerContestantController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [OrganizerContestantController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [OrganizerContestantController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [OrganizerContestantController::class, 'destroy'])->name('delete');
    });

    // Judge Routes
    Route::prefix('judge')->name('judge.')->group(function () {
        Route::get('/setup', [OrganizerJudgeController::class, 'create'])->name('setup');
        Route::get('/list', [OrganizerJudgeController::class, 'list'])->name('list');
        Route::post('/store', [OrganizerJudgeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [OrganizerJudgeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [OrganizerJudgeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [OrganizerJudgeController::class, 'destroy'])->name('delete');
        Route::get('/reset-password/{id}', [OrganizerJudgeController::class, 'resetPassword'])->name('reset-password');
    });

    Route::get('/calendar', [OrganizerCalendarController::class, 'index'])->name('calendar');

    Route::get('/score', [OrganizerScoreController::class, 'score'])->name('score');

    Route::get('/result', [OrganizerResultController::class, 'result'])->name('result');
    
    Route::get('/report', [OrganizerReportController::class, 'report'])->name('report');
});



// Judge Routes
Route::prefix('judge')->name('judge.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [JudgeEventController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/tabulation', [JudgeTabulationController::class, 'index'])->name('tabulation');
    
    Route::post('/tabulation/save-score', [JudgeTabulationController::class, 'saveScore'])->name('tabulation.save-score');
    
    // Make tabulation accessible directly as well
    Route::get('/tabulation', [JudgeTabulationController::class, 'index'])->name('tabulation');

    // Calendar Route
    Route::get('/calendar', [JudgeCalendarController::class, 'index'])->name('calendar');

    // Score Route
    Route::get('/score', [JudgeScoreController::class, 'score'])->name('score');

    // Result Route
    Route::get('/result', [JudgeResultController::class, 'result'])->name('result');
    
    // Report Route
    Route::get('/report', [JudgeReportController::class, 'report'])->name('report');
});