<?php

namespace App\Api\Controllers;

use App\Api\Requests\Tarefa\CriarTarefaRequest;
use App\UseCases\Projeto\ConsultarProjeto;
use App\UseCases\Tarefa\AlterarStatus;
use App\UseCases\Tarefa\CriarTarefa;
use App\UseCases\Tarefa\CriarTarefaInput;
use App\UseCases\Tarefa\ListarTarefasProjeto;
use App\UseCases\Tarefa\TarefaOutput;
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

            $criarTarefaUseCase->execute(new CriarTarefaInput(
                $request->input('descricao'),
                $idProjeto,
                Auth::user()->getAuthIdentifier(),
                $request->input('depende_de')
            ));

            return $this->makeSuccessResponse(statusCode: 201);
        } catch(\Throwable $th) {
            return response()->json(['message' => 'Erro ao cadastrar tarefa'], 500);
        }
    }

    public function listByProjeto(string $projetoId, ListarTarefasProjeto $listarTarefasProjetoUseCase)
    {
        $tarefas = $listarTarefasProjetoUseCase->execute($projetoId);

        return $this->makeSuccessResponse(collect($tarefas)->map(function (TarefaOutput $tarefa) {
            return [
                "id" => $tarefa->id,
                "descricao" => $tarefa->descricao,
                "iniciada_em" => $tarefa->dataInicio ? $this->serializeDateTime($tarefa->dataInicio) : null,
                "finalizada_em" => $tarefa->dataFim ? $this->serializeDateTime($tarefa->dataFim) : null,
                "depende_de" => $tarefa->dependeDe
            ];
        })->toArray());
    }

    public function changeStatus(string $tarefaId, AlterarStatus $alterarStatusUseCase)
    {
        $tarefa = $alterarStatusUseCase->execute($tarefaId);

        return $this->makeSuccessResponse([
            "id" => $tarefa->id,
            "descricao" => $tarefa->descricao,
            "iniciado_em" => $tarefa->dataInicio ? $this->serializeDateTime($tarefa->dataInicio) : null,
            "finalizada_em" => $tarefa->dataFim ? $this->serializeDateTime($tarefa->dataFim) : null,
            "depende_de" => $tarefa->dependeDe
        ]);
    }
}
