<?php

namespace Domain\Tarefa;

use Domain\Tarefa\Contracts\DependenciaTarefaRepository;

class DependenciaService
{
    public function __construct(private DependenciaTarefaRepository $dependenciaRepo)
    {
        //
    }

    public function adicionarDependente(Tarefa $tarefa, Tarefa $dependencia)
    {
        if ($tarefa->equals($dependencia)) {
            throw new \Exception('Uma tarefa não pode ser dependente dela mesma');
        }

        if ($tarefa->hasDependencia()) {
            throw new \Exception('A tarefa já possui dependência');
        }

        $this->dependenciaRepo->addDependencia($tarefa, $dependencia);
    }
}
