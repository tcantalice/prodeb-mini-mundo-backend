<?php

namespace App\Api\Controllers;

use App\Api\Requests\Auth\LoginRequest;
use App\Auth\Service as AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
        //
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
                'expires_at' => $this->serializeDateTime($authenticationSuccess->getExpiresAt()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}
