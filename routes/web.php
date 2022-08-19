<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\SiteController::class, 'index']);
Route::get('/joblist', [\App\Http\Controllers\JoblistController::class, 'index']);
Route::get('/joblist/new', [\App\Http\Controllers\JoblistController::class, 'newJob']);
Route::post('/joblist/new', [\App\Http\Controllers\JoblistController::class, 'newJob']);

Route::get('/competitor/email', [\App\Http\Controllers\CompetitorController::class, 'email']);
Route::get('/application/skills', [\App\Http\Controllers\ApplicationController::class, 'existSkills']);
Route::post('/application', [\App\Http\Controllers\ApplicationController::class, 'submit']);
