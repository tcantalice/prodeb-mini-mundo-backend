<?php

namespace App\Api\Requests\Auth;

use App\Api\Requests\PreAutorizada;

class LoginRequest extends \Illuminate\Foundation\Http\FormRequest
{
    use PreAutorizada;

    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'senha' => 'required|string',
        ];
    }

    public function getLogin(): string
    {
        return $this->input('login');
    }

    public function getSenha(): string
    {
        return $this->input('senha');
    }
}
