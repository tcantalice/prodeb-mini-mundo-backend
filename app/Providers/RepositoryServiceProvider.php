<?php

namespace App\Providers;

use App\Auth\Contracts\AuthRepository;
use App\Repositories\Auth\AuthRepositoryDatabase;
use App\Repositories\Projeto\ProjetoRepositoryEloquent;
use Domain\Projeto\Contracts\ProjetoRepository;
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
        $this->app->singleton(ProjetoRepository::class, ProjetoRepositoryEloquent::class);
    }

    private function registerDatabaseRepositories()
    {
        $this->app->singleton(AuthRepository::class, AuthRepositoryDatabase::class);
    }
}
