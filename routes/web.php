<?php

use App\Livewire\Chessboard\Chessboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['200' => 'OK'];
});

Route::get('/chessboard', Chessboard::class);
