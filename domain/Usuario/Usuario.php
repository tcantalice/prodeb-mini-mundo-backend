<?php

namespace Domain\Usuario;

class Usuario
{
    public function __construct(
        private string $id,
        private string $nome,
        private string $email
    ) {
        //
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function equals($other): bool
    {
        if (!$other instanceof Usuario) {
            return false;
        }

        return strcmp($this->login, $other->getLogin()) === 0;
    }
}
