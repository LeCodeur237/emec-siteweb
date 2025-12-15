<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/get-connected', function () {
    return view('pages.getconnect');
});

Route::get('/about-us', function () {
    return view('pages.about');
});

Route::get('/our-team', function () {
    return view('pages.team');
});

Route::get('/mandate', function () {
    return view('pages.mandate');
});


