<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\Tarefa;
use Domain\Tarefa\TarefaDependenteList;

interface DependenciaTarefaRepository
{
    function findAllDependentes(string $id): TarefaDependenteList;

    function addDependencia(Tarefa $tarefa, Tarefa $dependencia): void;

    function removeDependencia(Tarefa $tarefa, Tarefa $dependencia): void;
}
