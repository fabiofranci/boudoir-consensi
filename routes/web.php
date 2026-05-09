<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsensoController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/consenso/{tipo}', [ConsensoController::class, 'form']);
Route::post('/consenso/salva', [ConsensoController::class, 'salva']);
Route::get('/consenso/pdf/{id}', [ConsensoController::class, 'pdf']);