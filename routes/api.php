<?php

use App\Http\Controllers\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register', [RegisterUser::class,'register']);

Route::post('otp',[RegisterUser::class,'otp']);

Route::post('/verification',[RegisterUser::class,'verification']);

Route::post('/changepassword',[RegisterUser::class,'changepassword']);

Route::get('/login',[RegisterUser::class,'login'])->name('login');

Route::get('/user',[RegisterUser::class,'user']);

Route::post('/record', [RegisterUser::class, 'record']);
    // Add other routes that require authentication here



