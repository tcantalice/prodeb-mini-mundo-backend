<?php

namespace App\Auth;

class RegisterData
{
    public function __construct(
        public readonly string $login,
        public readonly string $senha,
        public readonly string $nome,
        public readonly string $email
    ) {
        //
    }
}
