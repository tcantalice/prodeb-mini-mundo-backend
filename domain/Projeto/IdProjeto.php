<?php

namespace Domain\Projeto;

class IdProjeto
{
    private String $valor;

    public function __construct(String $valor)
    {
        $this->valor = $valor;
    }

    public function equals($other): bool
    {
        if (!($other instanceof IdProjeto)) {
            return false;
        }

        return strcmp($this->valor, $other->valor) === 0;
    }

    public function __toString()
    {
        return $this->valor;
    }
}
