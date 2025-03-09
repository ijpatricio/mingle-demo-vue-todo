<?php

use App\Livewire\TodoApp;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/todo', TodoApp::class);
