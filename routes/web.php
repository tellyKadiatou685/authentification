<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TasksecController;
use Illuminate\Support\Facades\Route;






Route::get('/', [TaskController::class,'index'])->name('index')->middleware('auth');

Route::resource('task', TasksecController::class);
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/otp', [AuthController::class, 'showOtpForm'])->name('showOtpForm');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verifyOtp');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



