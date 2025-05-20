<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\Tarefa;

interface TarefaRepository
{
    function save(Tarefa $tarefa): void;

    function find(string $id): ?Tarefa;

    function findAllByDependencia(string $id): array;

    function findAllByProjeto(string $projetoRef): array;
}
