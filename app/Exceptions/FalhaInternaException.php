<?php

namespace App\Exceptions;

class FalhaInternaException extends \Exception
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct("Um erro interno não esperado ocorreu.", previous: $previous);
    }
}
