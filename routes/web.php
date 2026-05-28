<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

// Interactive API Documentation
Route::get('/docs', fn() => response()->file(public_path('docs.html')));
