<?php

namespace App\Auth\Contracts;

use Domain\Usuario\Usuario;

interface AuthRepository
{
    function save(Usuario $usuario, string $senha): void;

    function existsByLogin(string $login): bool;
}
