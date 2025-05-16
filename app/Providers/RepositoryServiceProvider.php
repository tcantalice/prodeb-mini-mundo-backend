<?php

namespace App\Providers;

use App\Auth\Contracts\AuthRepository;
use App\Infraestructure\Database\Auth\AuthRepository as DatabaseAuthRepository;
use App\Infraestructure\Eloquent\Projeto\ProjetoRepository as EloquentProjetoRepository;
use App\Infraestructure\Eloquent\Tarefa\TarefaRepository as EloquentTarefaRepository;
use Domain\Projeto\Contracts\ProjetoRepository;
use Domain\Tarefa\Contracts\TarefaRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->runningUnitTests()) {
            $this->registerDatabaseRepositories();
            $this->registerEloquentRepositories();
        }
    }

    private function registerEloquentRepositories()
    {
        $this->app->singleton(ProjetoRepository::class, EloquentProjetoRepository::class);
        $this->app->singleton(TarefaRepository::class, EloquentTarefaRepository::class);
    }

    private function registerDatabaseRepositories()
    {
        $this->app->singleton(AuthRepository::class, DatabaseAuthRepository::class);
    }
}
