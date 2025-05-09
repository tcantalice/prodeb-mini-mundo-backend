<?php

namespace App\Api\Requests\Projeto;

use App\Api\Requests\PreAutorizada;
use Illuminate\Foundation\Http\FormRequest;

class CriarProjetoRequest extends FormRequest
{
    use PreAutorizada;

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'orcamento' => 'nullable|numeric|min:0',
        ];
    }
}
