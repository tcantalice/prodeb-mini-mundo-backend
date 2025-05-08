<?php

namespace App\UseCases\Projeto;

class CriarProjetoDTO
{
    public function __construct(
        public readonly string $nome,
        public readonly ?string $descricao = null,
        public readonly ?float $orcamento = null,
    ) {}
}
