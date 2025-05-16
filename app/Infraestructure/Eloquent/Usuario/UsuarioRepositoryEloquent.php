<?php

namespace App\Infraestructure\Eloquent\Usuario;

use App\Infraestructure\Eloquent\Usuario\Usuario as Model;
use Domain\Usuario\Contracts\UsuarioRepository;
use Domain\Usuario\Usuario;

class UsuarioRepositoryEloquent implements UsuarioRepository
{
    public function findByLogin(string $login): ?Usuario
    {
        $queryResult = Model::where(Model::LOGIN, $login)->first();
        $result = null;

        if ($queryResult !== null) {
            $result = new Usuario(
                $$queryResult->getAttribute(Model::LOGIN),
                $queryResult->getAttribute(Model::NOME),
                $queryResult->getAttribute(Model::EMAIL)
            );
        }

        return $result;
    }

    public function existsByLogin(string $login): bool
    {
        return Model::where(Model::LOGIN, $login)->exists();
    }
}
