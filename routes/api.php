<?php

use App\Api\Controllers\ProjetoController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->prefix('api')
    ->group(function () {
        Route::post('/projetos', [ProjetoController::class, 'create']);
    });
