<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('user/register', function () {
    return view('auth.user.register');
});

Route::get('user/login', function () {
    return view('auth.user.login');
});

Route::get('user/check-in', function () {
    return view('user.check-in');
});
