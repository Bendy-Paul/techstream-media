<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryFilterController;
use App\Http\Controllers\NewsFilterController;

Route::get('/category-filter', [CategoryFilterController::class, 'index']);
Route::get('/news-filter', [NewsFilterController::class, 'index']);
