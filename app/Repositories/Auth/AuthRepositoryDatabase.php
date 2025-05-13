<?php

namespace App\Repositories\Auth;

use App\Auth\Contracts\AuthRepository;
use App\Auth\User;
use Domain\Usuario\Usuario;
use Illuminate\Support\Facades\DB;
use Psr\Log\LoggerInterface;

class AuthRepositoryDatabase implements AuthRepository
{
    public function __construct(private LoggerInterface $logger)
    {
        //
    }

    public function save(Usuario $usuario, string $senha): void
    {
        try {
            DB::table('usuario')
                ->insert([
                    "login" => $usuario->getId(),
                    "nome" => $usuario->getNome(),
                    "senha" => $senha,
                    "email" => $usuario->getEmail()
                ]);
        } catch(\Throwable $th) {
            $this->logger->error(
                "Ocorreu uma falha durante a execução da operação no banco de dados: {$th->getMessage()}"
            );
        }
    }


    public function existsByLogin(string $login): bool
    {
        return DB::table('usuario')->where('login', $login)
            ->whereNull('deleted_at')
            ->exists();
    }
}
