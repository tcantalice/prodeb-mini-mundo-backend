<?php

namespace Domain\Tarefa;

class TarefaPredecessora
{
    public function __construct(private IdTarefa $ref, private ?\DateTimeInterface $concluidaEm)
    {
        //
    }

    public function getConcluidaEm(): ?\DateTimeInterface
    {
        return $this->concluidaEm;
    }

    public function isConcluida(): bool
    {
        return $this->getConcluidaEm() !== null;
    }

    public function getRef(): IdTarefa
    {
        return $this->ref;
    }
}
