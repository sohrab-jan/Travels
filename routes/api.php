<?php

use App\Http\Controllers\Api\V1\Admin\TravelController as AdminTravelController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
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

Route::prefix('travels')->controller(TravelController::class)->group(function (){
    Route::get('/','index')->name('travels.index');
});
Route::prefix('travels/{travel:slug}/tours')->controller(TourController::class)->group(function (){
    Route::get('/','index')->name('tours.index');
});

Route::prefix('/admin')->middleware(['auth:sanctum','role:admin'])->controller(AdminTravelController::class)->group(function (){
    Route::post('/travels','store');
});

Route::post('/login',LoginController::class);
