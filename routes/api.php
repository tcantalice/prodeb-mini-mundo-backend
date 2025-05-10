<?php

use App\Api\Controllers\ProjetoController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function () {
        Route::post('/projetos', [ProjetoController::class, 'create']);
        Route::get('/projetos', [ProjetoController::class, 'list']);
        Route::get('/projetos/{idProjeto}', [ProjetoController::class, 'get']);
        Route::put('/projetos/{idProjeto}', [ProjetoController::class, 'update']);
    });
