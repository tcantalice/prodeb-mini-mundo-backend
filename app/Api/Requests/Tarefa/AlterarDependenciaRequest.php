<?php

namespace App\Api\Requests\Tarefa;

use App\Api\Requests\PreAutorizada;
use Illuminate\Foundation\Http\FormRequest;

class AlterarDependenciaRequest extends FormRequest
{
    use PreAutorizada;

    public function rules()
    {
        return [
            "dependencia_id" => 'present|nullable|string|uuid'
        ];
    }

    public function getDependenciaId(): ?string
    {
        return $this->input('dependencia_id');
    }
}
