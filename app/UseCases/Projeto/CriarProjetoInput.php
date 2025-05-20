<?php

namespace App\UseCases\Projeto;

class CriarProjetoInput
{
    public function __construct(
        public readonly String $nome,
        public readonly String $refUsuarioCriador,
        public readonly ?String $descricao = null,
        public readonly ?float $orcamento = null,
    ) {}
}
