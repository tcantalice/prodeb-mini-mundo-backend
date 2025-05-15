<?php

namespace Domain\Tarefa;

use Common\Utils\Uuid;

class IdTarefa
{
    private function __construct(public readonly String $valor, public readonly bool $novo)
    {
        //
    }

    public static function restore(string $id): IdTarefa
    {
        if (!Uuid::isValid($id)) {
            throw new \InvalidArgumentException('Formato do ID da tarefa é inválido.');
        }

        return new static($id, false);
    }

    public static function generate(): IdTarefa
    {
        return new static(Uuid::generate(), true);
    }

    public function isNovo(): bool
    {
        return $this->novo;
    }

    public function equals($other): bool
    {
        if (!($other instanceof IdTarefa)) {
            return false;
        }

        return strcmp($this->valor, $other->valor) === 0;
    }

    public function __toString()
    {
        return $this->valor;
    }
}
