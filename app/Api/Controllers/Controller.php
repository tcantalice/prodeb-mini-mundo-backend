<?php

namespace App\Api\Controllers;

use Psr\Log\LoggerInterface;

abstract class Controller
{
    public function __construct(protected LoggerInterface $logger)
    {
        //
    }
}
