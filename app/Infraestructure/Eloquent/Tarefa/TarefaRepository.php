<?php

namespace App\Infraestructure\Eloquent\Tarefa;

use App\Infraestructure\Eloquent\Tarefa\Tarefa as Model;
use Domain\Tarefa\Contracts\TarefaRepository as Contract;
use Domain\Tarefa\Tarefa;
use Psr\Log\LoggerInterface;

class TarefaRepository implements Contract
{
    public function __construct(private LoggerInterface $logger)
    {
        //
    }
    public function findAllByDependencia(string $id): array
    {
        return Model::byTarefaPredecessora(tarefaRef: $id)
            ->orderBy(Model::CRIADO_EM)->get()
            ->map(fn (Model $item) => $item->toEntity())
            ->toArray();
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
        $model = new Model([
            Model::UUID => $tarefa->getID()->valor,
            Model::DESCRICAO => $tarefa->getDescricao(),
            Model::CRIADO_EM => $tarefa->criadoEm()
        ]);

        $model->setAttribute(Model::DOMAIN_REF, $tarefa->getID()->valor);

        $model->setCriador($tarefa->criadoPor()->getRef());
        $model->setProjeto($tarefa->getProjetoRef());

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
