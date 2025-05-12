<?php

namespace App\Api\Middlewares;

use App\Auth\Service as AuthService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Contracts\Auth\Factory;

class JWTAuthenticate extends Authenticate
{
    public function __construct(Factory $auth, private AuthService $authService)
    {
        parent::__construct($auth);
    }

    protected function authenticate($request, array $guards = [])
    {
        $token = $request->headers->get('Authorization');

        if ($token === null || empty($token)) {
            throw new AuthenticationException('Token não informado');
        };

        $token = str_replace('Bearer ', '', $token);

        try {
            $this->authService->check($token);
        } catch(\Exception $e) {
            throw new AuthenticationException($e->getMessage());
        }
    }
}
