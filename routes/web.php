<?php

use Illuminate\Support\Facades\Route;


Route::view('/', 'pages.home')
    ->name('home');


Route::view('/contact', 'pages.contact')
    ->name('contact');


Route::view('/thank-you', 'pages.thank-you')
    ->name('thank-you');


Route::view('/register', 'pages.register')
    ->name('register');


Route::view('/login', 'pages.login')
    ->name('login');


Route::view('/pricing', 'pages.pricing')
    ->name('pricing');


Route::view('/payment', 'pages.payment')
    ->name('payment');

    Route::view('/dashboard', 'pages.user-dashboard')->name('dashboard');
Route::view('/profile-settings', 'pages.profile-settings')->name('profile-settings');

Route::view('/admin', 'pages.admin-dashboard')->name('admin.dashboard');
Route::view('/admin/users', 'pages.admin-users')->name('admin.users');