<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PhonebookController;

Route::get('/phonebook-list', PhonebookController::class)->name('api.phonebook.list');
