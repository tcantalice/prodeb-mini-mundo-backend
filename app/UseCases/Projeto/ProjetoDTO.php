<?php

namespace App\UseCases\Projeto;

class ProjetoDTO
{
    public function __construct(
        public readonly String $id,
        public readonly string $nome,
        public readonly ?string $descricao,
        public readonly ?float $orcamento,
        public readonly bool $ativo,
        public readonly CriadorProjetoDTO $criadoPor,
        public readonly \DateTimeInterface $criadoEm
    ) {
        //
    }
}
