<?php

namespace App\Api\Controllers;

use Psr\Log\LoggerInterface;

abstract class Controller
{
    // Base controller for the API
    // This can be extended by other controllers to share common functionality

    public function __construct(protected LoggerInterface $logger)
    {
        // Initialization code can go here
    }
}
