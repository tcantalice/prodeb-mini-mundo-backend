<?php

namespace Domain\Tarefa;

class TarefaDependenteList
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
        return $this->tarefas;
    }

    public function isEmpty()
    {
        return empty($this->getTarefas());
    }
}
