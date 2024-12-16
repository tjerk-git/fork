<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanningController;


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

    // Planning routes
    Route::get('/planning', [PlanningController::class, 'index'])->name('planning.index');
    Route::post('/planning/boards', [PlanningController::class, 'storeBoard'])->name('planning.boards.store');
    Route::patch('/planning/boards/{board}', [PlanningController::class, 'updateBoard'])->name('planning.boards.update');
    Route::delete('/planning/boards/{board}', [PlanningController::class, 'destroyBoard'])->name('planning.boards.destroy');
    
    Route::post('/planning/cards', [PlanningController::class, 'storeCard'])->name('planning.cards.store');
    Route::patch('/planning/cards/{card}', [PlanningController::class, 'updateCard'])->name('planning.cards.update');
    Route::patch('/planning/cards/{card}/position', [PlanningController::class, 'updateCardPosition'])->name('planning.cards.position');
    Route::delete('/planning/cards/{card}', [PlanningController::class, 'destroyCard'])->name('planning.cards.destroy');
});

// Public routes
Route::post('/results', [ResultsController::class, 'store'])->name('results.store');

// Guest routes
Route::middleware(['guest'])->group(function () {
    // Authentication
    Route::get('login', [AuthController::class, 'showLogin'])->name('login.show');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('verify-token', [AuthController::class, 'showVerifyToken'])->name('verify-token');
    Route::post('verify-token', [AuthController::class, 'verifyToken'])->name('verify-token');
});

 // Public scenario access
 Route::get('/scenarios/start/{slug}', [ScenarioController::class, 'showBySlug']);
 Route::post('/scenarios/start/{slug}', [ScenarioController::class, 'verifyAccessCode'])->name('verifyAccessCode');

// Logout route (available to all)
Route::get('logout', [AuthController::class, 'logout'])->name('logout');