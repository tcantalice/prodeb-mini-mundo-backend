<?php

namespace App\Api\Controllers;

use App\Api\Requests\Auth\LoginRequest;
use App\Auth\Service as AuthService;
use Psr\Log\LoggerInterface;

class AuthController extends Controller
{
    public function __construct(LoggerInterface $logger, private AuthService $authService)
    {
        parent::__construct($logger);
    }

    public function login(LoginRequest $request)
    {
        $this->logger->info('Realizando login', [
            'login' => $request->getLogin(),
        ]);

        try {
            $authenticationSuccess = $this->authService->authenticate($request->getLogin(), $request->getSenha());

            return $this->makeSuccessResponse([
                'token' => $authenticationSuccess->getToken(),
                'type' => $authenticationSuccess->getTokenType(),
                'expires_at' => $this->serializeDateTime($authenticationSuccess->getExpiresIn()),
            ]);
        } catch (\Exception $e) {
            $this->logger->warning("Falha ao realizar login: {$e->getMessage()}", [
                'login' => $request->getLogin(),
            ]);

            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
