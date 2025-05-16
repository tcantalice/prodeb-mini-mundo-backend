<?php

namespace Domain\Tarefa;

class TarefaPredecessora
{
    public function __construct(private IdTarefa $ref, private bool $concluida)
    {
        //
    }

    public function isConcluida(): bool
    {
        return $this->concluida;
    }

    public function getRef(): IdTarefa
    {
        return $this->ref;
    }
}
