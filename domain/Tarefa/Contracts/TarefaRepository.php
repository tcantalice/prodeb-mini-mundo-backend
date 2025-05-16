<?php

namespace Domain\Tarefa\Contracts;

use Domain\Tarefa\Tarefa;

interface TarefaRepository
{
    function save(Tarefa $tarefa): void;

    function findAllByTarefaDependente(string $tarefaRef): array;

    function findAllByProjeto(string $projetoRef): array;
}
