<?php

namespace App\UseCases\Tarefa;

use Domain\Tarefa\Contracts\TarefaRepository;
use Domain\Tarefa\Exceptions\TarefaNaoEncontradaException;

class RemoverDependencia
{
    public function __construct(private TarefaRepository $tarefaRepository)
    {
        //
    }

    public function execute(string $id): TarefaOutput
    {
        $tarefa = $this->tarefaRepository->find($id);

        if (!$tarefa) {
            throw new TarefaNaoEncontradaException($id);
        }

        if ($tarefa->hasDependencia()) {
            $tarefa->removeDependencia();

            $this->tarefaRepository->save($tarefa);
        }

        return new TarefaOutput(
            $tarefa->getID()->valor,
            $tarefa->getDescricao(),
            $tarefa->getProjetoRef(),
            $tarefa->criadoEm(),
            $tarefa->iniciadoEm(),
            $tarefa->finalizadoEm(),
            $tarefa->dependeDe(),
        );
    }
}
