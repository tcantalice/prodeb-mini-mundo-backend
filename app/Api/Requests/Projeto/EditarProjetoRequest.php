<?php

namespace App\Api\Requests\Projeto;

use App\Api\Requests\PreAutorizada;
use Illuminate\Foundation\Http\FormRequest;

class EditarProjetoRequest extends FormRequest
{
    use PreAutorizada;

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'ativo' => 'required|boolean',
            'orcamento_disponivel' => 'nullable|numeric|min:0',
        ];
    }
}
