<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConsensoController;

Route::get('/', function () {
    return view('home');
});


Route::get('/consenso/{tipo}', [ConsensoController::class, 'form']);
Route::post('/consenso/salva', [ConsensoController::class, 'salva']);
Route::get('/consenso/pdf/{id}', [ConsensoController::class, 'pdf']);

Route::get('/test-mail', function () {

    Mail::raw('TEST OK', function($m){

        $m->to('info@studioweb19.it')
          ->subject('Test');

    });

    return 'OK';
});