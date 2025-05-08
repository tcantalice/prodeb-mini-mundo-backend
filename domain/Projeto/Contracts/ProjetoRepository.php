<?php

namespace Domain\Projeto;

interface ProjetoRepository
{
    function save(Projeto $projeto): void;

    function find(String $id): Projeto;

    function existsByNome(String $nome): bool;

    function findByNome(String $nome): Projeto;

    function remove(Projeto $projeto): void;
}
