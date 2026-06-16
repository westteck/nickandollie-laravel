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
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\PhotoManagementController;
use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WeddingProfileController;

// Public site routes
Route::get('/', HomeController::class)->name('home');
Route::get('/phonebook', PhonebookController::class)->name('phonebook');
Route::get('/phonebook/all', [PhonebookController::class, 'all'])->name('phonebook.all');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/photo/{id}', [PhotoController::class, 'show'])->name('photo.show');
Route::get('/profile/{id?}', [WeddingProfileController::class, 'show'])->name('wedding.profile');
Route::get('/contest', [ContestController::class, 'index'])->name('contest');
Route::get('/contest/{id}', [ContestController::class, 'show'])->name('contest.show');
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
    Route::post('/themes/preset', [ThemeController::class, 'switch'])->name('theme.preset');
    Route::get('/themes/preview', [ThemeController::class, 'preview'])->name('theme.preview');
    Route::get('/phonebook', [AdminPhonebookController::class, 'index'])->name('phonebook');
    Route::post('/phonebook', [AdminPhonebookController::class, 'store'])->name('phonebook.store');
    Route::put('/phonebook/{id}', [AdminPhonebookController::class, 'update'])->name('phonebook.update');
    Route::delete('/phonebook/{id}', [AdminPhonebookController::class, 'destroy'])->name('phonebook.destroy');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/page', [SettingsController::class, 'savePage'])->name('settings.page');

    // Contest CRUD
    Route::get('/contests', [AdminContestController::class, 'index'])->name('contests');
    Route::post('/contests', [AdminContestController::class, 'store'])->name('contests.store');
    Route::put('/contests/{id}', [AdminContestController::class, 'update'])->name('contests.update');
    Route::delete('/contests/{id}', [AdminContestController::class, 'destroy'])->name('contests.destroy');

    // Admin User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    Route::get('/users/list', [UserManagementController::class, 'list'])->name('users.list');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Admin Photo Management
    Route::get('/photos', [PhotoManagementController::class, 'index'])->name('photos');
    Route::get('/photos/list', [PhotoManagementController::class, 'list'])->name('photos.list');
    Route::put('/photos/{id}', [PhotoManagementController::class, 'update'])->name('photos.update');
    Route::delete('/photos/{id}', [PhotoManagementController::class, 'destroy'])->name('photos.destroy');

    // Admin Comment Moderation
    Route::get('/comments', [CommentModerationController::class, 'index'])->name('comments');
    Route::get('/comments/list', [CommentModerationController::class, 'list'])->name('comments.list');
    Route::delete('/comments/{id}', [CommentModerationController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/bulk-delete', [CommentModerationController::class, 'bulkDestroy'])->name('comments.bulk-destroy');
});

// Breeze profile routes (keep under auth)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update.post');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile tab data endpoints
    Route::get('/profile/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
    Route::get('/profile/uploads', [ProfileController::class, 'uploads'])->name('profile.uploads');
    Route::get('/profile/votes', [ProfileController::class, 'votes'])->name('profile.votes');
    Route::get('/profile/comments', [ProfileController::class, 'comments'])->name('profile.comments');
});
