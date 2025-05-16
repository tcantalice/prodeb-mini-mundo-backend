<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\Tarefa;

interface TarefaRepository
{
    function save(Tarefa $tarefa): void;

    function findAllByDependencia(string $tarefaRef): array;

    function findAllByProjeto(string $projetoRef): array;
}
