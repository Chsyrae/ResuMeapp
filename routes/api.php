<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserAuthenticationController;
use App\Http\Controllers\UserController;
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


// Route::apiResource('resume-generator', ResumeController::class);
Route::apiResource('user-account', UserAuthenticationController::class);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('template', TemplateController::class);
    Route::apiResource('resume-generator', ResumeController::class);
    Route::apiResource('test-bot', AgentController::class);
});