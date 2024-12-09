<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\UserController;

// Debug route
Route::get('/debug', function () {
    return app('App\Http\Controllers\DebugController')->index();
})->middleware('auth');

// Home route
Route::get('/', function () {
    return app('App\Http\Controllers\ScenarioController')->index();
})->middleware('auth');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Users
    Route::resource('users', UserController::class);

    // Scenarios
    Route::resource('scenarios', ScenarioController::class);
    Route::put('/scenarios/{scenario}/edit', [ScenarioController::class, 'update'])->name('scenarios.update');
    Route::post('/scenario/{scenario}/update-step-order', [ScenarioController::class, 'updateStepOrder'])
        ->name('scenario.update-step-order');

    // Steps
    Route::get('/scenarios/{scenario}/steps/create', [StepController::class, 'create'])->name('steps.create');
    Route::post('/scenarios/{scenario}/steps', [StepController::class, 'store'])->name('steps.store');
    Route::get('/scenarios/{scenario}/steps/{step}/edit', [StepController::class, 'edit'])->name('steps.edit');
    Route::put('/scenarios/{scenario}/steps/{step}/edit', [StepController::class, 'update'])->name('steps.update');
    Route::delete('/scenarios/{scenario}/steps/{step}/delete', [StepController::class, 'destroy'])->name('steps.destroy');

    // Results
    Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
    Route::get('/results/{scenario}', [ResultsController::class, 'show'])->name('results.show');

    Route::get('/scenarios/{scenario}/results/csv', [ResultsController::class, 'createCSV'])->name('results.csv');
});

// Public routes
Route::post('/results', [ResultsController::class, 'store'])->name('results.store');

// Guest routes
Route::middleware(['guest'])->group(function () {
   

    // Authentication
    Route::get('login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('verify-login/{token}', [AuthController::class, 'verifyLogin'])->name('verify-login');
});

 // Public scenario access
 Route::get('/scenarios/start/{slug}', [ScenarioController::class, 'showBySlug']);
 Route::post('/scenarios/start/{slug}', [ScenarioController::class, 'verifyAccessCode'])->name('verifyAccessCode');

// Logout route (available to all)
Route::get('logout', [AuthController::class, 'logout'])->name('logout');