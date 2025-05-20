<?php

namespace App\UseCases\Tarefa;

use Domain\Tarefa\Contracts\TarefaRepository;
use Domain\Tarefa\Tarefa;

class ListarTarefasProjeto
{
    public function __construct(private TarefaRepository $repository)
    {
        //
    }

    public function execute(string $projetoRef): array
    {
        return array_map(function (Tarefa $entity) {
            return new TarefaOutput(
                $entity->getID()->valor,
                $entity->getDescricao(),
                $entity->getProjetoRef(),
                $entity->criadoEm(),
                $entity->iniciadoEm(),
                $entity->finalizadoEm(),
                $entity->dependeDe()->getRef() ?? null
            );
        }, $this->repository->findAllByProjeto($projetoRef));
    }
}
