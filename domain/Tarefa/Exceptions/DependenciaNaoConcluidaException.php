<?php

namespace Domain\Tarefa\Exceptions;

use Domain\Tarefa\Tarefa;

class DependenciaNaoConcluidaException extends \Exception
{
    public function __construct(private readonly Tarefa $tarefa)
    {
        parent::__construct('A dependência da tarefa ainda não foi concluída.');
    }

    public function getTarefaRef(): string
    {
        return $this->tarefa->getID()->valor;
    }

    public function getTarefaPredecessoraRef(): string
    {
        return $this->tarefa->dependeDe()->getRef()->valor;
    }
}
