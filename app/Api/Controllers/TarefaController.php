<?php

namespace App\Api\Controllers;

use App\Api\Requests\Tarefa\CriarTarefaRequest;
use App\UseCases\Projeto\ConsultarProjeto;
use App\UseCases\Tarefa\CriarTarefa;
use App\UseCases\Tarefa\CriarTarefaDTO;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        parent::__construct($logger);
    }

    public function create(
        string $idProjeto,
        CriarTarefaRequest $request,
        CriarTarefa $criarTarefaUseCase,
        ConsultarProjeto $consultarProjetoUseCase
    ) {
        try {
            $consultarProjetoUseCase->execute($idProjeto);

            $criarTarefaUseCase->execute(new CriarTarefaDTO(
                $request->input('descricao'),
                $idProjeto,
                Auth::user()->getAuthIdentifier(),
                $request->input('depende_de')
            ));

            return $this->makeSuccessResponse(statusCode: 201);
        } catch(\Throwable $th) {
            $this->logger->error($th->getMessage());
            return response()->json(['message' => 'Erro ao cadastrar tarefa'], 500);
        }
    }
}
