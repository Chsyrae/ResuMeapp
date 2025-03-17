<?php

use App\Http\Controllers\ResumeController;
use App\Http\Controllers\UserAuthenticationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserDetailsController;

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

// Route::apiResource('user_details', UserDetailsController::class);

// Route::get('/user_details/create', [App\Http\Controllers\UserDetailsController::class, 'create'])->name('user_details.create');

Route::apiResource('resume-generator', ResumeController::class);
Route::apiResource('user-account', UserAuthenticationController::class);