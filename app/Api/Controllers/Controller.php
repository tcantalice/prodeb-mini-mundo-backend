<?php

namespace App\Api\Controllers;

use DateTimeInterface;
use Psr\Log\LoggerInterface;

abstract class Controller
{
    public function __construct(protected LoggerInterface $logger)
    {
        //
    }

    protected function serializeDateTime(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    protected function makeSuccessResponse(?array $data = null, int $statusCode = 200)
    {
        return $data !== null
            ? response()->json(["data" => $data], $statusCode)
            : response(status: $statusCode);
    }
}
