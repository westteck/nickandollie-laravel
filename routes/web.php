<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PhonebookController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\PhonebookController as AdminPhonebookController;
use App\Http\Controllers\Admin\ContestController as AdminContestController;
use App\Http\Controllers\Admin\SettingsController;

Route::get('/', HomeController::class)->name('home');
Route::get('/phonebook', PhonebookController::class)->name('phonebook');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contest', ContestController::class)->name('contest');
Route::get('/upload', UploadController::class)->name('upload');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/themes', ThemeController::class)->name('themes');
    Route::get('/phonebook', AdminPhonebookController::class)->name('phonebook');
    Route::get('/contests', AdminContestController::class)->name('contests');
    Route::get('/settings', SettingsController::class)->name('settings');
});
