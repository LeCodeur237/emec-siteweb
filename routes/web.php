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

Route::get('/church', function () {
    return view('pages.church');
});

Route::get('/mandate', function () {
    return view('pages.mandate');
});

Route::get('/events', function () {
    return view('pages.events');
});

Route::get('/media-center', function () {
    return view('pages.media');
});

Route::get('/contact-us', function () {
    return view('pages.contact');
});

Route::get('/donate', function () {
    return view('pages.donate');
});


