<?php

namespace Domain\Projeto;

use Common\Utils\Uuid;

class IdProjeto
{
    private function __construct(public readonly String $valor, public readonly bool $novo)
    {
        //
    }

    public static function restore(string $id): IdProjeto
    {
        if (!Uuid::isValid($id)) {
            throw new \InvalidArgumentException('Formato do ID do projeto é inválido.');
        }

        return new static($id, false);
    }

    public static function generate(): IdProjeto
    {
        return new static(Uuid::generate(), true);
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
