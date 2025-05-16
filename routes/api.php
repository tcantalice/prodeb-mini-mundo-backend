<?php

use App\Api\Controllers\AuthController;
use App\Api\Controllers\ProjetoController;
use App\Api\Controllers\TarefaController;
use App\Api\Middlewares\JWTAuthenticate;
use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(function () {
        Route::prefix('/auth')->group(function () {
            Route::post('/login', [AuthController::class, 'login']);
        });

        Route::middleware([JWTAuthenticate::class])->group(function () {
            Route::post('/projetos', [ProjetoController::class, 'create']);
            Route::get('/projetos', [ProjetoController::class, 'list']);
            Route::get('/projetos/{idProjeto}', [ProjetoController::class, 'get']);
            Route::put('/projetos/{idProjeto}', [ProjetoController::class, 'update']);

            Route::post('/projetos/{idProjeto}/tarefas', [TarefaController::class, 'create']);
        });

    });
