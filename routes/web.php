<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPController;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::view('/login-with-otp', 'auth.loginwithotp')->name('login.with.otp');
//Route::view('/login-with-otp', [App\Http\Controllers\OTPController::class, 'loginwithotp'])->name('login.with.otp');
Route::get('/login-with-otp', [OTPController::class, 'loginwithotp'])->name('login.with.otp');
Route::post('/login-with-otp-post', [OTPController::class, 'loginwithotppost'])->name('login.with.otp.post');
Route::view('/confirm-login-with-otp', 'auth.confirmloginwithotp')->name('confirm.login.with.otp');
Route::post('/confirm-login-with-otp-post', [OTPController::class, 'confirmloginwithotppost'])->name('confirm.login.with.otp,post');


