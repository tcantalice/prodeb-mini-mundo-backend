<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\Tarefa;

interface TarefaRepository
{
    function save(Tarefa $tarefa): void;

    function find(string $id): ?Tarefa;

    function findAllByProjeto(string $projetoRef): array;
}
