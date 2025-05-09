<?php

namespace Domain\Projeto;

class ProjetoFilter
{
    public function __construct(public readonly ?bool $ativo = null)
    {
        //
    }
}
