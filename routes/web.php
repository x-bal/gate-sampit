<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\GateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/cards/change/{card:id}', [CardController::class, 'change'])->name("cards.change");
Route::resource('/cards', CardController::class);

Route::get('/get-logs', [LogController::class, 'get']);
Route::get('/gate-logs', [LogController::class, 'gate']);
Route::resource('/logs', LogController::class);

Route::get('/gates/{gate:id}/stream', [GateController::class, 'stream'])->name('gates.stream');
Route::resource('gates', GateController::class);
