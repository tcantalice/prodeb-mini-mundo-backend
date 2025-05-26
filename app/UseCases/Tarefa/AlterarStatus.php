<?php

namespace App\UseCases\Tarefa;

use Domain\Tarefa\Contracts\TarefaRepository;
use Domain\Tarefa\Exceptions\TarefaJaFinalizadaException;
use Domain\Tarefa\Exceptions\TarefaNaoEncontradaException;

class AlterarStatus
{
    public function __construct(private TarefaRepository $tarefaRepository)
    {
        //
    }

    public function execute(string $id)
    {
        $tarefa = $this->tarefaRepository->find($id);

        if (!$tarefa) {
            throw new TarefaNaoEncontradaException($id);
        }

        if ($tarefa->isConcluida()) {
            throw new TarefaJaFinalizadaException($tarefa);
        }

        $tarefa->isIniciada() ? $tarefa->finalizar() : $tarefa->iniciar();

        $this->tarefaRepository->save($tarefa);
    }
}
