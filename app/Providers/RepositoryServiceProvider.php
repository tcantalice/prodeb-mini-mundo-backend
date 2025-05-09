<?php

namespace App\Providers;

use App\Repositories\Projeto\ProjetoRepositoryEloquent;
use Domain\Projeto\Contracts\ProjetoRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (!$this->app->runningUnitTests()) {
            $this->registerEloquentRepositories();
        }
    }

    private function registerEloquentRepositories()
    {
        $this->app->singleton(ProjetoRepository::class, ProjetoRepositoryEloquent::class);
    }
}
