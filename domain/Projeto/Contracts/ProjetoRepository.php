<?php

namespace Domain\Projeto\Contracts;

use Domain\Projeto\Projeto;
use Domain\Projeto\ProjetoFilter;

interface ProjetoRepository
{
    function save(Projeto $projeto): void;

    function find(String $id): ?Projeto;

    function existsByNome(String $nome): bool;

    function findByNome(String $nome): ?Projeto;

    function remove(Projeto $projeto): void;

    function findAll(?ProjetoFilter $filtros = null): array;
}
