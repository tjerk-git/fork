<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScenarioController;

Route::get('/debug', function () {
    return app('App\Http\Controllers\DebugController')->index();
})->middleware('auth');

Route::resource('scenarios', ScenarioController::class);

// get scenario by slug
Route::get('/scenarios/start/{slug}', [ScenarioController::class, 'showBySlug']);

// verify the scenario access code
Route::post('/scenarios/start/{slug}', [ScenarioController::class, 'verifyAccessCode'])->name('verifyAccessCode');


Route::get('/results', function () {
   // get the debug controller index method
    return app('App\Http\Controllers\DebugController')->results();
});

Route::get('/', function () {
    return app('App\Http\Controllers\AuthController')->showLogin();
});


Route::group(['middleware' => ['guest']], function() {
  Route::get('login', [AuthController::class, 'showLogin'])->name('login.show');
  Route::post('login', [AuthController::class, 'login'])->name('login');
  Route::get('verify-login/{token}', [AuthController::class, 'verifyLogin'])->name('verify-login');
});

Route::get('logout', [AuthController::class, 'logout'])->name('logout');