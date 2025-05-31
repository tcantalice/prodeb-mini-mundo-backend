<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\Tarefa;
use Domain\Tarefa\TarefaDependenteList;

interface DependenciaTarefaRepository
{
    function findAllDependentes(string $id): TarefaDependenteList;
}
