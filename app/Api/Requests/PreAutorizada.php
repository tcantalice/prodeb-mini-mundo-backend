<?php

namespace App\Api\Requests;

trait PreAutorizada
{
    public function authorize(): bool
    {
        return true;
    }
}
