<?php

namespace App\UseCases\Tarefa;

class CriarTarefaDTO
{
    public function __construct(
        public readonly string $descricao,
        public readonly string $refProjeto,
        public readonly string $refUsuarioCriador,
        public readonly ?string $refTarefaPredecessora = null
    ) {
        //
    }
}
