<?php

use App\Http\Controllers\public\AccueilController;
use App\Http\Controllers\public\EvenementController;

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

Route::get('/', [AccueilController::class, 'index']) ->name('accueil');
Route::get('/evenements', [AccueilController::class, 'index']) ->name('public.evenements');

