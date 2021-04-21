<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\WordController;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/words', [WordController::class, 'getWords'])->name('word');

Route::get('/result/{id}', [GameController::class, 'getResult'])->name('result');


