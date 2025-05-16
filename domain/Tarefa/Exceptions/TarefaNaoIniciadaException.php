<?php

namespace Domain\Tarefa\Exceptions;

use Domain\Tarefa\Tarefa;

class TarefaNaoIniciadaException extends \Exception
{
    public function __construct(private readonly Tarefa $tarefa)
    {
        parent::__construct('A tarefa ainda não foi iniciada');
    }

    public function getTarefaRef(): string
    {
        return $this->tarefa->getID()->valor;
    }
}
