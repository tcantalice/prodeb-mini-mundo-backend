<?php

namespace App\UseCases\Projeto;

class ProjetoOutput
{
    public function __construct(
        public readonly string $id,
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
