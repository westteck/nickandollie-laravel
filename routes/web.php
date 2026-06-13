<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
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

// Public site routes
Route::get('/', HomeController::class)->name('home');
Route::get('/phonebook', PhonebookController::class)->name('phonebook');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contest', ContestController::class)->name('contest');
Route::get('/upload', UploadController::class)->name('upload')->middleware('auth');
Route::post('/upload', UploadController::class)->name('upload.store')->middleware('auth');

// Breeze auth routes
require __DIR__.'/auth.php';

// Breeze expects a /dashboard redirect target — route admins to admin panel
Route::get('/dashboard', function () {
    return auth()->user()->user_type === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// Admin routes — require login
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/themes', [ThemeController::class, 'index'])->name('themes');
    Route::put('/themes', [ThemeController::class, 'update'])->name('theme.update');
    Route::get('/phonebook', AdminPhonebookController::class)->name('phonebook');
    Route::get('/contests', AdminContestController::class)->name('contests');
    Route::get('/settings', SettingsController::class)->name('settings');
});

// Breeze profile routes (keep under auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
