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
        $this->logger->info('Criando projeto');

        $useCaseInputData = new CriarProjetoDTO(
            $request->input('nome'),
            '', // TODO: Adicionar a referência do usuário autenticado
            $request->input('descricao'),
            $request->input('orcamento')
        );

        // TODO: Adicionar tratamento de exceções
        $this->criarProjetoUseCase->execute($useCaseInputData);

        $this->logger->info('Projeto criado com sucesso');

        return response()->json(['message' => 'Projeto criado com sucesso!']);
    }
}
