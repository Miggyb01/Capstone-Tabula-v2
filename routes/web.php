<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Firebase\EventController;
use App\Http\Controllers\Firebase\Tabulation\CriteriaController;
use App\Http\Controllers\Firebase\Tabulation\ContestantController;
use App\Http\Controllers\Firebase\Tabulation\JudgeController;
use App\Http\Controllers\Firebase\LoginController;
use App\Http\Controllers\Firebase\RegistrationController;

#Dashboard Controller
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');

#Event Controller
Route::get("event-setup", [EventController::class, 'create']);
Route ::get("event-list", [EventController::class,'list']);
Route ::post("event-list", [EventController::class,'store']);
Route ::get('edit-event/{id}', [EventController::class,'edit']);
Route ::put('update-event/{id}', [EventController::class,'update']);
Route ::get('delete-event/{id}', [EventController::class,'destroy']);

#Tabulation Controllers

#Criteria Controller
Route::get('/criteria-setup', [CriteriaController::class, 'create'])->name('criteria-setup');
Route::get('/criteria-list', [CriteriaController::class, 'list'])->name('criteria-list');
Route::post('criteria-list', [CriteriaController::class, 'store']);
Route ::get('edit-criteria/{id}', [CriteriaController::class,'edit']);
Route ::put('update-criteria/{id}', [CriteriaController::class,'update' ]);
Route ::get('delete-criteria/{id}', [CriteriaController::class,'destroy']);


#Contestant Controller
Route ::get('contestant-setup', [ContestantController::class,'create']);
Route ::get('contestant-list', [ContestantController::class,'list']);
Route ::post('contestant-list', [ContestantController::class,'store']);
Route ::get('edit-contestant/{id}', [ContestantController::class,'edit']);
Route ::put('update-contestant/{id}', [ContestantController::class,'update']);
Route ::get('delete-contestant/{id}', [ContestantController::class,'destroy']);


#Judge Controller
Route ::get('judge-setup', [JudgeController::class,'create']);
Route ::get('judge-list', [JudgeController::class,'list']);
Route ::post('judge-list', [JudgeController::class,'store']);
Route ::get('edit-judge/{id}', [JudgeController::class,'edit']);
Route ::put('update-judge/{id}', [JudgeController::class,'update']);
Route ::get('delete-judge/{id}', [JudgeController::class,'destroy']);


#Login Controller
Route ::get('login', [LoginController::class,'login']);


#Registration Controller
Route ::get('registration', [RegistrationController::class,'registration']);