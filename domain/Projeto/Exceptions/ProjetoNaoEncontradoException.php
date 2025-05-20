<?php

namespace Domain\Projeto\Exceptions;

class ProjetoNaoEncontradoException extends \Exception
{
    public function __construct(private string $projetoRef)
    {
        parent::__construct("O projeto \"{$projetoRef}\" não foi encontrado");
    }

    public function getProjetoRef(): string
    {
        return $this->projetoRef;
    }
}
