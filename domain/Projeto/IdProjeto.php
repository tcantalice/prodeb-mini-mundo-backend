<?php

namespace Domain\Projeto;

class IdProjeto
{
    private function __construct(public readonly String $valor, public readonly bool $novo)
    {
        //
    }

    public static function restore(): IdProjeto
    {
        // Restaurar o UUID do Projeto

        return new static('', false);
    }

    public static function generate(): IdProjeto
    {
        // Gerar o UUID do Projeto

        return new static('', true);
    }

    public function isNovo(): bool
    {
        return $this->novo;
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
