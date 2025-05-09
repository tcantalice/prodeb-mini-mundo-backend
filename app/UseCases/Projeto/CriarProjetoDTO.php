<?php

namespace App\UseCases\Projeto;

class CriarProjetoDTO
{
    public function __construct(
        public readonly String $nome,
        public readonly String $refUsuarioCriador,
        public readonly ?String $descricao = null,
        public readonly ?float $orcamento = null,
    ) {}
}
