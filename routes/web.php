<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/thank-you', 'pages.thank-you')->name('thank-you');
Route::view('/register', 'pages.register')->name('register');
Route::view('/login', 'pages.login')->name('login');