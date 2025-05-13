<?php

namespace App\Auth;

use Domain\Usuario\Contracts\UsuarioRepository;

class Service
{
    public function __construct(private UsuarioRepository $usuarioRepository) {
        // Constructor logic if needed
    }

    public function authenticate(string $login, string $password): AuthenticationSuccess
    {
        $credentials = [
            User::LOGIN => $login,
            'password' => $password,
        ];

        if (!$token = auth()->attempt($credentials)) {
            throw new \Exception('Credenciais inválidas');
        }

        return new AuthenticationSuccess(
            auth()->user(),
            $token,
            'Bearer',
            now()->addMinutes(auth()->factory()->getTTL() * 60)
        );
    }

    public function check(string $token)
    {
        try {
            auth()->setToken($token)->checkOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $tee) {
            // TODO: Lançar uma exception mais específica
        } catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $tie) {
            // TODO: Lançar uma exception mais específica
        } catch(\Tymon\JWTAuth\Exceptions\JWTException $jwte) {
            // TODO: Lançar uma exception mais específica
        }
    }

    public function isUser(string $login): bool
    {
        return $this->usuarioRepository->existsByLogin($login);
    }
}
