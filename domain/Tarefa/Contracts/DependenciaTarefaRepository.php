<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\TarefaDependenteList;

interface DependenciaTarefaRepository
{
    function findAllDependentes(string $id): TarefaDependenteList;

    function addDependencia(Tarefa $dependente): void;

    function removeDependencia(Tarefa $dependente): void;
}
