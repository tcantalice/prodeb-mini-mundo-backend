<?php

namespace App\Infraestructure\Eloquent\Tarefa;

use App\Infraestructure\Eloquent\Tarefa\Tarefa as Model;
use Domain\Tarefa\Contracts\TarefaRepository as TarefaRepositoryContract;
use Domain\Tarefa\Contracts\DependenciaTarefaRepository as DependenciaTarefaRepositoryContract;
use Domain\Tarefa\IdTarefa;
use Domain\Tarefa\Tarefa;
use Domain\Tarefa\TarefaDependenteList;
use Psr\Log\LoggerInterface;

class TarefaRepository implements TarefaRepositoryContract, DependenciaTarefaRepositoryContract
{
    public function __construct(private LoggerInterface $logger)
    {
        //
    }

    public function findAllDependentes(string $id): TarefaDependenteList
    {
        return new TarefaDependenteList(
            IdTarefa::restore($id),
            Model::byTarefaPredecessora(tarefaRef: $id)
                ->orderBy(Model::CRIADO_EM)->get()
                ->map(fn (Model $item) => $item->toEntity())
                ->toArray()
        );
    }

    public function findAllByProjeto(string $projetoRef): array
    {
        return Model::byProjeto(projetoRef: $projetoRef)
            ->orderBy(Model::CRIADO_EM)
            ->get()
            ->map(fn (Model $item) => $item->toEntity())
            ->toArray();
    }

    public function save(Tarefa $tarefa): void
    {
        if ($tarefa->getID()->isNovo()) {
            $model = new Model([
                Model::UUID => $tarefa->getID()->valor,
                Model::DESCRICAO => $tarefa->getDescricao(),
                Model::CRIADO_EM => $tarefa->criadoEm()
            ]);

            $model->setAttribute(Model::DOMAIN_REF, $tarefa->getID()->valor);

            $model->setCriador($tarefa->criadoPor()->getRef());
            $model->setProjeto($tarefa->getProjetoRef());

        } else {
            $model = Model::where(Model::DOMAIN_REF, $tarefa->getID()->valor)->first();

            $model->setAttribute(Model::DESCRICAO, $tarefa->getDescricao());
            $model->setAttribute(Model::DATA_HORA_INICIO, $tarefa->iniciadoEm());
            $model->setAttribute(Model::DATA_HORA_FIM, $tarefa->finalizadoEm());
        }

        if ($tarefa->hasDependencia()) {
            $model->setTarefaPredecessora($tarefa->dependeDe()->getRef()->valor);
        }

        try {
            $model->save();
        } catch(\Throwable $th) {
            $this->logger->error(
                "Ocorreu uma falha durante a execução da operação no banco de dados: {$th->getMessage()}"
            );

            throw $th;
        }
    }

    public function find(string $id): ?Tarefa
    {
        $queryResult = Model::where(Model::DOMAIN_REF, $id)->first();

        return $queryResult ? $queryResult->toEntity() : null;
    }
}
