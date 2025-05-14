<?php

namespace Domain\Usuario\Contracts;

use Domain\Usuario\Usuario;

interface UsuarioRepository
{
    function findByLogin(string $login): ?Usuario;

    function existsByLogin(string $login): bool;
}
