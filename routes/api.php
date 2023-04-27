<?php

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/historical-data', [HomeController::class, 'getHistoricalData']);
Route::post('/historical-data/send', [HomeController::class, 'sendHistoricData']);
Route::get('/company-symbols', [HomeController::class, 'getCompanySymbols']);
