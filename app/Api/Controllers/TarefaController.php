<?php

namespace App\Api\Controllers;

use App\Api\Requests\Tarefa\AlterarDependenciaRequest;
use App\Api\Requests\Tarefa\CriarTarefaRequest;
use App\UseCases\Projeto\ConsultarProjeto;
use App\UseCases\Tarefa\AdicionarDependencia;
use App\UseCases\Tarefa\AlterarStatus;
use App\UseCases\Tarefa\CriarTarefa;
use App\UseCases\Tarefa\CriarTarefaInput;
use App\UseCases\Tarefa\ListarTarefasProjeto;
use App\UseCases\Tarefa\RemoverDependencia;
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

            $tarefa = $criarTarefaUseCase->execute(new CriarTarefaInput(
                $request->input('descricao'),
                $idProjeto,
                Auth::user()->getAuthIdentifier(),
                $request->input('depende_de')
            ));

            return $this->makeSuccessResponse($this->parsePartialTarefaOutputToArray($tarefa),  201);
        } catch(\Throwable $th) {
            return response()->json(['message' => 'Erro ao cadastrar tarefa'], 500);
        }
    }

    public function listByProjeto(string $projetoId, ListarTarefasProjeto $listarTarefasProjetoUseCase)
    {
        $tarefas = $listarTarefasProjetoUseCase->execute($projetoId);

        return $this->makeSuccessResponse(collect($tarefas)
            ->map(function (TarefaOutput $tarefa) {
                return $this->parsePartialTarefaOutputToArray($tarefa);
            })->toArray());
    }

    public function changeStatus(string $tarefaId, AlterarStatus $alterarStatusUseCase)
    {
        $tarefa = $alterarStatusUseCase->execute($tarefaId);

        return $this->makeSuccessResponse($this->parsePartialTarefaOutputToArray($tarefa));
    }

    public function changeDependencia(
        string $tarefaId,
        AlterarDependenciaRequest $request,
        AdicionarDependencia $adicionarDependenciaUseCase,
        RemoverDependencia $removerDependenciaUseCase
    ) {
        $dependenciaId = $request->getDependenciaId();

        $tarefa = $dependenciaId
            ? $adicionarDependenciaUseCase->execute($tarefaId, $dependenciaId)
            : $removerDependenciaUseCase->execute($tarefaId);

        return $this->makeSuccessResponse($this->parsePartialTarefaOutputToArray($tarefa));
    }

    private function parsePartialTarefaOutputToArray(TarefaOutput $output): array
    {
        return [
            "id"            => $output->id,
            "descricao"     => $output->descricao,
            "iniciada_em"   => $output->dataInicio ? $this->serializeDateTime($output->dataInicio) : null,
            "finalizada_em" => $output->dataFim ? $this->serializeDateTime($output->dataFim) : null,
            "depende_de"    => $output->dependeDe
        ];
    }
}
