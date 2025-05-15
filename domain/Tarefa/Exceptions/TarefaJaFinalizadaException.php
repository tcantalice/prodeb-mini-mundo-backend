<?php

namespace Domain\Tarefa\Exceptions;

use Domain\Tarefa\Tarefa;

class TarefaJaFinalizadaException extends \Exception
{
    public function __construct(private readonly Tarefa $tarefa)
    {
        parent::__construct("A tarefa \"{$tarefa->getDescricao()}\" já foi finalizada.");
    }
}
