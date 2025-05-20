<?php

namespace App\UseCases\Projeto;

class EditarProjetoInput
{
    public function __construct(
        public String $id,
        public ?String $nome = null,
        public ?String $descricao = null,
        public ?float $orcamento = null,
        public ?bool $ativo = null
    ) {
    }
}
