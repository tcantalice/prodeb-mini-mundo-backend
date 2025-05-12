<?php

namespace Domain\Usuario;

class Usuario
{
    public function __construct(
        private string $login,
        private string $nome,
        private string $email
    ) {
        //
    }

    public function getLogin(): string
    {
        return $this->login;
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
