<?php

 use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

// Route::get('/', function () {
//     return view('main');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Route::post('/user_details/create', [App\Http\Controllers\UserDetailsController::class, 'create'])->name('user_details.create')->middleware('auth');

// Route::post('/user_details', [App\Http\Controllers\UserDetailsController::class, 'store'])->name('user_details.store')->middleware('auth');

// Route::get('/send-test-email', function () {
//     Mail::raw('This is a test email.', function ($message) {
//         $message->to('recipient@example.com')
//                 ->subject('Test Email');
//     });

//     return 'Test email sent!';
// });

Route::get('/', function () {
    return view('welcome');
});