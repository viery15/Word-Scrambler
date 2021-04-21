<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\WordController;
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

Route::get('/', [GameController::class, 'index'])->name('game');

//WORD
Route::get('/word/export', [WordController::class, 'export'])->name('word');
Route::get('/word/scrambleWord/{type}', [WordController::class, 'getScrambleWords'])->name('word');

//GAME
Route::get('/play', [GameController::class, 'play'])->name('game');
Route::post('/game/profile', [GameController::class, 'setProfile'])->name('game');
Route::post('/game/scoring', [GameController::class, 'scoring'])->name('game');
Route::post('/game/submit', [GameController::class, 'submit'])->name('game');
Route::get('/game/result/{id}', [GameController::class, 'result'])->name('game');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/history', [GameController::class, 'history'])->name('history');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

});

//LOGIN
Route::get('login', [AuthController::class, 'showFormLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);


