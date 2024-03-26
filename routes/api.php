<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\public\AuthController;
use App\Http\Controllers\api\private\admin\ActiviteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/inscription-promoteur', [AuthController::class, 'inscriptionPromoteur']);

Route::post('/inscription-abonne', [AuthController::class, 'inscriptionAbonne']);
Route::post('/connexion', [AuthController::class, 'connexion']);
Route::post('/deconnexion', [AuthController::class, 'deconnexion']);

Route::middleware('auth:sanctum')->group(function () {
    Route::ApiResource('activites', ActiviteController::class);

});