<?php

namespace Domain\Tarefa;

class TarefasDependentes
{
    /**
     * @var Tarefa[]
     */
    private readonly array $tarefas;

    public function __construct()
    {

    }

    /**
     * @return Tarefa[]
     */
    public function getTarefas(): array
    {
        return [];
    }

    public function isEmpty()
    {
        return empty($this->getTarefas());
    }
}
