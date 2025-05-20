<?php

namespace App\UseCases\Tarefa;

use \DateTimeInterface;

class TarefaOutput
{
    public function __construct(
        public readonly string $id,
        public readonly string $descricao,
        public readonly string $projetoRef,
        public readonly DateTimeInterface $criadaEm,
        public readonly ?DateTimeInterface $dataInicio,
        public readonly ?DateTimeInterface $dataFim,
        public readonly ?string $dependeDe
    ) {
        //
    }
}
