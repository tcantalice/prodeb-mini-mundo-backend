<?php

namespace App\Api\Controllers;

use App\Api\Requests\Projeto\CriarProjetoRequest;
use App\UseCases\Projeto\CriarProjeto;
use App\UseCases\Projeto\CriarProjetoDTO;
use App\UseCases\Projeto\ListarProjetos;
use App\UseCases\Projeto\ProjetoDTO;
use Carbon\Carbon;
use \DateTimeInterface;
use Psr\Log\LoggerInterface;

class ProjetoController extends Controller
{
    public function __construct(LoggerInterface $logger, )
    {
        parent::__construct($logger);
    }

    public function create(CriarProjetoRequest $request, CriarProjeto $criarProjetoUseCase)
    {
        $this->logger->info('Criando projeto');

        $useCaseInputData = new CriarProjetoDTO(
            $request->input('nome'),
            '', // TODO: Adicionar a referência do usuário autenticado
            $request->input('descricao'),
            $request->input('orcamento')
        );

        // TODO: Adicionar tratamento de exceções
        $criarProjetoUseCase->execute($useCaseInputData);

        $this->logger->info('Projeto criado com sucesso');

        return response()->json(['message' => 'Projeto criado com sucesso!']);
    }

    public function list(ListarProjetos $listarProjetosUseCase)
    {
        $this->logger->info('Listando projetos');

        $projetos = $listarProjetosUseCase->execute();

        return response()->json(
            collect($projetos)->map(function (ProjetoDTO $projeto) {
                return [
                    'id' => $projeto->id,
                    'nome' => $projeto->nome,
                    'criado_em' => Carbon::createFromInterface($projeto->criadoEm)->format(DateTimeInterface::ATOM),
                    'criado_por' => [
                        'id' => $projeto->criadoPor->ref,
                        'nome' => $projeto->criadoPor->nome,
                    ],
                ];
            })
        );
    }
}
