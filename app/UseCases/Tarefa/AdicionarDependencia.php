<?php

namespace App\UseCases\Tarefa;

use Domain\Tarefa\Contracts\TarefaRepository;
use Domain\Tarefa\Exceptions\TarefaNaoEncontradaException;

class AdicionarDependencia
{
    public function __construct(private TarefaRepository $tarefaRepository)
    {
        //
    }

    public function execute(string $tarefaId, string $dependenciaId): TarefaOutput
    {
        $tarefa = $this->tarefaRepository->find($tarefaId);

        if (!$tarefa) {
            throw new TarefaNaoEncontradaException($tarefaId);
        }

        $dependencia = $this->tarefaRepository->find($dependenciaId);

        if (!$dependencia) {
            throw new TarefaNaoEncontradaException($dependenciaId);
        }

        $tarefa->addDependencia($dependencia);

        $this->tarefaRepository->save($tarefa);

        return new TarefaOutput(
            $tarefa->getID(),
            $tarefa->getDescricao(),
            $tarefa->getProjetoRef(),
            $tarefa->criadoEm(),
            $tarefa->iniciadoEm(),
            $tarefa->finalizadoEm(),
            $tarefa->dependeDe()?->getRef()
        );
    }
}
