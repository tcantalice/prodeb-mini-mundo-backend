<?php

namespace App\Api\Controllers;

use App\Api\Requests\Projeto\CriarProjetoRequest;
use App\Api\Requests\Projeto\EditarProjetoRequest;
use App\UseCases\Projeto\ConsultarProjeto;
use App\UseCases\Projeto\CriarProjeto;
use App\UseCases\Projeto\CriarProjetoInput;
use App\UseCases\Projeto\EditarProjeto;
use App\UseCases\Projeto\EditarProjetoInput;
use App\UseCases\Projeto\ListarProjetos;
use App\UseCases\Projeto\ProjetoOutput;
use Illuminate\Support\Facades\Auth;
use Psr\Log\LoggerInterface;

class ProjetoController extends Controller
{
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct($logger);
    }

    public function create(CriarProjetoRequest $request, CriarProjeto $criarProjetoUseCase)
    {
        $useCaseInputData = new CriarProjetoInput(
            $request->input('nome'),
            Auth::user()->getAuthIdentifier(),
            $request->input('descricao'),
            $request->input('orcamento')
        );

        $criarProjetoUseCase->execute($useCaseInputData);

        return $this->makeSuccessResponse(statusCode: 201);
    }

    public function update(string $idProjeto, EditarProjetoRequest $request, EditarProjeto $editarProjetoUseCase)
    {
        $this->logger->info('Atualizando projeto');

        $useCaseInputData = new EditarProjetoInput(
            $idProjeto,
            $request->input('nome'),
            $request->input('descricao'),
            $request->input('orcamento'),
            $request->input('ativo')
        );

        try {
            $editarProjetoUseCase->execute($useCaseInputData);
        } catch (\Throwable $th) {
            $this->logger->error('Erro ao atualizar projeto: ' . $th->getMessage());
            return response()->json(["message" => 'Erro ao atualizar projeto'], 500);
        }

        return $this->makeSuccessResponse(statusCode: 200);
    }

    public function list(ListarProjetos $listarProjetosUseCase)
    {
        $this->logger->info('Listando projetos');

        $projetos = $listarProjetosUseCase->execute();

        return $this->makeSuccessResponse(
            collect($projetos)->map(function (ProjetoOutput $projeto) {
                return [
                    "id" => $projeto->id,
                    "nome" => $projeto->nome,
                    "criado_em" => $this->serializeDateTime($projeto->criadoEm),
                    "criado_por" => [
                        "id" => $projeto->criadoPor->ref,
                        "nome" => $projeto->criadoPor->nome,
                    ],
                ];
            })->toArray()
        );
    }

    public function get(string $id, ConsultarProjeto $consultarProjetoUseCase)
    {
        $this->logger->info('Consultando projeto com ID: ' . $id);

        try {
            $projeto = $consultarProjetoUseCase->execute($id);
        } catch (\Throwable $th) {
            $this->logger->error('Erro ao consultar projeto: ' . $th->getMessage());
            return response()->json(["message" => 'Erro ao consultar projeto'], 500);
        }

        return $this->makeSuccessResponse(
            [
                "id" => $projeto->id,
                "nome" => $projeto->nome,
                "descricao" => $projeto->descricao,
                "orcamento" => $projeto->orcamento,
                "criado_em" => $this->serializeDateTime($projeto->criadoEm),
                "criado_por" => [
                    "id" => $projeto->criadoPor->ref,
                    "nome" => $projeto->criadoPor->nome,
                ],
            ]
        );
    }
}
