<?php

namespace Domain\Projeto;

class CriadorProjeto
{
    public function __construct(private string $ref, private string $nome)
    {
        //
    }

    public function getRef(): string
    {
        return $this->ref;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
}
