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

Route::post('/record', [RegisterUser::class, 'record']);

Route::post('/notes',[RegisterUser::class,'notes']);

Route::get('/farmerandmilkmandetails',[RegisterUser::class,'farmerandmilkmandetails']);

Route::get('/filterusers',[RegisterUser::class,'filterusers']);

Route::post('/sendrequest',[RegisterUser::class,'sendrequest']);

Route::post('/acceptrequest',[RegisterUser::class,'acceptrequest']);

Route::post('/farmersupply',[RegisterUser::class,'farmersupply']);

Route::post('/milkmansupply',[RegisterUser::class,'milkmansupply']);

Route::post('/requestwithdraw',[RegisterUser::class,'requestwithdraw']);

Route::post('/acceptwithdraw',[RegisterUser::class,'acceptwithdraw']);

Route::post('/rejectwithdraw',[RegisterUser::class,'rejectwithdraw']);

Route::get('/farmrecords',[RegisterUser::class,'farmrecords']);

Route::get('/supplyrecords',[RegisterUser::class,'supplyrecords']);

Route::post('updateuser', [RegisterUser::class,'updateuser']);

Route::post('/review',[RegisterUser::class,'review']);

Route::get('/transactions',[RegisterUser::class,'transactions']);

Route::post('/requesttransaction',[RegisterUser::class,'requesttransaction']);

Route::post('/society',[RegisterUser::class,'society']);
