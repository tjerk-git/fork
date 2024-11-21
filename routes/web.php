<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\StepController;
use App\Http\Controllers\ResultsController;

Route::get('/debug', function () {
    return app('App\Http\Controllers\DebugController')->index();
})->middleware('auth');

Route::resource('scenarios', ScenarioController::class)->middleware('auth');

Route::get('/scenarios/{scenario}/steps/create', [StepController::class, 'create'])->name('steps.create');
Route::post('/scenarios/{scenario}/steps', [StepController::class, 'store'])->name('steps.store');

Route::get('/scenarios/{scenario}/steps/{step}/edit', [StepController::class, 'edit'])->name('steps.edit');

// delete step
Route::delete('/scenarios/{scenario}/steps/{step}/delete', [StepController::class, 'destroy'])->name('steps.destroy');

// update step
Route::put('/scenarios/{scenario}/steps/{step}/edit', [StepController::class, 'update'])->name('steps.update');

// update scenario
Route::put('/scenarios/{scenario}/edit', [ScenarioController::class, 'update'])->name('scenarios.update');

// get scenario by slug
Route::get('/scenarios/start/{slug}', [ScenarioController::class, 'showBySlug']);

// verify the scenario access code
Route::post('/scenarios/start/{slug}', [ScenarioController::class, 'verifyAccessCode'])->name('verifyAccessCode');


Route::post('/scenario/{scenario}/update-step-order', [ScenarioController::class, 'updateStepOrder'])
    ->name('scenario.update-step-order');

Route::get('/results', function () {
   // get the debug controller index method
    return app('App\Http\Controllers\DebugController')->results();
});


// post to results controller to store the results
Route::post('/results', [ResultsController::class, 'store'])->name('results.store');

Route::get('/', function () {
   // get the debug controller index method
    return app('App\Http\Controllers\ScenarioController')->index();
})->middleware('auth');


Route::group(['middleware' => ['guest']], function() {
  Route::get('login', [AuthController::class, 'showLogin'])->name('login.show');
  Route::post('login', [AuthController::class, 'login'])->name('login');
  Route::get('verify-login/{token}', [AuthController::class, 'verifyLogin'])->name('verify-login');
});

Route::get('logout', [AuthController::class, 'logout'])->name('logout');