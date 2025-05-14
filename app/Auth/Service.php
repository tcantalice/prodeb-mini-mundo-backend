<?php

namespace App\Auth;

use App\Auth\Contracts\AuthRepository;
use Domain\Usuario\Usuario;
use Illuminate\Support\Facades\Hash;
use Psr\Log\LoggerInterface;

class Service
{
    public function __construct(
        private LoggerInterface $logger,
        private AuthRepository $authRepository
    ) {
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
        return $this->authRepository->existsByLogin($login);
    }

    public function register(RegisterData $data)
    {
        if ($this->isUser($data->login)) {
            // TODO: Lançar exceção mais específica
        }

        $usuario = new Usuario(
            $data->login,
            $data->nome,
            $data->email
        );

        $senha = Hash::make($data->senha);

        try {
            $this->authRepository->save($usuario, $senha);
        } catch(\Exception $e) {
            $this->logger->error("Ocorreu um erro durante a chamado ao repositório: $e");
            // TODO: Lançar exceçnao mais específica
        }
    }
}
