<?php

namespace App\Infraestructure\Eloquent\Tarefa;

use App\Infraestructure\Eloquent\Tarefa\Tarefa as Model;
use Domain\Tarefa\Contracts\TarefaRepository as Contract;
use Domain\Tarefa\Tarefa;

class TarefaRepository implements Contract
{
    public function findAllByDependencia(string $tarefaRef): array
    {
        return Model::byDependencia(tarefaRef: $tarefaRef)
            ->orderBy(Model::CRIADO_EM)->get()
            ->map(fn (Model $item) => $item->toEntity())
            ->toArray();
    }

    public function findAllByProjeto(string $projetoRef): array
    {
        return Model::byProjeto(projetoRef: $projetoRef)
            ->orderBy(Model::CRIADO_EM)
            ->get()
            ->map(fn (Model $item) => $item->toEntity())
            ->toArray();
    }

    public function save(Tarefa $tarefa): void
    {

    }
}
