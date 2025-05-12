<?php

namespace App\Domain\Usuario\Contracts;

use DateTimeInterface;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthenticationSuccess
{
    public function __construct(
        private Authenticatable $authenticated,
        private string $token,
        private string $tokenType,
        private DateTimeInterface $expiresIn
    ) {
    }

    public function getAuthenticated(): Authenticatable
    {
        return $this->authenticated;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): DateTimeInterface
    {
        return $this->expiresIn;
    }
}
