<?php

namespace App\Api\Controllers;

use App\Api\Requests\Projeto\CriarProjetoRequest;
use App\UseCases\Projeto\CriarProjeto;
use App\UseCases\Projeto\CriarProjetoDTO;
use Psr\Log\LoggerInterface;

class ProjetoController extends Controller
{
    public function __construct(LoggerInterface $logger, private readonly CriarProjeto $criarProjetoUseCase)
    {
        parent::__construct($logger);
    }

    public function create(CriarProjetoRequest $request)
    {
        $useCaseInputData = new CriarProjetoDTO(
            $request->input('nome'),
            '', // TODO: Adicionar a referência do usuário autenticado
            $request->input('descricao'),
            $request->input('orcamento')
        );

        $this->criarProjetoUseCase->execute($useCaseInputData);

        return response()->json(['message' => 'Projeto criado com sucesso!']);
    }
}
