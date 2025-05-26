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

    public function execute(string $id): TarefaOutput
    {
        $tarefa = $this->tarefaRepository->find($id);

        if (!$tarefa) {
            throw new TarefaNaoEncontradaException($id);
        }

        $tarefa->isIniciada() ? $tarefa->finalizar() : $tarefa->iniciar();

        $this->tarefaRepository->save($tarefa);

        return new TarefaOutput(
            $tarefa->getID()->valor,
            $tarefa->getDescricao(),
            $tarefa->getProjetoRef(),
            $tarefa->criadoEm(),
            $tarefa->iniciadoEm(),
            $tarefa->finalizadoEm(),
            $tarefa->dependeDe()->getRef(),
        );
    }
}
