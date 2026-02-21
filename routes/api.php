<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\SerieController;

Route::get('/genres', [GenreController::class, 'index']);

Route::get('/movies', [MovieController::class, 'index']);

Route::get('/series', [SerieController::class, 'index']);
