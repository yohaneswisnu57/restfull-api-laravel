<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Interactive API Documentation
Route::get('/docs', function () {
    return response()->file(public_path('docs.html'));
});
