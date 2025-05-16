<?php

namespace Domain\Tarefa\Exceptions;

class TarefaNaoEncontradaException extends \Exception
{
    public function __construct(private string $tarefaRef)
    {
        parent::__construct("A tarefa \"{$tarefaRef}\" não foi encontrada");
    }

    public function getTarefaRef(): string
    {
        return $this->tarefaRef;
    }
}
