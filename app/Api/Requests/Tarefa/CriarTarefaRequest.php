<?php

namespace App\Api\Requests\Tarefa;

use App\Api\Requests\PreAutorizada;
use Illuminate\Foundation\Http\FormRequest;

class CriarTarefaRequest extends FormRequest
{
    use PreAutorizada;

    public function rules()
    {
        return [
            "descricao"=> 'required|string|max:255',
            "depende_de" => 'nullable|string|uuid',
        ];
    }
}
