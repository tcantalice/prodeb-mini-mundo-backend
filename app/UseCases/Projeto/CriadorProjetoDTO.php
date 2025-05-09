<?php

namespace App\UseCases\Projeto;

class CriadorProjetoDTO
{
    public function __construct(public readonly string $ref, public readonly string $nome)
    {
        //
    }
}
